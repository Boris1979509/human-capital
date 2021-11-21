<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'body' => $this->body,
            'user' => new UserResource($this->commentator),
            'created_at' => $this->created_at,
            'replies' => $this->replies ?? [],
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'is_liked' => $this->is_user_liked > 0,
            'is_disliked' => $this->is_user_disliked > 0
        ];
    }
}
