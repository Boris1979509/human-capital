<?php

namespace App\Http\Controllers;

use App\Models\TemporaryUpload;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        Auth::setDefaultDriver('api');
    }

    protected function getPerPage(): int
    {
        return request()->get('per_page', request()->get('limit', 15));
    }

    protected function getPage(): int
    {
        return request()->get('page', 1);
    }

    protected function syncMediaWithModel(HasMedia $model, ?array $mediaIds, string $collectionName = 'default'): void
    {
        $currentFileIds = $model->getMedia($collectionName)->pluck('id');

        // добавим новые
        collect($mediaIds)->each(function (int $fileId) use ($collectionName, $model, $currentFileIds) {
            if (!$currentFileIds->contains($fileId)) {
                TemporaryUpload::attachMediaToModel($fileId, $model, $collectionName);
            }
        });

        //удалим не пришедшие файлы
        $fileIdsToDelete = collect($currentFileIds)->diff($mediaIds)->toArray();
        Media::whereIn('id', $fileIdsToDelete)->get()->each->delete();
    }

    protected function requestIsPaginated(): bool
    {
        return request()->has('per_page') || request()->has('page');
    }
}
