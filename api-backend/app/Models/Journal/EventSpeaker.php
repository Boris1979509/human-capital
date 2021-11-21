<?php

namespace App\Models\Journal;

use Database\Factories\Journal\EventSpeakerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Journal\EventSpeaker
 *
 * @property int $id
 * @property int $content_id
 * @property string $name
 * @property string|null $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static EventSpeakerFactory factory(...$parameters)
 * @method static Builder|EventSpeaker newModelQuery()
 * @method static Builder|EventSpeaker newQuery()
 * @method static Builder|EventSpeaker query()
 * @method static Builder|EventSpeaker whereContentId($value)
 * @method static Builder|EventSpeaker whereCreatedAt($value)
 * @method static Builder|EventSpeaker whereId($value)
 * @method static Builder|EventSpeaker whereName($value)
 * @method static Builder|EventSpeaker wherePosition($value)
 * @method static Builder|EventSpeaker whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EventSpeaker extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * @param  Media|null  $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CONTAIN, config('app.thumb_width'), 0)
            ->optimize()
            ->performOnCollections('avatar');
    }
}
