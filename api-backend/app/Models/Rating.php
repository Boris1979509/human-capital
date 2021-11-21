<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Rating
 *
 * @property int $id
 * @property string $rateable_type
 * @property int $rateable_id
 * @property int $user_id
 * @property int $type
 * @property int $rating
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $rateable
 * @property-read User|null $user
 * @method static Builder|Rating newModelQuery()
 * @method static Builder|Rating newQuery()
 * @method static Builder|Rating query()
 * @method static Builder|Rating whereCreatedAt($value)
 * @method static Builder|Rating whereId($value)
 * @method static Builder|Rating whereRateableId($value)
 * @method static Builder|Rating whereRateableType($value)
 * @method static Builder|Rating whereRating($value)
 * @method static Builder|Rating whereType($value)
 * @method static Builder|Rating whereUpdatedAt($value)
 * @method static Builder|Rating whereUserId($value)
 * @mixin Eloquent
 */
class Rating extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }
}
