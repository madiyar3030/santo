<?php

namespace App\Http\Middleware;

use Closure;

class TypeIsAdmin
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
        if (session()->get('admin')->type == \App\Models\Admin::TYPE_ADMIN) {
            $request['currentAdmin'] = session()->get('admin');
            return $next($request);
        }
        else
        {
            return redirect()->route('blogs.index')->withErrors('У вас недостаточно прав');
        }
    }
}
