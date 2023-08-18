<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Genre;
use App\Models\WantToReadBook;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'isbn',
        'image',
    ];

    /**
     * no imageの画像の相対パス
     */
    const NO_IMAGE = 'image/no_image_tate.jpg';

    /**
     * 推薦文取得のときにeagerロードするbookテーブルのカラム
     *
     * @var array
     */
    public static $withCards = ['id', 'title', 'author', 'image'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function wantToReadBooks()
    {
        return $this->hasMany(WantToReadBook::class);
    }

    /**
     * 読みたい本とのリレーションの際にログインユーザーの読みたい本だけ取得するためのメソッド
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loginWant()
    {
        return $this->wantToReadBooks()
                ->select('book_id')
                ->where('user_id', Auth::id());
    }

    /**
     * 著者名を取得するとき配列に変換
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => explode("\n", $value),
        );
    }

    /**
     * 書影名を取得するときパスをつける、書影がないときはno imageの画像を使う
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if($value == null) {
                    return self::NO_IMAGE;
                }
                return 'storage/'. $value;
            }
        );
    }

    /**
     * 指定isbnの本が登録されていればそのidを返却
     *
     * @param string $isbn
     * @return string|false
     */
    public static function getBookIdByIsbn($isbn)
    {
        $id = self::where('isbn', $isbn)
                ->value('id');
        
        if($id == null) {
            return false;
        }
        return $id;
    }
}
