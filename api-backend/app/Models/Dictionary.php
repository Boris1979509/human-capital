<?php

namespace App\Models;

use App\Filters\DictionaryFilter;
use App\Http\Requests\FilterRequest;
use App\Models\Traits\Filterable;
use Database\Factories\DictionaryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Dictionary
 *
 * @property int $id
 * @property int|null $type
 * @property string $option
 * @property string|null $alternative
 * @property bool $approved
 * @property int|null $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $slug
 * @method static DictionaryFactory factory(...$parameters)
 * @method static Builder|Dictionary filter(\App\Filters\FilterRequest $filters)
 * @method static Builder|Dictionary newModelQuery()
 * @method static Builder|Dictionary newQuery()
 * @method static \Illuminate\Database\Query\Builder|Dictionary onlyTrashed()
 * @method static Builder|Dictionary query()
 * @method static Builder|Dictionary whereAlternative($value)
 * @method static Builder|Dictionary whereApproved($value)
 * @method static Builder|Dictionary whereCreatedAt($value)
 * @method static Builder|Dictionary whereDeletedAt($value)
 * @method static Builder|Dictionary whereId($value)
 * @method static Builder|Dictionary whereOption($value)
 * @method static Builder|Dictionary whereSlug($value)
 * @method static Builder|Dictionary whereSort($value)
 * @method static Builder|Dictionary whereType($value)
 * @method static Builder|Dictionary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Dictionary withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Dictionary withoutTrashed()
 * @mixin Eloquent
 */
class Dictionary extends Model
{
    use SoftDeletes, Filterable, HasFactory;

    protected $table = 'dictionaries';

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public const TYPE_EDU_DEGREE = 1;
    public const TYPE_EDU_STATUS = 2;
    public const TYPE_EDU_QUALITY = 3;
    public const TYPE_COUNTRY = 6;

    public const TYPE_INST_TYPE = 10;
    public const TYPE_INST_DIPLOMA = 11;
    public const TYPE_INST_PROGRAM = 12;
    public const TYPE_INST_ITEM = 13;
    public const TYPE_INST_AUDITORY = 14;
    public const TYPE_INST_FORM = 15;
    public const TYPE_INST_AUDITORY_JOURNAL = 16;
    public const TYPE_INST_AGE = 17;
    public const TYPE_INST_FREQUENCY = 18;
    public const TYPE_INST_DIRECTION = 19;

    public const SETTING_PERSONAL = 30;
    public const SETTING_INST = 31;

    public const TYPE_BRANCH = 32;

    public const TEST = 99;

    public const TYPES = [
        self::TYPE_EDU_DEGREE => 'Уровень образования',
        self::TYPE_EDU_STATUS => 'Статус обучения',
        self::TYPE_EDU_QUALITY => 'Квалификация',
        self::TYPE_COUNTRY => 'Страны',

        self::TYPE_INST_TYPE => 'Тип учебного заведения',
        self::TYPE_INST_DIPLOMA => 'Категория диплома',
        self::TYPE_INST_PROGRAM => 'Тип образовательной программы',
        self::TYPE_INST_ITEM => 'Предмет',
        self::TYPE_INST_AUDITORY => 'Целевая аудитория',
        self::TYPE_INST_FORM => 'Форма обучения',
        self::TYPE_INST_AUDITORY_JOURNAL => 'Целевая аудитория(журнал)',
        self::TYPE_INST_AGE => 'Возраст участников',
        self::TYPE_INST_FREQUENCY => 'Частота оповещений',
        self::TYPE_INST_DIRECTION => 'Основные направления деятельности',

        self::SETTING_PERSONAL => 'Настройки физ. лиц',
        self::SETTING_INST => 'Настройки ОО',

        self::TYPE_BRANCH => 'Отрасль компании',

        self::TEST => 'Тест',
    ];

    public const NAMING = [
        self::TYPE_EDU_DEGREE => 'edu_degree',
        self::TYPE_EDU_STATUS => 'edu_status',
        self::TYPE_EDU_QUALITY => 'edu_quality',
        self::TYPE_COUNTRY => 'country',

        self::TYPE_INST_TYPE => 'inst_type',
        self::TYPE_INST_DIPLOMA => 'inst_diploma',
        self::TYPE_INST_PROGRAM => 'inst_program',
        self::TYPE_INST_ITEM => 'inst_item',
        self::TYPE_INST_AUDITORY => 'inst_auditory',
        self::TYPE_INST_FORM => 'inst_form',
        self::TYPE_INST_AUDITORY_JOURNAL => 'inst_auditory_journal',
        self::TYPE_INST_AGE => 'inst_age',
        self::TYPE_INST_FREQUENCY => 'inst_frequency',
        self::TYPE_INST_DIRECTION => 'inst_direction',

        self::SETTING_PERSONAL => 'setting_personal',
        self::SETTING_INST => 'setting_inst',

        self::TYPE_BRANCH => 'type_branch',

        self::TEST => 'test',
    ];

    public const TYPES_EDU = [
        self::TYPE_EDU_DEGREE => 'Уровень образования',
        self::TYPE_EDU_STATUS => 'Статус обучения',
        self::TYPE_EDU_QUALITY => 'Квалификация',
    ];

    public static function getOptions(DictionaryFilter $filter): array
    {
        $array = array();
        foreach (self::filter($filter)->get() as $row) {
            if (empty($array[self::NAMING[$row->type]])) {
                $array[self::NAMING[$row->type]] = array();
            }
            $array[self::NAMING[$row->type]][] = $row;
        }
        return $array;
    }

    public static function getById(int $id = null)
    {
        if (!$id) {
            return null;
        }

        /** @var Collection $dictionaries */
        $dictionaries = Cache::remember('dictionary', config('app.cache_ttl'), fn () => Dictionary::select(['id', 'option', 'type'])->get());

        return $dictionaries->firstWhere('id', $id);
    }
}
