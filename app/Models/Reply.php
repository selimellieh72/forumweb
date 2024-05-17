<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    public $fillable = ['reply', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replyLikedUsers()
    {
        return $this->belongsToMany(User::class, 'reply_liked_user');
    }
}
