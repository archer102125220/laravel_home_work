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
        $posts = Post::select('posts.*','users.name','comments.content as comment','comments.comment_id')
                    ->leftJoin('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')->get();
        $hasPost = [];
        foreach ( $posts as $key => $value){
            if( !in_array($value['posts_id'], $hasPost) ) {
                $newPost[$key]['posts_id'] = $value['posts_id'];
                $newPost[$key]['account'] = $value['account'];
                $newPost[$key]['title'] = $value['title'];
                $newPost[$key]['content'] = $value['content'];
                $newPost[$key]['created_at'] = $value['created_at'];
                $newPost[$key]['name'] = $value['name'];
                $newPost[$key]['comment'] = [];
            }
            $comment[$key]['posts_id'] = $value['posts_id'];
            if($value['comment'] != null){
                $comment[$key]['comment'] = $value['comment'];
                $comment[$key]['comment_id'] = $value['comment_id'];
                $comment[$key]['posts_id'] = $value['posts_id'];
            } else {
                $comment[$key]=[];
            }
            if(!in_array($value['posts_id'], $hasPost))array_push($hasPost, $value['posts_id']);
        }
        foreach ( $newPost as $key => $value){
            if( isset($comment[$key]['posts_id']) && $value['posts_id'] === $comment[$key]['posts_id']){
                array_push($newPost[$key]['comment'], $comment[$key]);
            }
        }
        $posts = [];
        foreach ( $newPost as $key => $value){
            $value['comment'] = array_reverse($value['comment']);
            array_push($posts, $value);
        }
        if($posts){
            return response()->json($posts, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
    public function Post($postsId)
    {
        $posts = Post::select('posts.*','users.name','comments.content as comment','comments.comment_id')
                    ->leftJoin('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')
                    ->where('posts.posts_id', $postsId)->get();
        $hasPost = [];
        foreach ( $posts as $key => $value){
            if( !in_array($value['posts_id'], $hasPost) ) {
                $newPost[$key]['posts_id'] = $value['posts_id'];
                $newPost[$key]['account'] = $value['account'];
                $newPost[$key]['title'] = $value['title'];
                $newPost[$key]['content'] = $value['content'];
                $newPost[$key]['created_at'] = $value['created_at'];
                $newPost[$key]['name'] = $value['name'];
                $newPost[$key]['comment'] = [];
            }
            $comment[$key]['posts_id'] = $value['posts_id'];
            if($value['comment'] != null){
                $comment[$key]['comment'] = $value['comment'];
                $comment[$key]['comment_id'] = $value['comment_id'];
                $comment[$key]['posts_id'] = $value['posts_id'];
            } else {
                $comment[$key]=[];
            }
            if(!in_array($value['posts_id'], $hasPost))array_push($hasPost, $value['posts_id']);
        }
        foreach ( $newPost as $key => $value){
            if( isset($comment[$key]['posts_id']) && $value['posts_id'] === $comment[$key]['posts_id']){
                array_push($newPost[$key]['comment'], $comment[$key]);
            }
        }
        $posts = [];
        foreach ( $newPost as $key => $value){
            $value['comment'] = array_reverse($value['comment']);
            array_push($posts, $value);
        }
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
