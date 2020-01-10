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
                ],
                'content' => [
                    'required',
                ],
            ],
            [
                'title.required' => '請輸入標題內容',
                'content.required' => '請輸入留言內容',
            ]
        );
        if ($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $postData['account'] = $postData['user']->account;
        $post = Post::create($postData);
        $newPost = Post::select('posts.*','users.name')
            ->join('users', 'users.account', 'posts.account')
            ->where('posts.posts_id', $post->posts_id)->first();
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
                ],
                'content' => [
                    'required',
                ],
            ],
            [
                'title.required' => '請輸入標題內容',
                'content.required' => '請輸入留言內容',
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
        $posts = Post::select('posts.*','users.name','comments.content as comment')
                    ->leftJoin('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')->get();
        // $posts = Post::all();
        if($posts){
            return response()->json($posts, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
    public function Post($postsId)
    {
        $posts = Post::select('posts.*','users.name','comments.content as comment')
                    ->leftJoin('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')
                    ->where('posts.posts_id', $postsId)->get();
                //Post::where('posts_id', $postsId)->first();
        if($posts){
            return response()->json($posts, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
    public function deletePost(Request $request)
    {
        $postData = $request->all();
        $objValidator = Validator::make(
            $postData,
            [
                'posts_id' => [
                    'required',
                ],
            ],
            [
                'posts_id.required' => '請輸入文章編號',
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $posts = Post::where('posts_id', $postData['posts_id'])->first();
        if ($posts) {
            $posts->delete();
            return response()->json(['刪除成功'], 200);
        }else{
            return response()->json(['無此貼文'], 400);
        }
    }
}
