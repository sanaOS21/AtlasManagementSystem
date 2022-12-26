<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests\BulletinBoard\RegisterFormRequest;
use App\Models\Users\Subjects;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function registerView()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    // public function rule(RegisterFormRequest $request)
    // {
    //     // バリデーション済みデータの取得
    //     $validated = $request->validated();
    //     return view('registerRule');
    // }


    public function registerPost(RegisterFormRequest $request)
    {
        // ↓ トランザクションを開始（まだDBに反映されない）
        DB::beginTransaction();
        try {
            $old_year = $request->old_year;
            $old_month = $request->old_month;
            $old_day = $request->old_day;
            $full_data = $old_year . '-' . $old_month . '-' . $old_day;
            $birth_day = date('Y-m-d', strtotime($full_data));
            $subjects = $request->subject;
            // 新規登録を実行
            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            // findOrFail()...は一致するidが見つからない場合はエラー
            $user = User::findOrFail($user_get->id);

            $user->subjects()->attach($subjects);
            // ↓コメット＝（トランザクションを）確定！　DBに反映される
            DB::commit();
            return view('auth.login.login');
        } catch (\Exception $e) {
            // ↓ロールバック＝（トランザクションを）破棄！（トランザクション前に戻す）
            DB::rollback();
            return redirect()->route('loginView');
        }
    }
}
