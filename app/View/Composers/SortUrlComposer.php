<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Common\UrlParameter;

/**
 * 表示順序を変更するためのurlをビューに結合するクラス
 */
class SortUrlComposer
{
    /**
     * ソートとページ数以外のurlパラメータ
     *
     * @var array
     */
    private $parameters;

    /**
     * ソートを表すurlパラメータ
     *
     * @var string
     */
    private $sort_parameter;

    public function __construct()
    {
        $parameters = request()->query();

        if (isset($parameters[UrlParameter::SORT]) && is_string($parameters[UrlParameter::SORT])) {
            $this->sort_parameter = $parameters[UrlParameter::SORT];
        }

        unset($parameters[UrlParameter::SORT]);
        unset($parameters[UrlParameter::PAGE]);

        $this->parameters = $parameters;
    }

    /**
     * データをビューと結合
     * 表示順序を設定するためのリンク
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // 新着順に並び替えるためのurl
        $top_url = url('/');
        if ($this->parameters != []) {
            $latest_sort_url = $top_url . '?' . http_build_query($this->parameters);
        } else {
            $latest_sort_url = $top_url;
        }

        // ハート順に並び替えるためのurl
        $this->parameters[UrlParameter::SORT] = UrlParameter::SORT_VALUES[0];
        $heart_sort_url = $top_url . '?' . http_build_query($this->parameters);

        // 表示ジャンル選択ページへ移動するためのurl
        // ハート順に並んでいるときはジャンル選択ページのurlにもその情報を持たせる
        if (isset($this->sort_parameter)) {
            $genre_change_url =  route('genre', [UrlParameter::SORT => $this->sort_parameter]);
        } else {
            $genre_change_url = route('genre');
        }

        $view->with(compact('latest_sort_url', 'heart_sort_url', 'genre_change_url'));
    }
}
