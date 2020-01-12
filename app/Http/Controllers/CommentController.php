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
                ],
            ],
            [
                'posts_id.required' => '缺少文章id',
                'content.required' => '請輸入留言內容',
            ]
        );
        if ($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $commentData['account'] = $commentData['user']->account;
        $comment = Comment::create($commentData);
        $newComment = Comment::select('posts.*','users.name','comments.content as comment','comments.comment_id')
            ->join('posts', 'posts.posts_id', 'comments.posts_id')
            ->join('users', 'users.account', 'comments.account')
            ->where('comments.posts_id', $comment->posts_id)->get();
        $hasPost = [];
        $comment = [];
        foreach ( $newComment as $key => $value){
            if( !in_array($value['posts_id'], $hasPost) ) {
                $newComment[$key]['posts_id'] = $value['posts_id'];
                $newComment[$key]['account'] = $value['account'];
                $newComment[$key]['title'] = $value['title'];
                $newComment[$key]['content'] = $value['content'];
                $newComment[$key]['created_at'] = $value['created_at'];
                $newComment[$key]['name'] = $value['name'];
                $newComment[$key]['comment'] = [];
            }
            if($value['comment'] != null){
                if( isset($comment[$value['posts_id']]) ){
                    array_push($comment[$value['posts_id']], ['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                } else {
                    $comment[$value['posts_id']] = Array(['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                }
            } else {
                $comment[$value['posts_id']] = [];
            }
            if(!in_array($value['posts_id'], $hasPost))array_push($hasPost, $value['posts_id']);
        }
        foreach ( $newComment as $key => $value){
            $newComment[$key]['comment'] = $comment[$value['posts_id']];
        }
        $newComment = [];
        foreach ( $newComment as $value){
            $value['comment'] = array_reverse($value['comment']);
            array_push($newComment, $value);
        }
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
                ],
            ],
            [
                'content.required' => '請輸入留言內容',
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
    public function CommentAll()
    {
        $comments = Comment::select('posts.*','users.name','comments.content as comment','comments.comment_id')
                    ->join('posts', 'posts.posts_id', 'comments.posts_id')
                    ->join('users', 'users.account', 'comments.account')->get();
        if($comments){
            $hasPost = [];
            $comment = [];
            foreach ( $comments as $key => $value){
                if( !in_array($value['posts_id'], $hasPost) ) {
                    $comments[$key]['posts_id'] = $value['posts_id'];
                    $comments[$key]['account'] = $value['account'];
                    $comments[$key]['title'] = $value['title'];
                    $comments[$key]['content'] = $value['content'];
                    $comments[$key]['created_at'] = $value['created_at'];
                    $comments[$key]['name'] = $value['name'];
                    $comments[$key]['comment'] = [];
                }
                if($value['comment'] != null){
                    if( isset($comment[$value['posts_id']]) ){
                        array_push($comment[$value['posts_id']], ['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                    } else {
                        $comment[$value['posts_id']] = Array(['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                    }
                } else {
                    $comment[$value['posts_id']] = [];
                }
                if(!in_array($value['posts_id'], $hasPost))array_push($hasPost, $value['posts_id']);
            }
            foreach ( $comments as $key => $value){
                $comments[$key]['comment'] = $comment[$value['posts_id']];
            }
            $comments = [];
            foreach ( $comments as $value){
                $value['comment'] = array_reverse($value['comment']);
                array_push($comments, $value);
            }
            return response()->json($comments, 200);
        }
        return response()->json(['查無留言'], 400);
    }

    public function Comment($commentId)
    {
        $comments = Comment::select('posts.*','users.name','comments.content as comment','comments.comment_id')
                    ->join('posts', 'posts.posts_id', 'comments.posts_id')
                    ->join('users', 'users.account', 'comments.account')
                    ->where('comment_id', $commentId)->get();
        if($comments){
            $hasPost = [];
            $comment = [];
            foreach ( $comments as $key => $value){
                if( !in_array($value['posts_id'], $hasPost) ) {
                    $comments[$key]['posts_id'] = $value['posts_id'];
                    $comments[$key]['account'] = $value['account'];
                    $comments[$key]['title'] = $value['title'];
                    $comments[$key]['content'] = $value['content'];
                    $comments[$key]['created_at'] = $value['created_at'];
                    $comments[$key]['name'] = $value['name'];
                    $comments[$key]['comment'] = [];
                }
                if($value['comment'] != null){
                    if( isset($comment[$value['posts_id']]) ){
                        array_push($comment[$value['posts_id']], ['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                    } else {
                        $comment[$value['posts_id']] = Array(['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                    }
                } else {
                    $comment[$value['posts_id']] = [];
                }
                if(!in_array($value['posts_id'], $hasPost))array_push($hasPost, $value['posts_id']);
            }
            foreach ( $comments as $key => $value){
                $comments[$key]['comment'] = $comment[$value['posts_id']];
            }
            $comments = [];
            foreach ( $comments as $value){
                $value['comment'] = array_reverse($value['comment']);
                array_push($comments, $value);
            }
            return response()->json($comments, 200);
        }
        return response()->json(['查無留言'], 400);
    }
    public function CommentByPostsId($postsId)
    {
        $comments = Comment::select('posts.*','users.name','comments.content as comment','comments.comment_id')
                    ->join('posts', 'posts.posts_id', 'comments.posts_id')
                    ->join('users', 'users.account', 'posts.account')
                    ->where('comments.posts_id', $postsId)->get();
        if($comments){
            $hasPost = [];
            $comment = [];
            foreach ( $comments as $key => $value){
                if( !in_array($value['posts_id'], $hasPost) ) {
                    $comments[$key]['posts_id'] = $value['posts_id'];
                    $comments[$key]['account'] = $value['account'];
                    $comments[$key]['title'] = $value['title'];
                    $comments[$key]['content'] = $value['content'];
                    $comments[$key]['created_at'] = $value['created_at'];
                    $comments[$key]['name'] = $value['name'];
                    $comments[$key]['comment'] = [];
                }
                if($value['comment'] != null){
                    if( isset($comment[$value['posts_id']]) ){
                        array_push($comment[$value['posts_id']], ['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                    } else {
                        $comment[$value['posts_id']] = Array(['comment' => $value['comment'], 'comment_id' => $value['comment_id'], 'posts_id' => $value['posts_id']]);
                    }
                } else {
                    $comment[$value['posts_id']] = [];
                }
                if(!in_array($value['posts_id'], $hasPost))array_push($hasPost, $value['posts_id']);
            }
            foreach ( $comments as $key => $value){
                $comments[$key]['comment'] = $comment[$value['posts_id']];
            }
            $comments = [];
            foreach ( $comments as $value){
                $value['comment'] = array_reverse($value['comment']);
                array_push($comments, $value);
            }
            return response()->json($comments, 200);
        }
        return response()->json(['查無留言'], 400);
    }
    public function deleteComment(Request $request)
    {
        $commentData = $request->all();
        $objValidator = Validator::make(
            $commentData,
            [
                'comment_id' => [
                    'required',
                ],
            ],
            [
                'comment_id.required' => '請輸入留言編號',
            ]
        );
        if($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $comment = Comment::where('comment_id', $commentData['comment_id'])->first();
        if ($comment) {
            $comment->delete();
            return response()->json(['刪除成功'], 200);
        }else{
            return response()->json(['無此留言'], 400);
        }
    }
}
