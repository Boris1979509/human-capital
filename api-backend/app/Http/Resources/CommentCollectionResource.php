<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/** @mixin Comment */
class CommentCollectionResource extends ResourceCollection
{
    public $collects = CommentResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->buildThread($this->collection)
        ];
    }

    private function buildThread(Collection $collection, int $parentId = null): Collection
    {
        $branch = collect();
        foreach ($collection as $comment) {
            if ($parentId === $comment->parent_id) {
                $replies = $this->buildThread($collection, $comment->id);
                if ($replies) {
                    $comment->replies = $replies;
                }
                $branch[$comment->id] = $comment;
                unset($comment);
            }
        }
        return collect(array_values($branch->toArray()));
    }
}
