<?php

namespace App\Models\Employer\ResponseState;

use App\Models\Employer\VacancyResponse;
use Spatie\ModelStates\Transition;

class TransitionToInvited extends Transition
{
    protected VacancyResponse $response;
    protected ApplicantInvite $invite;

    public function __construct(VacancyResponse $response, ApplicantInvite $invite)
    {
        $this->response = $response;
        $this->invite = $invite;
    }

    public function handle(): VacancyResponse
    {
        $this->response->status = new ApplicantInvitedState($this->response);

        $this->response->invite = $this->invite->toArray();

        $this->response->save();

        return $this->response;
    }
}
