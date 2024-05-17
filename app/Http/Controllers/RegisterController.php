<?php
// RegisterController.php 
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
  
    public function register(Request $request)
    {   

   
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'avatar' => ['image'], 

        ]);
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            
            if ($request->hasFile('avatar')) {
                // store in public disk
                $path = $request->file('avatar')->store('avatars', 'public');

                $user->avatar = $path;
            }
            $user->save();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 
            'An error occurred while creating your account.
            Do you already have an account?']);
     
   
        }
        Auth::login($user, $remember = true);
        return redirect('/');
    }
    public function index()
    {
        return view('sign-up');
    }
}