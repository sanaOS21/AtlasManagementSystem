<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'like_user_id',
        'like_post_id'
    ];

    public function users()
    {
        // 多対多
        return $this->belongsToMany('App\Models\Users\User', 'likes', 'like_post_id', 'like_user_id');
    }

    public function post()
    {
        return $this->belongsToMany('App\Models\Posts\Post');
    }


    public function likeCounts($post_id)
    {
        // いいねした投稿のIDを取得しカウント
        return $this->where('like_post_id', $post_id)->get()->count();
    }
}
