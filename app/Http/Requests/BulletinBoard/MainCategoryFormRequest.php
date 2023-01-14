<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryFormRequest extends FormRequest
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
            'main_category_name' => 'required|max:100|unique:main_category,main_categories'
            //
        ];
    }

    public function messages()
    {
        return [
            'main_category_name.required' => 'メインカテゴリーを記入してください',
            'main_category_name.unique' => 'すでに登録されています',
        ];
    }
}
