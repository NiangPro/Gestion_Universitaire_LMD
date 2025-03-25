<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse;

class PasswordResetLinkViewResponse implements RequestPasswordResetLinkViewResponse
{
    public function toResponse($request)
    {
        return view('livewire.password-forget');
    }
} 