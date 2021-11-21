<?php

namespace App\Http\Controllers\Api;

use App\Filters\SelectionFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Selection\SelectionItemResource;
use App\Http\Resources\Selection\SelectionResource;
use App\Models\Selection\Selection;
use App\Models\Selection\SelectionModel;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SelectionController extends Controller
{
    public function index(SelectionFilter $filter): AnonymousResourceCollection
    {
        $selections = Selection::filter($filter)->get();

        return SelectionResource::collection($selections);
    }

    public function show(Selection $selection): SelectionResource
    {
        return new SelectionResource($selection);
    }

    public function items(Selection $selection): AnonymousResourceCollection
    {
        $items = $selection->items;

        return SelectionItemResource::collection($items);
    }
}
