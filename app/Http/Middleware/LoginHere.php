<?php

namespace App\Http\Middleware;

use App\Common\Common;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginHere
{
    /**
     * Handle an incoming request.
     * ログインしていない場合はログインした後のリダイレクト先を現在のページにする
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()) {
            Common::loginHere();
        }
        return $next($request);
    }
}
