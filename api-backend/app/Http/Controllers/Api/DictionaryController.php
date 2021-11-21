<?php

namespace App\Http\Controllers\Api;

use App\Filters\DictionaryFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\DictionaryResource;
use App\Models\Dictionary;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DictionaryController extends Controller
{
    public function index(DictionaryFilter $filter): AnonymousResourceCollection
    {
        return DictionaryResource::collection(
            Dictionary::getOptions($filter)
        );
    }
}
