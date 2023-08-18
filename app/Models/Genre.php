<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    /**
     * 指定idのジャンル名取得
     *
     * @param int|string $id
     * @return string|null
     */
    public static function getGenreById($id)
    {
        return self::where('id', $id)->value('genre');
    }

    /**
     * 全ジャンル名とid取得
     *
     * @return Illuminate\Support\Collection
     */
    public static function getGenres()
    {
        return self::pluck('genre', 'id');
    }
}
