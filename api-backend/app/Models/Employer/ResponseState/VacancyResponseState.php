<?php

namespace App\Models\Employer\ResponseState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class VacancyResponseState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()->default(ResponseSendState::class);
    }
}
