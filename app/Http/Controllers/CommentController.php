<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Comment;

class CommentController extends Controller
{
    public function comment(Request $request)
    {
        $postData = $request->all();
        $objValidator = Validator::make(
            $postData,
            [
                'content' => [
                    'required',
                    'between:5,60',
                ],
            ],
            [
                'content.required' => '請輸入留言內容',
                'content.between' => '留言內容需介於 5-60 字元',
            ]
        );
        if ($objValidator->fails()){
            return response()->json($objValidator->errors()->all(), 400);
        }
        $postData['account'] = $postData['user']->account;
        $post = Comment::create($postData);
        $newComment = Comment::select('comments.*', 'name')
            ->join('users', 'users.account', 'comments.account')
            ->find($post->comment_id);
        return response()->json($newComment, 200);
    }

}
