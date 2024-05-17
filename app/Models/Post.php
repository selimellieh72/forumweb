<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $fillable = ['title', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'post_liked_user');
    }

    public function viewedUsers()
    {
        return $this->belongsToMany(User::class, 'post_viewed_user');
    }



}

