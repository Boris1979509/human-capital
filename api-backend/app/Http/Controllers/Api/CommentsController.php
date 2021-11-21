<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\CommentCollectionResource;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentsController extends Controller
{
    public function index(Request $request, string $commentableType, int $commentableId): CommentCollectionResource
    {
        $this->ensureRouteParametersAreCorrect($request);

        $comments = Comment::where('commentable_type', $commentableType)
            ->where('commentable_id', $commentableId)
            ->withCount(['likedUsers as likes_count'])
            ->withCount(['dislikedUsers as dislikes_count'])
            ->when(
                auth()->check(),
                fn (Builder $query) => $query
                    ->withCount([
                        'likedUsers as is_user_liked' => fn (Builder $query) => $query->where('user_id', auth()->id()),
                    ])
                    ->withCount([
                        'dislikedUsers as is_user_disliked' => fn (Builder $query) => $query->where('user_id', auth()->id()),
                    ])
            )
            ->with('commentator')
            ->get();

        return new CommentCollectionResource($comments);
    }

    public function create(CreateCommentRequest $request, string $commentableType, int $commentableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        $comment = Comment::create([
            'commentable_type' => $commentableType,
            'commentable_id' => $commentableId,
            'body' => $request->get('body'),
            'parent_id' => $request->get('parent_id'),
            'user_id' => auth()->id(),
            'approved' => true,
        ]);

        return response()->json(new CommentResource($comment), Response::HTTP_CREATED);
    }

    private function ensureRouteParametersAreCorrect(Request $request): void
    {
        $commentableType = $request->route('commentableType');
        $commentableId = $request->route('commentableId');
        $availableCommentableTypes = config('app.commentable', []);

        if (!array_key_exists($commentableType, $availableCommentableTypes)) {
            abort(404);
        }

        $commentableClass = $availableCommentableTypes[$commentableType];

        if (!$commentableClass::where('id', $commentableId)->exists()) {
            abort(404);
        }
    }
}
