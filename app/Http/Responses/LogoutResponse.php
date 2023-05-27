<?php
namespace App\Http\Responses;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutContract;

class LogoutResponse implements LogoutContract {

    public function toResponse($request)
    {
        return to_route('login');
        // return $request->expectsJson()
        // ? response()->json('success', 204)
        // : to_route('login');
    }
}
