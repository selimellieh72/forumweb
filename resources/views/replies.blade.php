<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forum - Replies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <!-- Favicon -->
    <link
      rel="icon"
      type="image/x-icon"
      href="resources/images/philosopher.ico"
    />
    <!-- css path -->
    <link rel="stylesheet" href="/resources/css/styles.css" />
    <link rel="stylesheet" href="/resources/css/main.css" />
    <link rel="stylesheet" href="/resources/css/replies.css" />
    <!-- Fonts url -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Navbar -->
    <div class="navbar">
      <!-- Left Content -->
      <div class="navbar-left">
       <a href="{{route('home')}}">
       <h1>Forum</h1>
       </a>
      </div>
      <!-- Right Content -->
      <div class="navbar-right">
     
        
      @auth
        @if (Auth::user()->disabled)
          <button class="button button-filled" disabled>Account Disabled</button>
   
        @endif
       
        <form action="{{ route('logout') }}" method="post">
          @csrf
          <input type="hidden" name="logout" value="1" />
 
        </form>
        <button  class="button button-filled"
        onclick="event.preventDefault(); document.querySelector('form').submit();"
        >Log out</button>
      
        <a href="{{route('profile')}}"> 
             
        <div class="circle"
        {{
        Auth::user()->avatar? 'style=background-image:url('.asset('storage/' . Auth::user()->avatar).')' : ''
        }} 
        >
       
        
        {!! 
            !Auth::user()->avatar ? 
            '<span class="initials">' . implode('', array_map(function($n){return strtoupper(substr($n,0,1));}, explode(' ', trim(Auth::user()->name)))) . '</span>' 
            : '' 
        !!}
        </div>
        </a>
      
        @endauth
        
        @guest
        <a href="{{route('login')}}" class="button button-filled">Log in</a>
        @endguest

      </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper">
      <!-- Top Search Content -->
      <div class="search-content">
        <!-- Top Header -->
        <div class="search-content-header">
          <h6>Community</h6>
          <h6>></h6>
          <h6>Forum Conversations</h6>
          <h6>></h6>
          <h6>Replies</h6>
          <h6>></h6>
          <!-- Dynamic -->
 
          <h6>{{$post->title}}</h6>
        
        
        </div>
      </div>
      <!-- Root Post -->
      <div class="posts-wrapper posts-wrapper-replies">
        <!-- Post Wrapper Body -->
        <div class="posts-wrapper-body replies-wrapper-body">
          <div class="root-post">
            <div class="flex">
            <h3>{{$post->title}}</h3>
          
            <div class="flex">
            @if (Auth::user() && ( Auth::user()->id == $post->user_id) && !Auth::user()->disabled)
         
              <button id="editPostBtn" class="button positive">Edit</button>
            @endif
            @if (Auth::user() && (Auth::user()->role === "admin" || Auth::user()->id == $post->user_id) && !Auth::user()->disabled)
         

            <form  action="{{route('posts.destroy', $post->id)}}" method="post">
              @csrf
              <input type="hidden" name="_method" value="DELETE">
              <button class="button ">Delete</button>
            </form>
            @endif
            </div>
            </div>
            
            <div class="root-post-details">
              <p>{{$post->created_at->diffForHumans()}}</p>
              <span></span>
              <p>{{$post->viewedUsers->count()}} {{$post->viewedUsers->count() == 1? "view" : "views"}}</p>
              <span></span>
              <p>{{$post->likedUsers->count()}} like{{
                $post -> likedUsers->count() == 1 ? "" : "s"
                }}</p>
              <span></span>
              <p>{{$post->replies->count()}} replies</p>
            </div>
            <div class="root-post-bar"></div>
            <div class="root-post-container"></div>
            <div class="root-post-bottom">
              <!-- Avatar -->
              <div class="post-avatar">
                <img src="{{
                $post->user->avatar ? asset('storage/' . $post->user->avatar) : '/resources/images/philosopher.png'
              }}" alt="avatar" />
              </div>
              <div class="post-right">
                <!-- Upper -->
                <div class="post-right-upper">
                  <h6>{{$post->user->name}}</h6>
                </div>
                <!-- Body -->
                <div class="post-right-body post-right-body-replies">
                  <p>
                    {{$post->description}}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Reply Input -->
      @if (Auth::user() &&  !Auth::user()->disabled)
      <form method="post" action="{{route('posts.reply', $post->id)}}">
        @csrf
        <div  
        class="reply-container posts-wrapper-replies">
          <h1>Reply</h1>
          <textarea
            id="description"
            name="reply"
            placeholder="Your reply"
            required
            class="input-primary"
          ></textarea>
          <button class="button button-filled">Send</button>
        </div>
      </form>
      @endif
      
      
      <!-- Replies -->
      <div class="posts-wrapper">
        <!-- Post Wrapper Upper -->
        <div class="posts-wrapper-upper">
          <h3>{{$post->replies->count()}} 
            {{$post->replies->count() == 1? "Reply" : "Replies"}}

          </h3>
          
         
        </div>
        <!-- Post Wrapper Body -->
        <div class="posts-wrapper-body">
          <!-- Single Post -->
          @foreach ($post->replies as $reply )
          <div class="post">
            <!-- Avatar -->
            <div class="post-avatar">
              <img src=" {{
                $reply->user->avatar ? asset('storage/' . $post->user->avatar) : '/resources/images/philosopher.png'
              }}" alt="avatar" />
            </div>
            <!-- Right Content -->
            <div class="post-right">
              <!-- Upper -->
              <div class="post-right-upper">
                <h6>{{$reply->user->name}}</h6>
                <span></span>
                <h6> {{$reply->created_at->diffForHumans()}}</h6>
                @if (Auth::user() && (Auth::user()->role === "admin" || Auth::user()->id == $reply->user_id) && !Auth::user()->disabled)
                <form style="margin-left: auto;" action="{{route('replies.destroy', $reply->id)}}" method="post">
                  @csrf
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="button ">Delete</button>
                </form>
                @endif
              </div>
       
              <!-- Body -->
              <div class="post-right-body">
                <p>
                  {{$reply->reply}}
                </p>
              </div>
              <!-- Bottom -->
              <div class="post-right-bottom">
               
              </div>
            </div>
          </div>
          @endforeach 
         
        
        </div>
      </div>
      <!-- Pagination -->
      <!-- <div class="pagination-wrapper">
        <div class="pagination">
          <div class="pagination-box active">
            <p>1</p>
          </div>
          <div class="pagination-box">
            <p>2</p>
          </div>
          <div class="pagination-box">
            <p>3</p>
          </div>
          <div class="pagination-box next">
            <img src="/resources/images/icons/next.png" alt="next" />
          </div>
        </div>
      </div> -->
    </div>
    <!-- Footer -->
    <div class="footer">
      <h1>Ready to get stuck in?</h1>
      <button class="button button-filled">Sign Up Now</button>
    </div>
    <div class="last-footer">
      <p>© Forum</p>
      <p>Tony Kosseify - Selim Ellieh</p>
    </div>

    <!-- Modal Structure -->
    <div id="postModal" class="modal">
      <div class="modal-content">
        <div class="modal-top">
          <h5>
            Edit Post
          </h5>
          <span class="close">&times;</span>
        </div>
        <form class="modal-form" id="postForm" 
        action="{{route('posts.update', $post->id)}}" method="post">
  
        @csrf
          <label for="title">Title:</label>
          <input
            type="text"
            id="title"
            name="title"
            placeholder=""
            value="{{
              $post->title
            }}"
            required
            class="input-primary"
          />
          <label for="description">Description:</label>
     
          <textarea
            id="description"
            name="description"
            placeholder=""
            required
          
            class="input-primary"
          >{{
              $post->description
            }}</textarea>
          <input type="hidden" name="_method" value="PATCH">
          <button class="button button-filled" type="submit" class="button">
            Edit Post
          </button>
        </form>
      </div>
    </div>
    <div id="modalOverlay" class="modal-overlay"></div>

    <script>
         
     
      // CREATE POST MODAL
      // Get modal elements
      var modal = document.getElementById("postModal");
      var modalOverlay = document.getElementById("modalOverlay");
      var btn = document.querySelector("#editPostBtn");

      var span = document.getElementsByClassName("close")[0];

      // Open the modal
      // // Open the modal
      btn.onclick = function () {
      
        modal.style.visibility = "visible";
        modal.style.opacity = "1";
        modalOverlay.style.visibility = "visible";
        modalOverlay.style.opacity = "1";
      };

      // Close the modal when clicking on (x)
      span.onclick = function () {
        modal.style.visibility = "hidden";
        modal.style.opacity = "0";
        modalOverlay.style.visibility = "hidden";
        modalOverlay.style.opacity = "0";
      };

      // Close the modal when clicking outside of it
      modalOverlay.onclick = function () {
        modal.style.visibility = "hidden";
        modal.style.opacity = "0";
        modalOverlay.style.visibility = "hidden";
        modalOverlay.style.opacity = "0";
      };

    </script>
  </body>
</html>
