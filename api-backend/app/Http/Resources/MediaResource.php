<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/** @mixin Media */
class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        $fileName = is_array($this->custom_properties) && array_key_exists(
            'original_file_name',
            $this->custom_properties
        )
            ? $this->custom_properties['original_file_name']
            : $this->file_name;

        return [
            'id' => $this->id,
            'file_name' => $fileName,
            'size' => $this->size,
            'custom_properties' => $this->custom_properties,
            'created_at' => $this->created_at,
            'url' => $this->getFullUrl(),
            'thumb' => $this->when(
                $this->hasGeneratedConversion('thumb'),
                fn () => $this->getFullUrl('thumb')
            )
        ];
    }
}
