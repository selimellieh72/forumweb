<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $filter = request('filter');

        switch ($filter) {
            case 'newest':
                $posts = Post::latest()->get();
                break;
            case 'most_viewed':
                $posts = Post::withCount('viewedUsers')->orderBy('viewed_users_count', 'desc')->get();
                break;
            case 'most_replies':
                $posts = Post::withCount('replies')->orderBy('replies_count', 'desc')->get();
                break;
            case 'most_liked':
                $posts = Post::withCount('likedUsers')->orderBy('liked_users_count', 'desc')->get();
          
                break;
            default:
                $posts = Post::latest()->get();
              
                break;
        }

        $search = request('search');
  
        if ($search) {
            $posts  = $posts->filter(function ($post) use ($search) {
                return str_contains($post->title, $search) || str_contains($post->description, $search);
            });
        }

        // implement pagination
        $page = request('page') ? request('page') : 1;
        
        $last_page = ceil($posts->count() / 10);
        // create a paginator on the posts that we have
        $all_posts_count = $posts->count();
        $posts = $posts->forPage($page, 10);
        
        
    
        // use paginator where search is applied and then, paginate
        # other pages as integer array, 1 each side of current page
        // except last page and first page, if current page is 1 or last page
        $other_pages = [];

        if ($page > 1 && $page < $last_page) {
            $other_pages = [$page - 1,$page,  $page + 1];
        } elseif ($page == 1) {
           $other_pages = [1];
           // check if page 2 exists
              if ($last_page > 1) {
                $other_pages[] = 2;
              }
            // check if page 3 exists
            if ($last_page > 2) {
                $other_pages[] = 3;
            }
            
        } elseif ($page == $last_page) {
            $other_pages = [$last_page];

            if ($last_page > 1) {
                // insert at beginning of other_pages
                array_unshift($other_pages, $last_page - 1);
            }
            
            if ($last_page > 2) {
                // insert at beginning of other_pages
                array_unshift($other_pages, $last_page - 2);
            }
           

        }
        return view('index', ['posts' => $posts, 'allPostsCount' => $all_posts_count, 'other_pages' => $other_pages, 'page' => $page]);

    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (Auth::user()->disabled) {
            return redirect()->back();
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        Auth::user()->posts()->create($request->all());

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {

       // add user to post.viewed users if not
       $post->viewedUsers()->syncWithoutDetaching(Auth::id());




        
       return view('replies', ['post' => $post]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if ( Auth::id() === $post->user_id) {
      
            $request->validate([
                'title' => 'required',
                'description' => 'required',
            ]);
   
            $post->update($request->all());

        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin' || Auth::id() === $post->user_id) {
                $post->delete();
            }
        }
        return redirect('/');
    }

   

    public function like(Post $post)
    {

        $post->likedUsers()->toggle(Auth::id());

        return back();
    }
}
