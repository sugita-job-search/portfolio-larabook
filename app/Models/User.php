<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'genre_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'genre_id' => 'integer',
    ];

    public static $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'genre_id' => ['nullable', 'integer', 'exists:genres,id'],
    ];

    public static $messages = [
        'email.email' => '正しいメールアドレスを入力してください。',
        'email.unique' => 'そのメールアドレスは既に登録されています。',
        'password.confirmed' => '入力されたパスワードが一致しません。',
    ];

    /**
     * 推薦文取得時にeagerロードするusersテーブルのカラム
     *
     * @var array
     */
    public static $withCards = ['id', 'name'];

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function wantToReadBooks()
    {
        return $this->hasMany(WantToReadBook::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    // want_to_read_booksテーブルを介してbooksテーブルの情報を取る時用のリレーション
    public function books()
    {
        return $this->belongsToMany(Book::class, 'want_to_read_books');
    }

    public function hearts()
    {
        return $this->hasMany(Heart::class);
    }

    public function heartsThroughRecommendations()
    {
        return $this->hasManyThrough(Heart::class, Recommendation::class);
    }

    public function recommendationsThroughHearts()
    {
        return $this->belongsToMany(Recommendation::class, 'hearts');
    }

    /**
     * 特定のユーザーの推薦文カードに必要な情報を取得するためのメソッド
     *
     * @param boolean $needsWant 読みたい本に登録ボタンを表示するか
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards($needsWant = false)
    {
        $recommendations = $this->recommendations()->cards();

        return $recommendations
            ->withBook($needsWant);
    }
}
