<?php

namespace App\Admin\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Selection\SelectionItem;
use App\Models\Selection\SelectionModel;
use App\Models\UI\AutocompleteWord;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiController extends AdminController
{
    public function getContentForSelectionItem(Request $request): \Illuminate\Http\JsonResponse
    {
        Relation::morphMap(SelectionModel::MORPH_MAP);
        $type = $request->get('type');
        $model = SelectionModel::MORPH_MAP[$type];

        $content = $model::orderBy('id', 'desc')
            ->when($type === 'journal', function ($q)  {
                $q->select('title as text', 'id');
            })
            ->when(($type == SelectionModel::MODEL_INSTITUTION), function ($q) {
                $q->select('full_name as text', 'id');
            })
            ->when(($type == SelectionModel::MODEL_CURRICULUM), function ($q) {
                $q->select('name as text', 'id');
            })
            ->get();

        return response()->json(['data' => $content]);
    }

    public function getSelectionItemMedia(SelectionItem $selection_item)
    {
        if ($selection_item->hasMedia('cover')) {
            return new MediaResource($selection_item->getFirstMedia('cover'));
        }
        return response()->json([
            'message' => 'Not Found!',
        ], 404);
    }

    public function updateAutocompleteWords()
    {
        $count = AutocompleteWord::prepareNewWords();
        return response()->json([
            'count' => $count,
        ], 200);
    }
}
