<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')
    ->group(function () {
        //会員情報
        Route::controller(App\Http\Controllers\UserController::class)
            ->group(function () {
                Route::get('member', 'index')->name('member');
                Route::get('member/edit', 'edit')->name('member.edit');
                Route::post('member/edit', 'update')->name('member.update');
            });


        //本
        Route::resource('book', App\Http\Controllers\BookController::class)
            ->except(['index', 'show', 'destroy'])
            ->middleware(['fullwidth', 'newline']);
        Route::controller(App\Http\Controllers\BookController::class)
            ->group(function () {
                Route::get('book/create/isbn', 'isbn')
                    ->name('isbn');
                Route::post('book/create/isbn', 'isbnPost')
                    ->middleware('fullwidth');
                Route::post('book/create/confirm', 'confirm')
                    ->middleware(['fullwidth', 'newline'])
                    ->name('book.confirm');
                Route::get('book/{book}/create/error', 'duplicate')
                    ->name('duplicate');

                //確認画面で書影を表示
                Route::get('book/create/image', 'image')
                    ->name('image');
            });

        //推薦文
        Route::resource('recommendation', App\Http\Controllers\RecommendationController::class)
            ->except('show');
        Route::controller(\App\Http\Controllers\RecommendationController::class)
            ->group(function () {
                Route::post('recommendation/confirm', 'confirm')
                    ->name('recommendation.confirm');
                Route::get('recommendation/{recommendation}/delete', 'delete')
                    ->name('recommendation.delete');
                Route::get('recommendation/create/search', 'search')
                    ->name('recommendation.search');
            });

        //読みたい本
        Route::controller(App\Http\Controllers\WantToReadBookController::class)
            ->group(function () {
                Route::get('want-to-read', 'index')
                    ->name('want-to-read.index');
                Route::post('want-to-read', 'store')
                    ->name('want-to-read.store');
                Route::get('want-to-read/{book_id}/delete', 'delete')
                    ->name('want-to-read.delete');
                Route::post('want-to-read/delete', 'destroy')
                    ->name('want-to-read.destroy');
            });

        // ハート
        Route::controller(App\Http\Controllers\HeartController::class)
            ->group(function() {
                Route::get('heart', 'index')->name('heart.index');
                Route::post('heart', 'store');
                Route::post('heart/delete', 'destroy');
            });
    });

//ログインしなくても閲覧できるページ
//トップページ
Route::get('/', [App\Http\Controllers\TopController::class, 'index'])->name('top');
//ログインした後元のurlにリダイレクトされるページ
Route::middleware('loginHere')
    ->group(function () {
        //ジャンル選択
        Route::get('genre', [App\Http\Controllers\TopController::class, 'genre'])
            ->name('genre');
        //検索結果
        Route::get('search', [App\Http\Controllers\TopController::class, 'search'])
            ->name('search');
        //本の詳細
        Route::get('book/{book}', [App\Http\Controllers\BookController::class, 'show'])
            ->name('book.show');
        //個別ユーザーの推薦文一覧
        Route::get('recommendation/user/{user}', [App\Http\Controllers\RecommendationController::class, 'user'])
            ->name('recommendation.user');
    });