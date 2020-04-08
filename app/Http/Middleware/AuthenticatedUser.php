<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class AuthenticatedUser
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
        if ($request->header('Authorization') == null){
            $statusCode = 404;
            return response()->json([
                'statusCode' => $statusCode,
                'message' => 'Токен не найден',
                'result' => null
            ], $statusCode);
        }
        $user = User::where('access_token', $request->header('Authorization'))->whereType('user', User::TYPE_USER)->first();
        if ($user) {
            $request['currentUser'] = $user;
            User::$currentUser = $user;
            return $next($request);
        }
        else {
            $statusCode = 401;
            return response()->json([
                'statusCode' => $statusCode,
                'message' => 'Неправильный токен или сессия истекла',
                'result' => null
            ], $statusCode);
        }
    }
}
