<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property int $user_id
 * @property string $body
 * @property int|null $parent_id
 * @property bool $approved
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $commentable
 * @property-read User|null $commentator
 * @property-read Collection|User[] $dislikedUsers
 * @property-read int|null $disliked_users_count
 * @property-read Collection|Dislike[] $dislikes
 * @property-read int|null $dislikes_count
 * @property-read Collection|User[] $likedUsers
 * @property-read int|null $liked_users_count
 * @property-read Collection|Like[] $likes
 * @property-read int|null $likes_count
 * @method static CommentFactory factory(...$parameters)
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereApproved($value)
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereParentId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @mixin Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function commentator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function dislikes(): MorphMany
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }

    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'likes',
            'likeable_id',
            'user_id'
        )->where('likeable_type', 'comment');
    }

    public function dislikedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'dislikes',
            'dislikeable_id',
            'user_id'
        )->where('dislikeable_type', 'comment');
    }
}
