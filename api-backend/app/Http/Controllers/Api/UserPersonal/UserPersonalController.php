<?php

namespace App\Http\Controllers\Api\UserPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserPersonalRequest;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\UserPersonalResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserPersonalController extends Controller
{
    /** @var User $user */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    public function index()
    {
        return (new UserPersonalResource($this->user))
            ->response()
            ->setStatusCode(200);
    }

    public function store(UserPersonalRequest $request)
    {
        // Save phone number
        if (request('phone')) {
            $this->user->phone = request('phone');
            $this->user->save();
        }

        // Save personal info
        $this->user->personal()->update($request->getPersonalData());

        return (new UserPersonalResource($this->user))
            ->response()
            ->setStatusCode(201);
    }

    public function avatar()
    {
        $this->user->addMediaFromRequest('file')->toMediaCollection('avatar');

        return (new UserPersonalResource($this->user->fresh()))
            ->response()
            ->setStatusCode(201);
    }

    public function avatarDelete()
    {
        $this->user->clearMediaCollection('avatar');

        return (new UserPersonalResource($this->user->fresh()))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function calendar(): AnonymousResourceCollection
    {
        $events = $this->user->calendarEntries()
            ->with('calendareable')
            ->get();

        return CalendarEventResource::collection($events);
    }
}
