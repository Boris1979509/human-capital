<?php

namespace App\Http\Resources\Selection;

use App\Http\Resources\MediaResource;
use App\Models\Journal\ContentType;
use App\Models\Selection\Selection;
use App\Models\Selection\SelectionModel;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Selection */
class SelectionItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $selectionableData = null;
        $selectionableType = $this->selectionable_type;

        if ($this->selectionable_type === 'journal') {
            if ($this->selectionable->type === ContentType::EVENT) {
                $selectionableData = new SelectionItemEventResource($this->selectionable);
                $selectionableType = 'event';
            } else {
                $selectionableData = new SelectionItemContentResource($this->selectionable);
                if ($this->selectionable->type === ContentType::ARTICLE) {
                    $selectionableType = 'article';
                } else {
                    $selectionableType = 'news';
                }
            }
        }

        if ($this->selectionable_type === SelectionModel::MODEL_INSTITUTION) {
            $selectionableData = new SelectionItemInstitutionResource($this->selectionable);
        }

        if ($this->selectionable_type === SelectionModel::MODEL_CURRICULUM) {
            $selectionableData = new SelectionItemCurriculumResource($this->selectionable);
        }

        return [
            'id' => $this->id,
            'selectionable_id' => $this->selectionable_id,
            'selectionable_type' => $selectionableType,
            'sort' => $this->sort,
            'description' => $this->description,
            'cover' => new MediaResource($this->getFirstMedia('cover')),
            'selectionable_data' => $selectionableData,
        ];
    }
}
