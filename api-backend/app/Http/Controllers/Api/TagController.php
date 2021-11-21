<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BaseWithFilesResource;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\University;

class TagController extends Controller
{
    public function index()
    {
        return BaseWithFilesResource::collection(
            Tag::all()->when(request('type'), function ($q) {
                return $q->where('type', request('type'));
            })
        )->additional(['dictionaries' => Tag::TYPES]);
    }
}
