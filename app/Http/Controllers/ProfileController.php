<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        // Retrieve:
        // total likes of the user
        // total posts of the user
        // total replies of the user
        // total number of replies to the user's posts

        $likes = auth()->user()->likes->count();
  
        $replies = auth()->user()->replies->count();
        $repliesToPosts = auth()->user()->posts->map(function ($post) {
            return $post->replies->count();
        })->sum();
        // total like to the user's posts
        $likesToPosts = auth()->user()->posts->map(function ($post) {
            return $post->likedUsers->count();
        })->sum();

        return view('profile', [
            'likes' => $likes,
           
            'replies' => $replies,
            'repliesToPosts' => $repliesToPosts,
            'likesToPosts' => $likesToPosts,
        ]);
    }

    public function update(Request $request) {
        // get password first

        $request->validate([
      
            'name' => ['string'],
            'email' => ['email', 'unique:users,email,' . auth()->id()],
            'avatar' => ['image'],
           
        ],
    );
    
        if ($request->input('name')) {
            $updates['name'] = $request->input('name');
        }

        if ($request->input('email')) {
            // check that email is not already taken
           
            $updates['email'] = $request->input('email');

        }

        if ($request->input('password')) {
            $updates['password'] = Hash::make($request->input('password'));

        } 
        if ($request->hasFile('avatar')) {
            $updates['avatar'] = $request->file('avatar')->store(
                'avatars', 'public'
            );
        }
    
        auth()->user()->update(
            $updates
        );
        return redirect()->route('profile');
    }
}
