<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
{
    /**
     * Departments index page
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // User
        $user = auth()->user();

        // If user is not an admin, redirect to dashboard
        if ($user->type != 1) {
            return Redirect::route('dashboard')->with('error', 'You are not authorized to view that page.');
        }

        // Get all departments
        $departments = Department::all();

        // Render departments index page
        return Inertia::render('Departments/Index', [
            'departments' => $departments
        ]);
    }
}
