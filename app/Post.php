<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Notifiable;
    protected $table = 'posts';
    protected $primarykey = 'posts_id';
    const UPDATED_AT = null;

    protected $fillable=[
        'account', 'title', 'content'
    ];
}
