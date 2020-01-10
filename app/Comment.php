<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    // public $incrementing = false;
    const UPDATED_AT = null;

    protected $fillable=[
        'posts_id', 'account', 'content'
    ];
}
