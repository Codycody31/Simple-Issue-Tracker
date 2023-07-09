<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index()
    {
        // Get user
        $user = auth()->user();

        // If user is admin, get all users
        if ($user->type === 1) {
            $users = User::all();
        }
        // If user is not admin, redirect to dashboard
        else {
            return Redirect::route('dashboard');
        }

        // Return view
        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }
}
