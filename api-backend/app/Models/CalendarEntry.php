<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * App\Models\CalendarEntry
 *
 * @property int $id
 * @property int $user_id
 * @property string $calendareable_type
 * @property int $calendareable_id
 * @property string|null $started_at
 * @property string|null $stopped_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $calendareable
 * @property-read User $user
 * @method static Builder|CalendarEntry newModelQuery()
 * @method static Builder|CalendarEntry newQuery()
 * @method static Builder|CalendarEntry query()
 * @method static Builder|CalendarEntry whereCalendareableId($value)
 * @method static Builder|CalendarEntry whereCalendareableType($value)
 * @method static Builder|CalendarEntry whereCreatedAt($value)
 * @method static Builder|CalendarEntry whereId($value)
 * @method static Builder|CalendarEntry whereStartedAt($value)
 * @method static Builder|CalendarEntry whereStoppedAt($value)
 * @method static Builder|CalendarEntry whereUpdatedAt($value)
 * @method static Builder|CalendarEntry whereUserId($value)
 * @mixin Eloquent
 */
class CalendarEntry extends Model
{
    protected $table = 'calendar_entries';

    protected $guarded = ['id'];

    public function calendareable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'));
    }
}
