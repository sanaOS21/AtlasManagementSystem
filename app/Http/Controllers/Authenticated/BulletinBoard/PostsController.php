<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    // 投稿一覧表示
    public function show(Request $request)
    {
        // postComments...コメントの数
        $posts = Post::with('user', 'postComments')->get();
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        // [02]コメント数表示のため下記追加
        $comment = new PostComment;

        if (!empty($request->keyword)) {
            $posts = Post::with('user', 'postComments')
                ->where('post_title', 'like', '%' . $request->keyword . '%')
                ->orWhere('post', 'like', '%' . $request->keyword . '%')->get();
        } else if ($request->category_word) {
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments')->get();
        } else if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
                ->whereIn('id', $likes)->get();
        } else if ($request->my_posts) {
            $posts = Post::with('user', 'postComments')
                ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    //
    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')
            // findOrFail...()内のidで抽出。見つからないときは例外なげる。
            ->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    // 投稿
    public function postCreate(PostFormRequest $request)
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        return redirect()->route('post.show');
    }

    // 投稿(更新)
    public function postEdit(Request $request)
    {
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    // 投稿(削除)
    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    // メインカテゴリー
    public function mainCategoryCreate(Request $request)
    {
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // サブカテゴリー
    public function SubCategoryCreate(Request $request)
    {
        SubCategory::create(['sub_category' => $request->sub_category_name]);
        return redirect()->route('post.input');
    }


    // コメントの登録
    public function commentCreate(Request $request)
    {
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }
    // いいねした投稿
    public function likeBulletinBoard()
    {
        // toArray()...Collectionを配列に変換する時に使うメソッド(全て配列に変換する)。
        // with('')->where('',$変数)->get()...データを取得する
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();

        // Likeモデルのインスタンスを作成
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }
    // いいねの切り替え
    public function postLike(Request $request)
    {
        // attach...完全重複可。全てのデータが中間テーブルに保存される。↔︎sync(重複不可)
        Auth::user()->likes()->attach($request->post_id);
        return response()->json();
    }
    // いいねの解除
    public function postUnLike(Request $request)
    {
        // detach...ヒモ付の解除をする
        Auth::user()->likes()->detach($request->post_id);
        return response()->json();
    }

    public function index()
    {
        $likes = Post::withCount('likes')->get();
        return view('posts', compact('posts'));
    }
}
