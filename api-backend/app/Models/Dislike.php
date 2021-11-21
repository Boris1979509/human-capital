<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Dislike
 *
 * @property int $id
 * @property string $dislikeable_type
 * @property int $dislikeable_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $dislikeable
 * @property-read User|null $user
 * @method static Builder|Dislike newModelQuery()
 * @method static Builder|Dislike newQuery()
 * @method static Builder|Dislike query()
 * @method static Builder|Dislike whereCreatedAt($value)
 * @method static Builder|Dislike whereDislikeableId($value)
 * @method static Builder|Dislike whereDislikeableType($value)
 * @method static Builder|Dislike whereId($value)
 * @method static Builder|Dislike whereUpdatedAt($value)
 * @method static Builder|Dislike whereUserId($value)
 * @mixin Eloquent
 */
class Dislike extends Model
{
    protected $guarded = ['id'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function dislikeable(): MorphTo
    {
        return $this->morphTo();
    }
}
