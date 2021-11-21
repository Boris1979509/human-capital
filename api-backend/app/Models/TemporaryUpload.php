<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\TemporaryUpload
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|TemporaryUpload newModelQuery()
 * @method static Builder|TemporaryUpload newQuery()
 * @method static Builder|TemporaryUpload query()
 * @method static Builder|TemporaryUpload whereCreatedAt($value)
 * @method static Builder|TemporaryUpload whereId($value)
 * @method static Builder|TemporaryUpload whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TemporaryUpload extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default')
            ->singleFile();
    }

    public static function attachMediaToModel(int $mediaId, HasMedia $model, string $collection = 'default'): void
    {
        /** @var Media $media */
        $media = Media::find($mediaId);
        $temporaryUploadId = $media->model_id;
        $media->move($model, $collection);
        self::destroy($temporaryUploadId);
    }

    /**
     * @throws Exception
     */
    public static function deleteOldImages(): void
    {
        self::where('created_at', '<', Carbon::yesterday())->get()
            ->each(fn (self $media) => $media->delete());
    }
}
