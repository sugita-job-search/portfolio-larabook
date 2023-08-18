<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation',
        'book_id',
    ];

    /**
     * 基本のバリデーションルール
     *
     * @var array
     */
    private static $rules = [
        'book_id' => ['required', 'integer'],
        'recommendation' => ['nullable', 'required_without:merits', 'string', 'max:500'],
        'merits' => ['required_without:recommendation', 'array'],
    ];

    /**
     * 新規登録時のバリデーションルールを返却する
     * データベースにidが存在しているかもチェックする
     *
     * @return array
     */
    public static function storeRules()
    {
        $rules = self::$rules;
        $rules['book_id'][] = 'exists:books,id';
        $rules['merits'][] = 'exists:merits,id';

        return $rules;
    }

    /**
     * 確認画面に遷移する時のバリデーションルール
     *
     * @return array
     */
    public static function confirmRules()
    {
        $rules = self::$rules;
        $rules['merits.*'] = 'integer';

        return $rules;
    }

    /**
     * 更新するときのバリデーションルール
     * 本idは更新しない
     *
     * @return array
     */
    public static function updateRules()
    {
        $rules = self::$rules;
        unset($rules['book_id']);
        $rules['merits'][] = 'exists:merits,id';
            
        return $rules;
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recommendationMerits()
    {
        return $this->hasMany(RecommendationMerit::class);
    }

    public function merits()
    {
        return $this->belongsToMany(Merit::class, 'recommendation_merits');
    }

    public function hearts()
    {
        return $this->hasMany(Heart::class);
    }

    /**
     * 推薦文が削除されたときrecommendationMeritsテーブルとHeartsテーブルからも関連するレコード削除
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($recommendation) {
            $recommendation->merits()->detach();
            $recommendation->hearts()->delete();
        });
    }

    /**
     * 推薦文と紐づいた長所idを取得
     * 
     * @return array
     */
    public function getMerits()
    {
        return $this->recommendationMerits()
            ->pluck('merit_id')
            ->all();
    }

    /**
     * 推薦文と長所とハートを取得するためのメソッド
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $needsLoginHeart ログインユーザーがハート登録しているかも取得するか
     * @return void
     */
    public function scopeCards($query, $needsLoginHeart = null)
    {
        if(!isset($needsLoginHeart)) {
            $needsLoginHeart = Auth::check();
        }
        
        $query->select('recommendations.id', 'recommendation', 'book_id', 'recommendations.user_id')
            ->with('merits:id,merit')
            ->withCount('hearts')
            ->when($needsLoginHeart, function ($query) {
                $query->withExists(['hearts' => function (Builder $query) {
                    $query->where('user_id', Auth::id());
                }]);
            });
    }

    /**
     * 推薦文投稿者をeagerロードするためのメソッド
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeWithUser($query)
    {
        $query->with('user:'. implode(',', User::$withCards));
    }

    /**
     * 推薦文が書かれた本をeagerロードするためのメソッド
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $needsLoginWant 読みたい本に登録しているかを取得する必要があるか
     * @return void
     */
    public function scopeWithBook($query, $needsLoginWant = null)
    {
        if(!isset($needsLoginWant)) {
            $needsLoginWant = Auth::check();
        }

        $with[] = 'book:'. implode(',', Book::$withCards);
        if($needsLoginWant) {
            $with[] = 'book.loginWant';
        }
        
        $query->with($with);
    }
}
