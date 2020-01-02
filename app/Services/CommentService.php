<?php
namespace App\Services;
use App\User;
use App\Comment;
use Illuminate\Support\Facades\Hash;

class CommentService
{
    public function comment($data)
    {
        $data['account'] = $data['user']->account;
        $newComment = Comment::create($data);
        return Comment::select('comments.*', 'name')
            ->join('users', 'users.account', 'comments.account')
            ->find($newComment->comment_id);
    }
}