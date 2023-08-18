<?php

namespace App\Common;

class Common {

    
    /**
     * isbn-10をisbn-13に変換
     * 
     * @param string $isbn
     * @return string
     */
    public static function convertIsbn10To13($isbn)
    {
        //末尾1文字以外を取り出す
        $str = substr($isbn, 0, -1);

        //頭に978をつける
        $str = '978' . $str;

        //チェックディジット計算
        $odd_sum = 0;
        $even_sum = 0;
        for ($i = 0; $i < 12; $i++) {
            if (($i % 2) == 0) {
                $odd_sum += $str[$i];
            } else {
                $even_sum += $str[$i];
            }
        }

        $sum = $odd_sum + $even_sum * 3;

        $check = 10 - $sum % 10;

        //チェックディジットをつけて返却
        if ($check == 10) {
            return $str . '0';
        }

        return $str . $check;
    }

    /**
     * isbn-13をisbn-10に変換
     * 
     * @param string $isbn
     * @return string
     */
    public static function convertIsbn13To10($isbn)
    {
        //4桁目から12桁目を取り出す
        $str = substr($isbn, 3, 9);

        //チェックディジット計算
        $sum = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $sum += $str[$i] * $j;
        }

        $check = 11 - $sum % 11;

        //チェックディジットをつけて返却
        if ($check == 10) {
            return $str . 'X';
        }

        if ($check == 11) {
            return $str . '0';
        }

        return $str . $check;
    }

    /**
     * ログイン後にリダイレクトされるページを指定
     * 指定しない場合はクエリ文字列つきの現在のurl
     *
     * @param string $url
     * @return void
     */
    public static function loginHere($url = null)
    {
        if($url == null) {
            $url = url()->full();
        }
        session(['url' => ['intended' => $url]]);
    }
}