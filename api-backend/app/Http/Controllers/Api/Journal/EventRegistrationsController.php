<?php

namespace App\Http\Controllers\Api\Journal;

use App\Filters\ContentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Journal\EventRegistrationChangeStatusRequest;
use App\Http\Requests\Journal\EventRegistrationCreateRequest;
use App\Http\Requests\Journal\SortContentRequest;
use App\Http\Resources\EventRegistrationResource;
use App\Http\Resources\Journal\ContentResource;
use App\Models\EventRegistration;
use App\Models\Institution\Institution;
use App\Models\Journal\Content;
use Elasticsearch\Common\Exceptions\Forbidden403Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

class EventRegistrationsController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Content $event): JsonResponse
    {
        $this->authorize('manage', [Content::class, $event]);
        $registrations = EventRegistrationResource::collection($event->registrations);
        return response()->json([
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
            ],
            'registrations' => $registrations
        ]);
    }

    public function show(Content $event): EventRegistrationResource
    {
        $registration = $event->registrations()->where('user_id', auth()->id())->firstOrFail();
        return new EventRegistrationResource($registration);
    }

    public function create(EventRegistrationCreateRequest $request, Content $event): JsonResponse
    {
        if ($event->hasNoAvailableSlotsForRegistration()) {
            return response()->json('Места на регистрацию закончились', Response::HTTP_FORBIDDEN);
        }
        if ($event->isRegistrationPeriodExpired()) {
            return response()->json('Регистрация уже закончилась', Response::HTTP_FORBIDDEN);
        }
        if ($event->registrations()->where('user_id', auth()->id())->exists()) {
            return response()->json('Вы уже подавали заявку', Response::HTTP_FORBIDDEN);
        }
        $event->registrations()->create($request->getRegistrationData());
        return response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function accept(EventRegistrationChangeStatusRequest $request): JsonResponse
    {
        $this->checkIfUserCanManageEventRegistrations($request->getRegistrationsIds());
        EventRegistration::whereIn('id', $request->getRegistrationsIds())->update([
            'status' => EventRegistration::STATUS_ACCEPTED
        ]);
        return response()->json();
    }

    /**
     * @throws AuthorizationException
     */
    public function reject(EventRegistrationChangeStatusRequest $request): JsonResponse
    {
        $this->checkIfUserCanManageEventRegistrations($request->getRegistrationsIds());
        EventRegistration::whereIn('id', $request->getRegistrationsIds())->update([
            'status' => EventRegistration::STATUS_REJECTED
        ]);
        return response()->json();
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(EventRegistrationChangeStatusRequest $request): JsonResponse
    {
        $this->checkIfUserCanManageEventRegistrations($request->getRegistrationsIds());
        EventRegistration::whereIn('id', $request->getRegistrationsIds())->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws AuthorizationException
     */
    private function checkIfUserCanManageEventRegistrations(array $registrationsIds): void
    {
        $eventIds = EventRegistration::whereIn('id', $registrationsIds)->pluck('event_id');
        $eventsUsersIds = Content::whereIn('id', $eventIds)->pluck('user_id');
        $userId = auth()->id();
        foreach ($eventsUsersIds as $eventsUsersId) {
            if ($userId !== $eventsUsersId) {
                throw new AuthorizationException();
            }
        }
    }
}
