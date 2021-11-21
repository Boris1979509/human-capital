<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEmployerRequest;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\Employer\EmployerResource;
use App\Models\Dictionary;
use App\Models\Employer\Employer;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    /** @var User $user */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    public function show(): ?EmployerResource
    {
        return $this->user->employer ? new EmployerResource($this->user->employer) : null;
    }

    public function store(UserEmployerRequest $request)
    {
        // Save employer info
        $this->user->employer()->update($request->getEmployerData());

        if ($request->get('branch_name')) {
            $this->user->employer()->update([
                'branch_id' => Dictionary::firstOrCreate([
                    'type' => Dictionary::TYPE_BRANCH,
                    'option' => $request->branch_name
                ])->id
            ]);
        }

        $this->addImagesToEmployer($this->user->employer, $request);

        return response(new EmployerResource($this->user->employer), Response::HTTP_CREATED);
    }

    public function avatar(Employer $employer): EmployerResource
    {
        $employer->addMediaFromRequest('file')->toMediaCollection('avatar');

        return new EmployerResource($employer->fresh());
    }

    public function avatarDelete(Employer $employer): EmployerResource
    {
        $employer->clearMediaCollection('avatar');

        return new EmployerResource($this->user->fresh());
    }

    public function cover(Employer $employer): EmployerResource
    {
        $employer->addMediaFromRequest('file')->toMediaCollection('cover');

        return new EmployerResource($this->user->fresh());
    }

    public function coverDelete(Employer $employer): EmployerResource
    {
        $employer->clearMediaCollection('cover');

        return new EmployerResource($this->user->fresh());
    }


    private function addImagesToEmployer(Employer $employer, Request $request): void
    {
        $this->syncMediaWithModel($employer, $request->get('images'), 'images');
    }

    public function calendar(): AnonymousResourceCollection
    {
        $events = Content::where('user_id', $this->user->id)
            ->where('type', ContentType::EVENT)
            ->get();

        return CalendarEventResource::collection($events);
    }
}
