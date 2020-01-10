<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Notifiable;
    protected $table = 'posts';
    protected $primaryKey = 'posts_id';
    // public $incrementing = false;
    const UPDATED_AT = null;

    protected $fillable=[
        'account', 'title', 'content'
    ];
}
