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

    /**
     * Update a department
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Inertia\Response
     */
    public function update(Request $request)
    {
        // User
        $user = auth()->user();

        // If user is not an admin, redirect to dashboard
        if ($user->type != 1) {
            return Redirect::route('dashboard')->with('error', 'You are not authorized to view that page.');
        }

        // Validate input
        $validated = $request->validate(
            [
                'id' => 'required|integer|exists:departments,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255'
            ],
            [
                'id.required' => 'Please select a department to update.',
                'id.integer' => 'Department ID must be an integer.',
                'id.exists' => 'Department does not exist.',
                'name.required' => 'Please enter a department name.',
                'name.string' => 'Department name must be a string.',
                'name.max' => 'Department name must not exceed 255 characters.',
                'description.required' => 'Please enter a department description.',
                'description.string' => 'Department description must be a string.',
                'description.max' => 'Department description must not exceed 255 characters.'
            ]
        );

        // Get model
        $department = Department::findOrFail($request->id);

        // Update attributes
        $department->name = $validated['name'];
        $department->description = $validated['description'];

        // Save model
        $department->save();

        // Return response
        return Redirect::route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Delete a department
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
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
                'id' => 'required|integer|exists:departments,id',
                'name' => 'required|string|exists:departments,name'
            ],
            [
                'id.required' => 'Please select a department to delete.',
                'id.integer' => 'Department ID must be an integer.',
                'id.exists' => 'Department does not exist.',
                'name.required' => 'Please enter a department name.',
                'name.string' => 'Department name must be a string.',
                'name.exists' => 'Department does not exist.'
            ]
        );

        // Get department
        $department = Department::find($request->id);

        // If department does not exist, return error via request
        if (!$department) {
            return redirect()->back()->withErrors(['department' => 'Department does not exist.']);
        }

        // If department name does not match, return error via request
        if ($department->name != $request->name) {
            return redirect()->back()->withErrors(['name' => 'Invalid department name.']);
        }

        // If department has issues or employees, return error via request
        if ($department->issues->count() > 0 || $department->employees->count() > 0) {
            return redirect()->back()->withErrors(['department' => 'Department cannot be deleted because it has associated issues or employees.']);
        }

        // Delete department
        $department->delete();

        // Redirect to departments index page
        return Redirect::route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
