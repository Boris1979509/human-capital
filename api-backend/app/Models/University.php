<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\University
 *
 * @property int $id
 * @property int|null $city_id
 * @property string $title
 * @property string|null $alternative
 * @property bool $approved
 * @property int|null $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read City|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|University newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|University newQuery()
 * @method static Builder|University onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|University query()
 * @method static \Illuminate\Database\Eloquent\Builder|University whereAlternative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereUpdatedAt($value)
 * @method static Builder|University withTrashed()
 * @method static Builder|University withoutTrashed()
 * @mixin Eloquent
 */
class University extends Model
{
    use SoftDeletes;

    protected $table = 'universities';

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public static function initialize(string $title)
    {
        $university = University::where('title', $title)->first();

        // Create University if not exists, but approved = false
        if (!$university) {
            $university = University::create([
                'title' => $title,
                'approved' => false,
            ]);
        }
        return $university;
    }
}
