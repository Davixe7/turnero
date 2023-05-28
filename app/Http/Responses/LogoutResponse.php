<?php
namespace App\Http\Responses;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutContract;

class LogoutResponse implements LogoutContract {

    public function toResponse($request)
    {
        // if( auth()->user()->user_id ) return to_route('employee.login');
        // if( auth()->user()->email == 'root@turnero.com' ) return to_route('root.login');
        return to_route('login');
    }
}
