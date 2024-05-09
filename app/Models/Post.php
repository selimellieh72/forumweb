<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $fillable = ['title', 'description'];

    public function likedUsers() {
        return $this->hasManyThrough(
            'App\Models\User',
            'App\Models\PostUsers',
            'post_id',
            'id',
            'id',
            'user_id'
            
        );
    }
}

