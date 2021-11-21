<?php

namespace App\Http\Controllers\Api\Journal;

use App\Http\Requests\Journal\CreateOrUpdateContentRequest;
use App\Filters\ContentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Journal\SortContentRequest;
use App\Http\Resources\Journal\ContentDetailedResource;
use App\Http\Resources\Journal\ContentResource;
use App\Models\Employer\Employer;
use App\Models\Institution\Institution;
use App\Models\Journal\Content;
use App\Models\Journal\EventSpeaker;
use App\Models\TemporaryUpload;
use DB;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class ContentsController extends Controller
{
    public function index(ContentFilter $filter, SortContentRequest $sorter): AnonymousResourceCollection
    {
        $contents = Content::published()
            ->withoutEventsInPast()
            ->filter($filter)
            ->sort($sorter)
            ->orderBy('id');

        if ($this->requestIsPaginated()) {
            $contents = $contents->simplePaginate($this->getPerPage());
        } else {
            $contents = $contents->get();
        }

        return ContentResource::collection($contents);
    }

    /**
     * @param  Content  $content
     * @param  Request  $request
     * @return ContentDetailedResource|JsonResponse
     * @throws AuthorizationException
     */
    public function show(Content $content, Request $request)
    {
        $this->authorize('view', [$content]);

        if ($request->has('type') && (int) $request->get('type') !== $content->type) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return new ContentDetailedResource($content);
    }

    /**
     * @param  CreateOrUpdateContentRequest  $request
     * @param  Employer|null  $employer
     * @param  Institution|null  $institution
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function create(
        CreateOrUpdateContentRequest $request,
        ?Employer $employer = null,
        ?Institution $institution = null
    ): JsonResponse {
        if ($institution) {
            $this->authorize('create', [Content::class, $institution]);
        } else {
            $this->authorize('createAsEmployer', [Content::class, $request->get('type')]);
        }

        $content = DB::transaction(function () use ($request, $institution) {
            /** @var Content $content */
            $content = Content::create(
                array_merge(
                    $request->getContentData(),
                    [
                        'institution_id' => optional($institution)->id,
                        'user_id' => auth()->id(),
                    ]
                )
            );
            $content->targetAudience()->sync($request->get('target_audience'));
            $content->participantsAge()->sync($request->get('participants_age'));
            $this->addImagesToContent($content, $request);
            if (request('cover') &&
                optional($content->getFirstMedia('cover'))->id !== request('cover')
            ) {
                TemporaryUpload::attachMediaToModel(request('cover'), $content, 'cover');
            }

            if ($request->getSpeakersData()) {
                foreach ($request->getSpeakersData() as $index => $speaker) {
                    /** @var EventSpeaker $createdSpeaker */
                    $createdSpeaker = $content->speakers()->create(Arr::except($speaker, ['avatar']));
                    $this->addAvatarToSpeaker($createdSpeaker, $speaker);
                }
            }
            return $content;
        });

        return response()->json(new ContentDetailedResource($content), Response::HTTP_CREATED);
    }

    /**
     * @param  Content  $content
     * @param  CreateOrUpdateContentRequest  $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Content $content, CreateOrUpdateContentRequest $request): JsonResponse
    {
        $this->authorize('manage', [$content]);

        $content = DB::transaction(function () use ($request, $content) {
            $content->update($request->getContentData());
            $this->addImagesToContent($content, $request);
            if (request('cover') &&
                optional($content->getFirstMedia('cover'))->id !== request('cover')
            ) {
                TemporaryUpload::attachMediaToModel(request('cover'), $content, 'cover');
            }

            if ($request->has('speakers')) {
                $this->updateContentSpeakers($content, $request->getSpeakersData());
            }

            return $content;
        });

        return response()->json(new ContentDetailedResource($content));
    }

    /**
     * @param  Content  $content
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function delete(Content $content): JsonResponse
    {
        $this->authorize('manage', [$content]);

        $content->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function similar(
        ContentFilter $filter,
        SortContentRequest $sorter,
        Content $content
    ): AnonymousResourceCollection {
        $similarContent = Content::where('type', $content->type)
            ->published()
            ->withoutEventsInPast()
            ->where('id', '<>', $content->id)
            ->filter($filter)
            ->sort($sorter)
            ->simplePaginate($this->getPerPage());

        return ContentResource::collection($similarContent);
    }

    private function updateContentSpeakers(Content $content, array $speakersData): void
    {
        // сначала удалим всех спикеров, которые не пришли в запросе
        $addedSpeakersIds = collect($speakersData)
            ->pluck('id')
            ->filter(fn(?int $speaker) => $speaker !== null)
            ->toArray();
        EventSpeaker::where('content_id', $content->id)->whereNotIn('id', $addedSpeakersIds)->delete();

        // обновим спикеров, которые пришли с айдишником
        $speakersToUpdate = collect($speakersData)->filter(fn($speaker) => isset($speaker['id']));
        $speakersToUpdate->each(
            function (array $speaker) {
                $speakerModel = EventSpeaker::find($speaker['id']);
                $this->addAvatarToSpeaker($speakerModel, $speaker);
                $speakerModel->update(Arr::except($speaker, 'avatar'));
            }
        );

        // добавим новых спикеров, которые пришли без айдишника
        collect($speakersData)->each(function ($speakerData) use ($content) {
            if (!isset($speakerData['id'])) {
                /** @var EventSpeaker $speakerModel */
                $speakerModel = $content->speakers()->create($speakerData);
                $this->addAvatarToSpeaker($speakerModel, $speakerData);
            }
        });

        // удалим файлы спикеров, у которых не пришло поле avatar - это значит, что файл удалили
        $speakerIdsWithDeletedAvatars = collect($speakersData)
            ->filter(fn($speaker) => !isset($speaker['avatar']))
            ->pluck('id')
            ->toArray();
        $content->speakers()
            ->whereIn('id', $speakerIdsWithDeletedAvatars)
            ->each(function (EventSpeaker $speaker) {
                $speaker->clearMediaCollection('avatar');
            });
    }

    private function addImagesToContent(Content $content, Request $request): void
    {
        $this->syncMediaWithModel($content, $request->get('images'), 'images');
    }

    private function addAvatarToSpeaker(EventSpeaker $speaker, array $speakerData): void
    {
        if (isset($speakerData['avatar']) &&
            optional($speaker->getFirstMedia('avatar'))->id !== $speakerData['avatar']
        ) {
            TemporaryUpload::attachMediaToModel($speakerData['avatar'], $speaker, 'avatar');
        }
    }
}
