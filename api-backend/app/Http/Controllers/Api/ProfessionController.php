<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profession;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionController extends Controller
{
    public function index()
    {
        return JsonResource::collection(Profession::all());
    }
}
