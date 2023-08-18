<?php

namespace App\Common;

/**
 * セッションのキーを集めたクラス
 */
class Session {
    /**入力されたisbnをセッションに保存するときの名前 */
    const ISBN_INPUT = 'isbn_input';

    /**入力されたisbnをハイフンなしのisbn-13に変換した後セッションに保存するときの名前 */
    const ISBN13 = 'isbn13';

    /**isbn以外の入力値をセッションに保存するときの名前 */
    const BOOK_CREATE_INPUT = 'book_create_input';

    /**アップロードファイルをセッションに保存するときの名前 */
    const IMAGE = 'image';

    /**アップロードファイルのmimeタイプをセッションに保存するときの名前 */
    const MIME = 'mime';

    /**アップロードファイルの新しい名前をセッションに保存するときの名前 */
    const IMAGE_NAME = 'image_name';
}