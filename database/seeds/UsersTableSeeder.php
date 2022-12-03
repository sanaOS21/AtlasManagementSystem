<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 以下ユーザ情報追加
        DB::table('users')->insert([
            'over_name'=>'テスト',
            'under_name'=>'太郎',
            'over_name_kana'=>'テスト',
            'under_name_kana'=>'タロウ',
            'mail_address'=>'test@mail',
            'sex'=>'男性',
            'birth_day'=>'2000年01月01日',
            'role'=>'教師(国語)'
            'password'=>Hash::make('testtest'),
        ]);
    }
}
