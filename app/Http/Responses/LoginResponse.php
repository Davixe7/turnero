<?php
namespace App\Http\Responses;
use Laravel\Fortify\Contracts\LoginResponse as LoginContract;

class LoginResponse implements LoginContract {

    public function toResponse($request)
    {
        if (auth()->user()->user_id) return to_route('dashboard');
        if (auth()->user()->email != 'root@example.com') return to_route('orders.index');
        return to_route('root.users.index');
    }
}
