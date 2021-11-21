<?php

namespace App\Models\Selection;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Selection\SelectionItem
 *
 * @property int $id
 * @property int $selection_id
 * @property int|null $content_type
 * @property int $selectionable_id
 * @property string $selectionable_type
 * @property string|null $title
 * @property string|null $description
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Selection $selection
 * @property-read Model|Eloquent $selectionable
 * @method static Builder|SelectionItem newModelQuery()
 * @method static Builder|SelectionItem newQuery()
 * @method static Builder|SelectionItem query()
 * @method static Builder|SelectionItem whereContentType($value)
 * @method static Builder|SelectionItem whereCreatedAt($value)
 * @method static Builder|SelectionItem whereDescription($value)
 * @method static Builder|SelectionItem whereId($value)
 * @method static Builder|SelectionItem whereSelectionId($value)
 * @method static Builder|SelectionItem whereSelectionableId($value)
 * @method static Builder|SelectionItem whereSelectionableType($value)
 * @method static Builder|SelectionItem whereSort($value)
 * @method static Builder|SelectionItem whereTitle($value)
 * @method static Builder|SelectionItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SelectionItem extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'selection_items';

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
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
            ->performOnCollections('cover');
    }

    public function selectionable()
    {
        return $this->morphTo();
    }

    public function selection(): BelongsTo
    {
        return $this->belongsTo(Selection::class);
    }
}
