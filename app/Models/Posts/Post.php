<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        // hasMany(1対多)
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories()
    {
        // リレーションの定義
        // 第一引数：リレーション先のモデル名
        // 第二引数：リレーション先のテーブル名
        // 第三引数：自モデルの主キー
        // 第四引数：相手モデルの主キー
        // belongsToMany.....多対多
        return $this->belongsToMany('App\Models\Categories\SubCategory', 'post_sub_categories', 'post_id', 'sub_category_id');
    }

    // コメント数
    public function commentCounts($post_id)
    {
        return Post::with('postComments')->find($post_id)->postComments();
    }
}
