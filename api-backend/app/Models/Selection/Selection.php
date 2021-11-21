<?php

namespace App\Models\Selection;

use App\Filters\FilterRequest;
use App\Http\Requests\SortRequest;
use App\Models\Traits\CanBePublished;
use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use App\Models\User;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Database\Factories\Selection\SelectionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Mtownsend\ReadTime\ReadTime;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Selection\Selection
 *
 * @property int $id
 * @property int|null $author_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $annotation
 * @property string|null $description
 * @property int|null $reading_time
 * @property string|null $published_at
 * @property bool $is_published
 * @property bool $is_advertisement
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User|null $author
 * @property-read Collection|Favorite[] $favorites
 * @property-read int $favorites_count
 * @property-read Collection|SelectionItem[] $items
 * @property-read int|null $items_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static SelectionFactory factory(...$parameters)
 * @method static Builder|Selection filter(FilterRequest $filters)
 * @method static Builder|Selection newModelQuery()
 * @method static Builder|Selection newQuery()
 * @method static \Illuminate\Database\Query\Builder|Selection onlyTrashed()
 * @method static Builder|Selection published()
 * @method static Builder|Selection query()
 * @method static Builder|Selection sort(SortRequest $sorter)
 * @method static Builder|Selection whereAnnotation($value)
 * @method static Builder|Selection whereAuthorId($value)
 * @method static Builder|Selection whereCreatedAt($value)
 * @method static Builder|Selection whereDeletedAt($value)
 * @method static Builder|Selection whereDescription($value)
 * @method static Builder|Selection whereId($value)
 * @method static Builder|Selection whereIsAdvertisement($value)
 * @method static Builder|Selection whereIsPublished($value)
 * @method static Builder|Selection wherePublishedAt($value)
 * @method static Builder|Selection whereReadingTime($value)
 * @method static Builder|Selection whereSlug($value)
 * @method static Builder|Selection whereTitle($value)
 * @method static Builder|Selection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Selection withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Selection withoutTrashed()
 * @mixin Eloquent
 */
class Selection extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CanBePublished;
    use Filterable;
    use InteractsWithMedia;
    use Sortable;
    use Favoriteable;

    protected $table = 'selections';

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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(SelectionItem::class)->orderBy('id');
    }

    public function cover()
    {
        return $this->getFirstMedia('cover')->url;
    }

    public function getReadingTime(): string
    {
        $texts = [$this->description, $this->annotation];

        $this->items->each(function (SelectionItem $item) use ($texts) {
            $texts[] = $item->selectionable->text;
        });

        return (new ReadTime($texts))->toArray()['minutes'].' мин.';
    }
}
