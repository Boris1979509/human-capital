<?php

namespace App\Models\UI;

use App\Filters\FilterRequest;
use App\Models\Traits\Filterable;
use App\Models\UserPersonal\UserPersonal;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\UI\AutocompleteWord
 *
 * @property int $id
 * @property int $type
 * @property string $word
 * @property bool $approved
 * @method static Builder|AutocompleteWord filter(FilterRequest $filters)
 * @method static Builder|AutocompleteWord newModelQuery()
 * @method static Builder|AutocompleteWord newQuery()
 * @method static Builder|AutocompleteWord query()
 * @method static Builder|AutocompleteWord whereApproved($value)
 * @method static Builder|AutocompleteWord whereId($value)
 * @method static Builder|AutocompleteWord whereType($value)
 * @method static Builder|AutocompleteWord whereWord($value)
 * @mixin Eloquent
 */
class AutocompleteWord extends Model
{
    use Filterable;

    protected $table = 'autocomplete_words';
    public $timestamps = false;

    protected $guarded = ['id'];

    public const TYPE_SKILL = 1;
    public const TYPE_QUALITY = 2;

    public const OPTIONS = [
        self::TYPE_SKILL            => 'Навыки',
        self::TYPE_QUALITY         => 'Качества',
    ];

    public static function prepareNewWords()
    {
        $personals = UserPersonal::select(['skills', 'qualities'])->get();
        $skills = array();
        $qualities = array();
        foreach ($personals as $row) {
            if ($row->skills) {
                foreach ($row->skills as $skill) {
                    $skill = Str::lower($skill);
                    if (!in_array($skill, $skills)) {
                        $skills[] = $skill;
                        AutocompleteWord::firstOrCreate([
                            'type' => AutocompleteWord::TYPE_SKILL,
                            'word' => $skill
                        ]);
                    }
                }
            }
            if ($row->qualities) {
                foreach ($row->qualities as $quality) {
                    $quality = Str::lower($quality);
                    if (!in_array($quality, $qualities)) {
                        $qualities[] = $quality;
                        AutocompleteWord::firstOrCreate([
                            'type' => AutocompleteWord::TYPE_QUALITY,
                            'word' => $quality
                        ]);
                    }
                }
            }
        }
        return count($skills) + count($qualities);
    }
}
