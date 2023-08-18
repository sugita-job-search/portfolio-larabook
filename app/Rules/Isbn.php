<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Isbn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return self::isIsbn($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ISBNを正しく入力してください。';
    }

    /**
     * isbnの形式に合致していればtrueを返す
     *
     * @param string $value
     * @return boolean
     */
    public static function isIsbn($value)
    {
        //ハイフンを削除
        $str = str_replace(['-', 'ー'], '', $value);

        //末尾1文字とそれ以外に分離
        $digits = mb_substr($str, 0, -1);
        $end = mb_substr($str, -1);

        //末尾以外に数字以外の文字があったらfalse
        if(!ctype_digit($digits)) {
            return false;
        }

        //末尾含めて10桁のときチェックディジットが合っていればtrue
        if(strlen($digits) == 9) {
            $sum = 0;
            for($i = 0, $j = 10; $i < 9; $i ++, $j --) {
                $sum += $digits[$i] * $j;
            }

            $check = 11 - $sum % 11;
            
            if($check == $end) {
                return true;
            }
            
            if($check == 11 && $end == 0) {
                return true;
            }
            
            if($check == 10) {
                if($end == 'X' || $end = 'x') {
                    return true;
                }
            }
        }

        //末尾含めて13桁のときチェックディジットが合っていればtrue
        if(strlen($digits) == 12) {
            $odd_sum = 0;
            $even_sum = 0;
            for($i = 0; $i < 12; $i ++) {
                if(($i % 2) == 0) {
                    $odd_sum += $digits[$i];
                } else {
                    $even_sum += $digits[$i];
                }
            }

            $sum = $odd_sum + $even_sum * 3;

            $check = 10 - $sum % 10;

            if($check == 10 && $end == 0) {
                return true;
            }

            if($check == $end) {
                return true;
            }
        }

        //10桁でも13桁でもないときとチェックディジットが合わなかったときはfalse
        return false;
    }
}
