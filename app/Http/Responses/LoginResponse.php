<?php
namespace App\Http\Responses;
use Laravel\Fortify\Contracts\LoginResponse as LoginContract;

class LoginResponse implements LoginContract {

    public function toResponse($request)
    {
        return (auth()->user()->user_id)
                ? to_route('dashboard')
                : to_route('orders.index');
    }
}
