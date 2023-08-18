<?php

namespace App\Common;

/**
 * URLパラメータを集めたクラス
 */
class UrlParameter {
    /**
     * 本のidをURLパラメータとして送るときの名前
     */
    const BOOK_ID = 'book-id';

    /**検索項目をURLパラメータとして送るときの名前 */
    const ALL = 'all';
    const AUTHOR = 'author';
    const SERIES = 'series';
    
    /**トップページの選択ジャンル */
    const GENRES = 'genres';

    /**推薦文の表示順序 */
    const SORT = 'sort';

    /**ページ数 */
    const PAGE = 'page';

    /**推薦文の表示順序のvalue部分 */
    const SORT_VALUES = ['heart'];

}