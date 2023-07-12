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

    /**
     * Create a new department
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        // User
        $user = auth()->user();

        // If user is not an admin, redirect to dashboard
        if ($user->type != 1) {
            return Redirect::route('dashboard')->with('error', 'You are not authorized to view that page.');
        }

        // Validate request
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255'
            ],
            [
                'name.required' => 'Please enter a department name.',
                'name.string' => 'Department name must be a string.',
                'name.max' => 'Department name must not exceed 255 characters.',
                'description.required' => 'Please enter a department description.',
                'description.string' => 'Department description must be a string.',
                'description.max' => 'Department description must not exceed 255 characters.'
            ]
        );

        // Create department
        $department = Department::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Redirect to departments index page
        return Redirect::route('departments.index')->with('success', 'Department created successfully.');
    }
}
