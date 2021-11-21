<?php

namespace App\Models\Employer\ResponseState;

use App\Models\Employer\VacancyResponse;
use Spatie\ModelStates\Transition;

class TransitionToRejected extends Transition
{
    protected VacancyResponse $response;
    private ApplicantRejection $rejection;

    public function __construct(VacancyResponse $response, ApplicantRejection $rejection)
    {
        $this->response = $response;
        $this->rejection = $rejection;
    }

    public function handle(): VacancyResponse
    {
        $this->response->status = new ApplicantRejectedState($this->response);

        $this->response->rejection = $this->rejection->toArray();

        $this->response->save();

        return $this->response;
    }
}
