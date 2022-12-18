<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
      'over_name' => 'required|string|max:10',
      'under_name' => 'required|string|max:10',
      'over_name_kana' => 'required|string|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
      'under_name_kana' => 'required|string|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
      'mail_address' => 'required|string|max:100|unique:users',
      'sex' => 'required',
      'old_year' => 'required|after:2000',
      'old_month' => 'required',
      'old_day' => 'required',
      // 誕生日を3つのデータを１つのデータとして扱う＝
      'role' => 'required',
      'password' => 'required|string|max:30|min:8|confirmed',
    ];
  }

  public function messages()
  {
    return [
      'post_title.min' => 'タイトルは4文字以上入力してください。',
      'post_title.max' => 'タイトルは50文字以内で入力してください。',
      'post_body.min' => '内容は10文字以上入力してください。',
      'post_body.max' => '最大文字数は500文字です。',
      'over_name.required' => '苗字は必須項目です。',
      'over_name.max' => '苗字は最大10文字です。',
      'under_name.required' => '名前は必須項目です。',
      'under_name.max' => '名前は最大10文字以内です。',
      'over_name_kana.required' => '「セイ」は入力必須です',
      'over_name_kana.max' => '「セイ」は30文字以内で入力してください。',
      'over_name_kana.regex' => '「セイ」はカタカナで入力してください。',
      'under_name_kana.required' => '「メイ」は入力必須です。',
      'under_name_kana.max' => '「メイ」は30文字以内です。',
      'under_name_kana.regex' => '「メイ」はカタカナで入力してください。',
      'mail_address.required' => 'メールアドレスは必須項目です。',
      'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
      'mail_address.unique' => 'このアドレスは登録できません。他のアドレスを入力してください。',
      'sex.required' => '性別を選択してください。',
      'old_year.required' => '生年月日を入力してください。',
      'old_year.after' => '生年月日を2000年以降で選択してください。',
      'old_month.required' => '生年月日の「月」を選択してください。',
      'old_day.required' => '生年月日の「日」を選択してください。',
      'role.required' => '役職を選択してください。',
      'password.required' => 'パスワードは必須項目です。',
      'password.max' => 'パスワードは30文字以内で入力してください。',
      'password.min' => 'パスワードは8文字以上で入力してください。',
      'password.confirmed' => 'パスワードが異なります。',
    ];
  }
}
