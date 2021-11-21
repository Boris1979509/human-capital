<?php

namespace App\Models\UI;

use App\Filters\FilterRequest;
use App\Models\Traits\Filterable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\UI\Panel
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string|null $description
 * @property string|null $color
 * @property int|null $sort
 * @property bool $vertical
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|PanelItem[] $items
 * @property-read int|null $items_count
 * @method static Builder|Panel filter(FilterRequest $filters)
 * @method static Builder|Panel newModelQuery()
 * @method static Builder|Panel newQuery()
 * @method static \Illuminate\Database\Query\Builder|Panel onlyTrashed()
 * @method static Builder|Panel query()
 * @method static Builder|Panel whereColor($value)
 * @method static Builder|Panel whereCreatedAt($value)
 * @method static Builder|Panel whereDeletedAt($value)
 * @method static Builder|Panel whereDescription($value)
 * @method static Builder|Panel whereId($value)
 * @method static Builder|Panel whereSort($value)
 * @method static Builder|Panel whereTitle($value)
 * @method static Builder|Panel whereType($value)
 * @method static Builder|Panel whereUpdatedAt($value)
 * @method static Builder|Panel whereVertical($value)
 * @method static \Illuminate\Database\Query\Builder|Panel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Panel withoutTrashed()
 * @mixin Eloquent
 */
class Panel extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    public const PANEL_TYPE_MAIN = 'for_main';
    public const PANEL_TYPE_CHILDREN = 'for_children';
    public const PANEL_TYPE_ENTRANT = 'for_entrant';
    public const PANEL_TYPE_ADULT = 'for_adult';

    public const TYPES = [
        self::PANEL_TYPE_MAIN => 'Главная страница',
        self::PANEL_TYPE_CHILDREN => 'Детям',
        self::PANEL_TYPE_ENTRANT => 'Абитуриенты',
        self::PANEL_TYPE_ADULT => 'Взрослые',
    ];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PanelItem::class)->orderBy('sort');
    }
}
