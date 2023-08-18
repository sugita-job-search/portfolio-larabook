<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Models\Genre;

class UserController extends Controller
{
    /**
     * 会員情報ページを表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('member.index', compact('user'));
    }

    /**
     * 会員情報編集ページを表示
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $genres = Genre::getGenres();
        return view('member.edit', compact('genres'));
    }

    /**
     * 会員情報変更
     *
     * @param App\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        $user = Auth::user();
        $user->fill($request->validated());
        
        //パスワードが入力されているときハッシュ化、されていないときunset
        if(isset($user->password)) {
            $user->password = Hash::make($user->password);
        } else {
            unset($user->password);
        }

        $user->save();

        return redirect()->route('member');
    }
}
