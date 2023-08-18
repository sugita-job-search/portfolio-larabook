<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = User::$rules;

        //メールアドレスが元のままならok
        $key = array_search('unique:users', $rules['email']);
        unset($rules['email'][$key]);
        $rules['email'][] = Rule::unique('users')->ignore(Auth::id());

        //パスワード未入力のとき更新しない
        $key = array_search('required', $rules['password']);
        unset($rules['password'][$key]);
        $rules['password'][] = 'nullable';
        return $rules;
    }

    public function messages()
    {
        return User::$messages;
    }
}
