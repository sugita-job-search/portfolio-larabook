<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 全角文字を半角に変換するミドルウェア
 */
class ConvertFullwidth
{
    /**
     * 半角に変換する入力項目とmb_convert_kanaの変換オプションを組にした配列
     */
    const ITEMS = [
        'year' => 'n',
        'isbn' => 'a',
    ];

    /**
     * Handle an incoming request.
     * 全角の入力を半角に変換
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        foreach (self::ITEMS as $item => $mode) {
            if ($request->has($item)) {
                $request->merge([$item => mb_convert_kana($request->input($item), $mode)]);
            }
        }

        return $next($request);
    }
}
