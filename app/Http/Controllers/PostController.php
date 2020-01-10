<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Post;

class PostController extends Controller
{
    public function newPost(Request $request)
    {
        $postData = $request->all();
        $objValidator = Validator::make(
            $postData,
            [
                'title' => [
                    'required',
                    'between:0,10',
                ],
                'content' => [
                    'required',
                    'between:0,60',
                ],
            ],
            [
                'title.required' => '請輸入標題內容',
                'title.between' => '標題內容需小於 10 字元',
                'content.required' => '請輸入留言內容',
                'content.between' => '留言內容需小於 60 字元',
            ]
        );
        if ($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $postData['account'] = $postData['user']->account;
        $post = Post::create($postData);
        $newPost = Post::select('*')
            ->join('users', 'users.account', 'posts.account')
            ->where('posts.posts_id', $post->id)->first();
            // ->find($post->posts_id);
        return response()->json($newPost, 200);
    }

    public function editPost($postsId, Request $request)
    {
        $postData = $request->all();
        $objValidator = Validator::make(
            $postData,
            [
                'title' => [
                    'required',
                    'between:0,10',
                ],
                'content' => [
                    'required',
                    'between:0,60',
                ],
            ],
            [
                'title.required' => '請輸入標題內容',
                'title.between' => '標題內容需小於 10 字元',
                'content.required' => '請輸入留言內容',
                'content.between' => '留言內容需小於 60 字元',
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $posts = Post::where('posts_id', $postsId)->first();
        if($posts){
            $posts->title = $postData['title'];
            $posts->content = $postData['content'];
            $posts->save();
            return response()->json(['修改成功'], 200);
        }
        return response()->json(['無此貼文'], 400);
    }
    public function Posts()
    {
        $posts = Post::select('*')
                    ->join('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account');
                //Post::all();
        if($posts){
            return response()->json($posts, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
    public function Post($postsId)
    {
        $posts = Post::select('*')
                    ->join('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')
                    ->where('posts.posts_id', $postsId)->first();
                //Post::where('posts_id', $postsId)->first();
        if($posts){
            return response()->json($posts, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
}
