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
            'over_name' => 'テスト',
            'under_name' => '太郎',
            'over_name_kana' => 'テスト',
            'under_name_kana' => 'タロウ',
            'mail_address' => 'test@mail',
            // バリューを入力！
            'sex' => '1',
            // 「/」、「年月日」入力×
            'role' => '1',
            'birth_day' => '2000-01-01',
            'password' => bcrypt('password'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'over_name' => '佐藤',
            'under_name' => '沙南',
            'over_name_kana' => 'サトウ',
            'under_name_kana' => 'サナ',
            'mail_address' => 'satou@mail',
            // バリューを入力！
            'sex' => '2',
            // 「/」、「年月日」入力×
            'role' => '4',
            'birth_day' => '2000-10-01',
            'password' => bcrypt('satousatou'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        // 「php artisan db:seed」 をターミナルに入力したら反映された！
    }
}
