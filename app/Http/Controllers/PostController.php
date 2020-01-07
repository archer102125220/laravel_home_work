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
        $newPost = Post::select('Post.*', 'name')
            ->join('users', 'users.account', 'Post.account')
            ->find($post->comment_id);
        return response()->json($newPost, 200);
    }
}
