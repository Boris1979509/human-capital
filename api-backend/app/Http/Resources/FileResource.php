<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'url' => url(Storage::url($this->file)),
            'thumb' => ImageHelper::fullPathWithPrefix($this->file, 'small')
        ]);
    }
}
