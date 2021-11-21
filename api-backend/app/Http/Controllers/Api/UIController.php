<?php

namespace App\Http\Controllers\Api;

use App\Filters\AutocompleteFilter;
use App\Filters\PanelFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UI\PanelResource;
use App\Models\UI\AutocompleteWord;
use App\Models\UI\Panel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UIController extends Controller
{
    public function panels(PanelFilter $filter): AnonymousResourceCollection
    {
        $panels = Panel::filter($filter)->get();
        return PanelResource::collection($panels);
    }

    public function autocomplete(AutocompleteFilter $filter): AnonymousResourceCollection
    {
        $panels = AutocompleteWord::filter($filter)->limit(100)->get();
        return JsonResource::collection($panels);
    }
}
