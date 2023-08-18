<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UnifyNewline
{
    /**
     * Handle an incoming request.
     * 入力された著者名の改行コードを\nに統一
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('author')) {
            $request->merge(['author' => str_replace(["\r\n", "\r"], "\n", $request->input('author'))]);
        }

        return $next($request);
    }
}
