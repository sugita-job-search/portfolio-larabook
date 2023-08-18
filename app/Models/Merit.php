<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merit extends Model
{
    use HasFactory;

    /**
     * 全長所とid取得
     * 
     * @return Illuminate\Support\Collection
     */
    public static function getMerits()
    {
        return self::pluck('merit', 'id');
    }
}
