<?php

namespace App\Models\UI;

use App\Models\Dictionary;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UI\PanelItem
 *
 * @property int $id
 * @property int $panel_id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $sort
 * @property int|null $dictionary_type
 * @property int $dictionary_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PanelItem newModelQuery()
 * @method static Builder|PanelItem newQuery()
 * @method static Builder|PanelItem query()
 * @method static Builder|PanelItem whereCreatedAt($value)
 * @method static Builder|PanelItem whereDescription($value)
 * @method static Builder|PanelItem whereDictionaryId($value)
 * @method static Builder|PanelItem whereDictionaryType($value)
 * @method static Builder|PanelItem whereId($value)
 * @method static Builder|PanelItem wherePanelId($value)
 * @method static Builder|PanelItem whereSort($value)
 * @method static Builder|PanelItem whereTitle($value)
 * @method static Builder|PanelItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PanelItem extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    public function count(): ?int
    {
        $count = null;
        $dictionary = Dictionary::getById($this->dictionary_id);

        switch ($dictionary->type) {
            case Dictionary::TYPE_INST_TYPE:
                $count = Institution::where('inst_type_id', '=', $this->dictionary_id)->count();
                break;
            case Dictionary::TYPE_INST_PROGRAM:
                $count = InstitutionCurriculum::where('type_id', '=', $this->dictionary_id)->count();
                break;
        }

        return $count;
    }
}
