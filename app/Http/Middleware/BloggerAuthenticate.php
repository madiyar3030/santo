<?php

namespace App\Http\Middleware;

use App\Models\Blog;
use App\Models\User;
use Closure;

class BloggerAuthenticate
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
        if (session()->get('SlbtHR0pAqkGe0CK2JvO') == 1) {
            $request->currentAdmin = session()->get('admin');
            return $next($request);
        }
        else
        {
            return redirect()->route('viewSignIn')->withErrors('Вы не авторизованы');
        }
    }
}
