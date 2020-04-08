<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($request['currentUser']) && isset($request['currentUser']->email_verified_at)) {
            return $next($request);
        }
        else {
            $statusCode = 401;
            return response()->json([
                'statusCode' => $statusCode,
                'message' => 'Email не подтверждён',
                'result' => null
            ], $statusCode);
        }
    }
}
