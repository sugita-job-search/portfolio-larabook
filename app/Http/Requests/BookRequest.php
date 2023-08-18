<?php

namespace App\Http\Requests;

use App\Http\Controllers\BookController;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        $year_max = now()->year + 2;
        return [
            'title' => ['required', 'string', 'max:240'],
            'author' => ['required', 'string', 'max:120'],
            'publisher' => ['required', 'string', 'max:120'],
            'year' => ['required', 'numeric', 'integer', 'between:1950,'. $year_max],
            'month' => ['required', 'numeric', 'integer', 'between:1,12'],
            'series_title' => ['nullable', 'string', 'max:100'],
            'genre_id' => ['required', 'integer', 'exists:genres,id'],
            'image' => ['nullable', 'file', 'mimes:jpg,png'],
        ];
    }

    /**
     * バリデーションのエラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'author.max' => '文字数が多すぎます。多数の著者がいる場合は主要な著者のみを入力してください。',
            'year.numeric' => '出版年月を正しく入力してください。',
            'year.integer' => '出版年月を正しく入力してください。',
            'year.between' => '出版年月を正しく入力してください。',
            'genre_id.required' => 'ジャンルを選択してください。',
            'genre_id.integer' => 'ジャンルを正しく選択してください。',
            'genre_id.exists' => 'ジャンルを正しく選択してください。',
        ];
    }

    /**
     * セッションに保存されているisbnを保持
     *
     * @return void
     */
    public function withValidator()
    {
        BookController::keepIsbn();
    }
}
