<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Commemt extends Model
{
    use Notifiable;
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    // public $incrementing = false;
    const UPDATED_AT = null;

    protected $fillable=[
        'posts_id', 'account', 'content'
    ];
}
