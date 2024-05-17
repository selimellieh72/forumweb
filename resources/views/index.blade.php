<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forum - General</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <!-- Favicon -->
    <link
      rel="icon"
      type="image/x-icon"
      href="resources/images/philosopher.ico"
    />
    <!-- css path -->
    <link rel="stylesheet" href="resources/css/styles.css" />
    <link rel="stylesheet" href="resources/css/main.css" />
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
        @else
    
        <button id="createPostBtn" class="button button-filled">Create a post</button>
     
        @endif
        @endauth
        @auth
       
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
        </div>

        <!-- Top Titles -->
        <div class="top-titles">
          <h1>Your Forum</h1>
          <h6>Where Every Voice Matters</h6>
          <form id="searchForm" method="get" >
          <input value="{{request('filter')}}" name="filter" hidden />
          <input name="filter" value="{{request('filter')}}" hidden />
          <input id="searchInput" value="{{request('search')}}" type="text" name="search" placeholder="Search" />
      
          <input type="submit" hidden />

          </form>
        </div>
      </div>
      <!-- Posts -->
   
      <div class="posts-wrapper">
        <!-- Post Wrapper Upper -->
        <div class="posts-wrapper-upper">
          <h3>{{$allPostsCount}} Conversations</h3>
          <!-- Dropdown -->
          <div class="dropdown">
            <div class="dropdown">
              <button id="dropdownButton">
                <span>
                  {{
                    request()->filter === 'newest' ? 'Newest First' :
                   ( request()->filter === 'most_viewed' ? 'Most Views' :
                    (request()->filter === 'most_replies' ? 'Most Replies' :
                    (request()->filter === 'most_liked' ? 'Most Liked' : 'Newest First')))
                  }}
                </span>
                <img src="/resources/images/down-arrow.png" alt="arrow" />
              </button>
              <div id="dropdownContent" class="dropdown-content">

                <a href="?filter=newest&search={{request('search')}}
                &page={{request('page')}}">Newest First</a>
             
                <a href="?filter=most_viewed&search={{request('search')}}&page={{request('page')}}
                ">Most Views</a>
                <a href="?filter=most_replies&search={{request('search')}}&page={{request('page')}}
                ">Most Replies</a>
                <a href="?filter=most_liked&search={{request('search')}}&page={{request('page')}}
                ">Most Liked</a>
              </div>
            </div>
          </div>
        </div>
        <!-- Post Wrapper Body -->
        <div class="posts-wrapper-body">
        @foreach($posts as $post)
          
          <!-- Single Post -->
          <div class="post">
            <!-- Avatar -->
            <div class="post-avatar">
              <img src="
              {{
                $post->user->avatar ? asset('storage/' . $post->user->avatar) : '/resources/images/philosopher.png'
              }}
              " alt="avatar" />
            </div>
            <!-- Right Content -->
            <div class="post-right">
              <!-- Upper -->
              <div class="post-right-upper">
                <h6>{{$post->user->name}}</h6>
                <span></span>
                <h6>{{$post->created_at->diffForHumans()}}</h6>
              </div>
              <!-- Body -->
              <div class="post-right-body">
                <h3>{{$post->title}} </h3>
                <p>
                  {{$post->description}}
                </p>
              </div>
              <!-- Bottom -->
          
              <div class="post-right-bottom">
              <div>
                <img data-nonclickable src="/resources/images/icons/view.png" alt="likes" />
                <p>{{$post->viewedUsers->count()}}</p>
              </div>
              <form id="post{{$post->id}}like" method="post" action="{{route('posts.like', $post->id)}}" >
                @csrf
               
           
            
                </form>
                <div
                  
                @auth
                onclick="document.forms['post{{$post->id}}like'].submit()"
                @endauth
                >
                  <img 
                  @guest
                    data-disabled
                  @endguest
                  src="/resources/images/icons/like.png" alt="likes" />
                  <p>{{$post->likedUsers->count()}}</p>
                </div>
                </form>
       
             <a href="{{route('posts.show', $post->id)}}">
             <div>
                  <img src="/resources/images/icons/chat.png" alt="replies" />
                  <p>{{$post->replies->count()}}</p>
                </div>
            </a>
              </div>
            </div>
          </div>
          @endforeach
          
         
        </div>
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper">
        @foreach ($other_pages as $other_page)
        <a 
          href="
          ?page={{$other_page}}&filter={{request('filter')}}&search={{request('search')}}"
          '
          >
            <div class="pagination-box {{((request('page') == null && $other_page === 1) || request('page') == $other_page )? 'active' : '' }}">
              <p>{{$other_page}}</p>
            </div>
          </a>
          
        @endforeach
      </div>
    </div>
    <!-- Footer -->
    @guest
      

    <div class="footer">
      <h1>Ready to get stuck in?</h1>
      <button class="button button-filled">Sign Up Now</button>
    </div>
    @endguest
    <div class="last-footer">
      <p>© Forum</p>
      <p>Tony Kosseify - Selim Ellieh</p>
    </div>

    <!-- Modal Structure -->
    <div id="postModal" class="modal">
      <div class="modal-content">
        <div class="modal-top">
          <h5>What's happening?!</h5>
          <span class="close">&times;</span>
        </div>
        <form action="{{route('posts.store')}}" method="post" class="modal-form" id="postForm">
          @csrf
          <label for="title">Title:</label>
          <input
            type="text"
            id="title"
            name="title"
            placeholder=""
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
          ></textarea>
          <button class="button button-filled" type="submit" class="button">
            Submit Post
          </button>
        </form>
      </div>
    </div>
    <div id="modalOverlay" class="modal-overlay"></div>
    <script>
      // keep load position
        document.addEventListener("DOMContentLoaded", function(event) { 
            var scrollpos = localStorage.getItem('scrollpos');
            if (scrollpos) window.scrollTo(0, scrollpos);
        });

        window.onbeforeunload = function(e) {
            localStorage.setItem('scrollpos', window.scrollY);
        };
    </script>
    <script>
      document
        .getElementById("dropdownButton")
        .addEventListener("click", function () {
          var dropdownContent = document.getElementById("dropdownContent");
          dropdownContent.style.display =
            dropdownContent.style.display === "block" ? "none" : "block";
        });

      // Add event listeners to each dropdown link
      document.querySelectorAll(".dropdown-content a").forEach((item) => {
        item.addEventListener("click", function () {
          // Update the text within the span element in the button
          document.querySelector("#dropdownButton span").textContent =
            this.textContent;
          document.getElementById("dropdownContent").style.display = "none";
        });
      });
      // CREATE POST MODAL
      // Get modal elements
      var modal = document.getElementById("postModal");
      var modalOverlay = document.getElementById("modalOverlay");
      var btn = document.querySelector("#createPostBtn");
      var span = document.getElementsByClassName("close")[0];

      // Open the modal
      // Open the modal
      @auth
      btn.onclick = function () {
        modal.style.visibility = "visible";
        modal.style.opacity = "1";
        modalOverlay.style.visibility = "visible";
        modalOverlay.style.opacity = "1";
      };
      @endauth
    

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
      const searchInput = document.getElementById("searchInput");
      const form = document.getElementById("searchForm");

      searchInput.addEventListener("keypress", function(event) {
                if (event.keyCode === 13) { 
                    event.preventDefault();  
                    form.submit();          
                }
            });

      
    </script>
  </body>
</html>
