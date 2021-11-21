<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;

class UserController
{
    public function whoami()
    {
        return new UserResource(auth()->user());
    }

    public function logout()
    {
        auth()->user()->regenerateToken();
        session()->flush();
        session()->regenerate();
        return new UserResource(null);
    }
}
