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
    <link rel="stylesheet" href="resources/css/settings.css" />
    <!-- Fonts url -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <!-- Icons -->
    <script
      src="https://kit.fontawesome.com/e0dbca38a7.js"
      crossorigin="anonymous"
    ></script>
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
          <h6>My profile settings</h6>
        </div>
      </div>

      <!-- Settings Body -->
      <div class="settings-wrapper">
        <div class="settings-body">
          <h1>Settings</h1>
          <form method="post" class="settings-form" 
          action="{{route('profile.update', Auth::user()->id)}}"
          enctype="multipart/form-data"
          >
          @csrf
            <label>Profile picture</label>
            <div
              class="input-primary settings-input-file"
              id="profileImageContainer"
            >
              <img
                id="profileImage"
                {{
                Auth::user()->avatar? 'src='.asset('storage/' . Auth::user()->avatar) : ''
                }} 
                {{
                !Auth::user()->avatar? 'style=display:none;' : ''
                }} 
                
                alt="upload"
              />
              <div class="edit-button-container">
                <img
                  class="edit-button"
                  src="/resources/images/edit.png"
                  alt="edit"
                />
                <input
                  type="file"
                  id="fileInput"
                  style="display: none"
                  name="avatar"
                  accept="image/*"
                />
              </div>
            </div>

            <label>Name</label>
            <input
              class="input-primary"
              type="text"
              id="name"
              name="name"
              value="{{Auth::user()->name}}"
              
            />
            <label>Email</label>
            <input
              class="input-primary"
              type="email"
              id="email"
              name="email"
              value="{{Auth::user()->email}}"
              
            />
            <label>Password</label>
            <div class="password-wrapper">
              <input
                style="width: 100%"
                minlength="8"
                class="input-primary input-password"
                type="password"
                id="password"
                name="password"
                
                
                placeholder=""
              />
              <div class="toggle eye-toggle">
                <i class="fa fa-eye" id="togglePassword"></i>
              </div>
            </div>
            <input type="hidden" name="_method" value="PATCH" />
            <button type="submit" class="button button-filled">Save Changes</button>
          </form>
        </div>
        <div class="profile-body">
          <h1>Profile Details</h1>
          <div class="profile-body-scores">
            <div>
              <h6>{{$likesToPosts}}</h6>
              <p>Likes</p>
            </div>
            <div>
              <h6>{{$likes}}</h6>
              <p>Posts Liked</p>
            </div>
            <div>
              <h6>{{$repliesToPosts}}</h6>
              <p>Replies</p>
            </div>
            <div>
              <h6>{{$replies}}</h6>
              <p>Posts Replied</p>
            </div>
          </div>
          <div class="profile-body-bottom">
            <button class="button button-filled">Create a post</button>
          </div>
        </div>
      </div>
    </div>

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
        <form class="modal-form" id="postForm"
       
        >
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
      const togglePassword = document.getElementById("togglePassword");
      const password = document.getElementById("password");

      togglePassword.addEventListener("click", function () {
        // Toggle the type attribute
        const isPassword = password.type === "password";
        password.type = isPassword ? "text" : "password";

        // Toggle the icon class
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
      });

      document
        .querySelector(".edit-button")
        .addEventListener("click", function () {
          // Trigger the file input click
          document.getElementById("fileInput").click();
        });

      document
        .getElementById("fileInput")
        .addEventListener("change", function () {
          // Check if a file was selected
          if (this.files && this.files[0]) {
            var reader = new FileReader(); // Create a file reader
            reader.onload = function (e) {
              const profileImage = document.getElementById("profileImage");
              profileImage.src = e.target.result; // Update the image source

              // Update classes as needed
              profileImage.classList.remove("old-class"); // Remove the old class
              profileImage.classList.add("avatar-uploaded"); // Add a new class
              const container = document.getElementById(
                "profileImageContainer"
              );
              container.style.padding = "0"; // Set padding to 0
              container.style.backgroundColor = "white"; // Set background color to white
              document.getElementById("profileImage").style.display = "block";
            };
            reader.readAsDataURL(this.files[0]); // Read the file as a Data URL
          
          
          }
        });

      // CREATE POST MODAL
      // Get modal elements
      var modal = document.getElementById("postModal");
      var modalOverlay = document.getElementById("modalOverlay");
      // var btn = document.querySelector(".navbar-right .button"); // Assumes first button is the create post button
      var span = document.getElementsByClassName("close")[0];

      // Open the modal
      // Open the modal
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

      // Prevent form from submitting; you may want to handle this differently
      document.getElementById("postForm").onsubmit = function (e) {
        e.preventDefault();
        alert("Form submitted!"); // Placeholder for form submission logic
        modal.style.display = "none";
        modalOverlay.style.display = "none";
      };
    </script>
  </body>
</html>
