<?php

use Illuminate\Database\Seeder;
use App\Models\Users\Subjects;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 国語、数学、英語を追加
        DB::table('subjects')->insert([
            // 'Y-m-d H:i:s'...時間表示の定義に合わせて時間のデータを作成
            ['subject' => '国語', 'created_at' => date('Y-m-d H:i:s')],
            ['subject' => '数学', 'created_at' => date('Y-m-d H:i:s')],
            ['subject' => '英語', 'created_at' => date('Y-m-d H:i:s')]
        ]);
    }
}
