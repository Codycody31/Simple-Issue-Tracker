<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Show all users
     */
    public function index()
    {
        // Get user
        $user = auth()->user();

        // Get departments
        $departments = \App\Models\Department::all();

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
            'users' => $users,
            'departments' => $departments,
        ]);
    }

    /**
     * Create a new user
     * 
     * @param Request $request The request object
     * @return void
     */
    public function store(Request $request)
    {
        // Make sure user is admin
        if (auth()->user()->type !== 1) {
            return Redirect::route('dashboard')->with('error', 'You are not authorized to perform this action.');
        }

        // Validate request
        $request->validate(
            [
                'fullname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'contact' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'department' => 'required|integer|exists:departments,id',
                'designation' => 'required|string|max:255',
                'type' => 'required|integer',
            ],
            [
                'fullname.required' => 'Fullname is required',
                'email.required' => 'Email is required',
                'contact.required' => 'Contact is required',
                'username.required' => 'Username is required',
                'department.required' => 'Department is required',
                'designation.required' => 'Designation is required',
                'type.required' => 'Type is required',
            ]
        );

        // Generate password
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $password = implode($pass); //turn the array into a string

        // Create user
        $user = new User();
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->contact = $request->contact;
        $user->username = $request->username;
        $user->department_id = $request->department;
        $user->designation = $request->designation;
        $user->type = $request->type;
        $user->password = bcrypt($password);
        $user->save();

        // Send email to user with password and email
        $user->notify(new \App\Notifications\UserAccountCreated($password));

        // Redirect to users page
        return Redirect::route('users.index')->with('success', 'User created successfully.');
    }
}
