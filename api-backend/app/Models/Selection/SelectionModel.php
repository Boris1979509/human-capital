<?php

namespace App\Models\Selection;

use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selection\SelectionModel
 *
 * @method static Builder|SelectionModel newModelQuery()
 * @method static Builder|SelectionModel newQuery()
 * @method static Builder|SelectionModel query()
 * @mixin Eloquent
 */
class SelectionModel extends Model
{
    public const MODEL_NEWS = 'news';
    public const MODEL_ARTICLE = 'article';
    public const MODEL_EVENT = 'event';
    public const MODEL_CURRICULUM = 'curriculum';
    public const MODEL_INSTITUTION = 'institution';

    public const CONTENT_TYPES = [
        self::MODEL_NEWS => ContentType::NEWS,
        self::MODEL_ARTICLE => ContentType::ARTICLE,
        self::MODEL_EVENT => ContentType::EVENT,
    ];

    public const OPTIONS = [
        'journal' => 'Журнал',
        self::MODEL_CURRICULUM => 'Программы обучения',
        self::MODEL_INSTITUTION => 'Обр. учреждения',
    ];

    public const MORPH_MAP = [
        'journal' => Content::class,
        self::MODEL_CURRICULUM => InstitutionCurriculum::class,
        self::MODEL_INSTITUTION => Institution::class,
    ];
}
