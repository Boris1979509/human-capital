<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller;
use App\Models\Resume\Resume;
use Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserResumeController extends Controller
{
    public function generate()
    {
        $user = Auth::user();
        $resume = new Resume($user);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('resume', ['resume' => $resume]);
        return $pdf->stream('resume.pdf');
    }

    public function uploaded(): AnonymousResourceCollection
    {
        /** @var App\Models\User $user */
        $user = auth()->user();
        return App\Http\Resources\MediaResource::collection($user->getMedia('job'));
    }
}
