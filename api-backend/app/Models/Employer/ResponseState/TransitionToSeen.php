<?php

namespace App\Models\Employer\ResponseState;

use App\Models\Employer\VacancyResponse;
use Spatie\ModelStates\Transition;

class TransitionToSeen extends Transition
{
    protected VacancyResponse $response;

    public function __construct(VacancyResponse $response)
    {
        $this->response = $response;
    }

    public function handle(): VacancyResponse
    {
        $this->response->status = new ResponseSeenState($this->response);

        $this->response->save();

        return $this->response;
    }
}
