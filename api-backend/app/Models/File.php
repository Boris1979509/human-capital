<?php

namespace App\Models;

use App\Models\Traits\ImageHandle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config as Config;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\File
 *
 * @property int $id
 * @property int $fileable_id
 * @property string $fileable_type
 * @property string|null $type
 * @property string $file
 * @property int $sort
 * @property string|null $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $kind
 * @property-read Model|\Eloquent $fileable
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    use ImageHandle;

    protected $table = 'files';

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'fileable_id',
        'fileable_type',
        'sort',
        'description',
        'kind',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     *  Типы файлов
     */
    public const KIND_AVATAR = 1;
    public const KIND_EDU = 2;
    public const KIND_JOB = 3;
    public const KIND_CONTENT_COVER = 4;
    public const KIND_SPEAKER_AVATAR = 5;

    public const INST_EMPLOYEE = 10;

    public static function downloadFile(UploadedFile $file, $model, $kind): void
    {
        // Уникальное имя для файла
        $fileName = File::generateName($file->getClientOriginalExtension());

        // Переносим файл куда надо
        File::moveFile($file, $fileName);

        // Добавляем файл в объект
        $model->files()->create([
            'type' => $file->getClientMimeType(),
            'title' => $file->getClientOriginalName(),
            'kind' => $kind,
            'file' => $fileName
        ]);
    }

    public function fileable()
    {
        return $this->morphTo();
    }

    public function fileLink()
    {
        return Storage::url($this->file);
    }

    public function thumbnail()
    {
        return Storage::url(str_replace('images/', 'images/small.', $this->file));
    }

    public static function staticThumbnail($file)
    {
        return Storage::url(str_replace('images/', 'images/small.', $file));
    }

    /**
     * @param  string  $extension
     * @return string
     */
    public static function generateName(string $extension): string
    {
        return Config::get('app.path.files').DIRECTORY_SEPARATOR.uniqid('', true).'.'.strtolower($extension);
    }

    /**
     * @param  UploadedFile  $file
     * @param  string  $fileName
     * @param  string|null  $pathPrefix
     * @return bool
     */
    public static function moveFile(UploadedFile $file, string $fileName, string $pathPrefix = null)
    {
        if (!$pathPrefix) {
            $pathPrefix = Config::get('app.path.files');
        }
        // Полный путь
        $fullPath = Config::get('app.path.storage').DIRECTORY_SEPARATOR.$pathPrefix;
        // Перемещаем файл
        $file->move($fullPath, $fileName);

        return true;
    }

    public static function downloadFiles($model, $kind, $key = 'files')
    {
        $files = request()->file($key);
        if (isset($files)) {
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                self::downloadFile($file, $model, $kind);
            }
        }
    }
}
