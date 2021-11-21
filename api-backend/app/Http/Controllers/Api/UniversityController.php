<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Resources\Json\JsonResource;

class UniversityController extends Controller
{
    public function index()
    {
        return JsonResource::collection(University::all());
    }
}
