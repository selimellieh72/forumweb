<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Post;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'reply' => 'required',
        ]);

        $post->replies()->create([
            'reply' => $request->reply,
            'user_id' => auth()->id(),
        ]);



        return back();

        
    }

   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reply $reply)
    {
        // check if the user is the owner of the reply or an admin
        if (auth()->id() === $reply->user_id || auth()->user()->role === 'admin') {
            $reply->delete();
        }
        return back();
    }
}
