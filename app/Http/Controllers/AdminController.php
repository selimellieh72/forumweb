<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {

        // users alpahbetically ordered
        $users = User::orderBy('name')->get();

        return view('admin', [
        'users' => $users
        ]);
    }

    public function toogleactivate(User $user)
    {
        $user->disabled = !$user->disabled;
        $user->save();

        return redirect()->route('admin');
    }
}
