<?php


namespace App\Models\Traits;

use App\Models\CalendarEntry;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;

trait CalendarEntrybility
{
    public function calendarEntry()
    {
        return $this->morphOne(CalendarEntry::class, 'calendareable');
    }

    public function isCalendarEntry($userId = null)
    {
        return $this->calendarEntry()->where('user_id', ($userId) ? $userId : auth()->id())->exists();
    }
}
