<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Comment;

class CommentController extends Controller
{
    public function newComment(Request $request)
    {
        $commentData = $request->all();
        $objValidator = Validator::make(
            $commentData,
            [
                'posts_id'=>[
                    'required',
                ],
                'content' => [
                    'required',
                    'between:0,60',
                ],
            ],
            [
                'posts_id.required' => '缺少文章id',
                'content.required' => '請輸入留言內容',
                'content.between' => '留言內容需小於 60 字元',
            ]
        );
        if ($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $commentData['account'] = $commentData['user']->account;
        $comment = Comment::create($commentData);
        $newComment = Comment::select('comments.*', 'name')
            ->join('posts', 'posts.posts_id', 'comments.posts_id')
            ->join('users', 'users.account', 'comments.account')
            ->where('comments.comment_id', $comment->id)->first();
            // ->find($comment->comment_id);
        return response()->json($newComment, 200);
    }
    public function editComment($commentId, Request $request)
    {
        $commentData = $request->all();
        $objValidator = Validator::make(
            $commentData,
            [
                'content' => [
                    'required',
                    'between:0,60',
                ],
            ],
            [
                'content.required' => '請輸入留言內容',
                'content.between' => '留言內容需小於 60 字元',
            ]
        );
        if ($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $comment = Comment::where('comment_id', $commentId)->first();
        if($comment){
            $comment->content = $commentData['content'];
            $comment->save();
            return response()->json(['修改成功'], 200);
        }
        return response()->json(['無此留言'], 400);
    }
    public function Comments()
    {
        $comments = Comment::select('comments.*', 'name')
                    ->join('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account');
                //Post::all();
        if($comments){
            return response()->json($comments, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
    public function Comment($commentId)
    {
        $comments = Comment::select('comments.*', 'name')
                    ->join('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')
                    ->where('comment_id', $commentId)->first();
                //Post::where('posts_id', $postsId)->first();
        if($comments){
            return response()->json($comments, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
    public function CommentByPostsId($postsId)
    {
        $comments = Comment::select('comments.*', 'name')
                    ->join('comments', 'comments.posts_id', 'posts.posts_id')
                    ->join('users', 'users.account', 'posts.account')
                    ->where('posts_id', $postsId)->first();
                //Post::where('posts_id', $postsId)->first();
        if($comments){
            return response()->json($comments, 200);
        }
        return response()->json(['查無貼文'], 400);
    }
}
