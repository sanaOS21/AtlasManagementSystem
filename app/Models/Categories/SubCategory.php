<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory()
    {
        // リレーションの定義
        // belongsTo...1対多
        return $this->belongsTo('App\Models\Categories\MainCategory');
    }

    public function posts()
    {
        // リレーションの定義
        //belongsToMany...多対多
        // 第一引数：リレーション先のモデル名
        // 第二引数：リレーション先のテーブル名
        // 第三引数：自モデルの主キー
        // 第四引数：相手モデルの主キー
        return $this->belongsToMany('App\Models\Posts\Post', 'post_sub_categories', 'sub_category_id', 'post_id');
    }
}
