<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            // 後程ポストカテゴリー追加
            'post_title' => 'required|min:4|max:100',
            'post_body' => 'required|min:10|max:5000',
        ];
    }

    public function messages()
    {
        return [];
    }
}
