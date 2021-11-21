<?php

namespace App\Http\Controllers\Api\Journal;

use App\Filters\ContentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Journal\SortContentRequest;
use App\Http\Resources\Journal\ContentResource;
use App\Models\Institution\Institution;
use App\Models\Journal\Content;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContentsManagementController extends Controller
{
    /**
     * @param  ContentFilter  $filter
     * @param  SortContentRequest  $sorter
     * @param  Institution|null  $institution
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(
        ContentFilter $filter,
        SortContentRequest $sorter,
        ?Institution $institution
    ): AnonymousResourceCollection {
        $this->authorize('viewAny', [Content::class, $institution]);

        $contents = Content::query()
            ->when($institution->id, function (Builder $query) use ($institution) {
                $query->where('institution_id', $institution->id);
            })
            ->when(!$institution->id, function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->withCount(['registrations', 'processedRegistrations'])
            ->filter($filter)
            ->sort($sorter)
            ->simplePaginate($this->getPerPage());
        return ContentResource::collection($contents);
    }
}
