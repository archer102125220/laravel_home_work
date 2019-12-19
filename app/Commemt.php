<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Commemt extends Model
{
    use Notifiable;
    protected $primarykey = 'comment_id';
    const UPDATED_AT = null;

    protected $fillable=[
        'comment_id', 'title', 'content'
    ];
}
