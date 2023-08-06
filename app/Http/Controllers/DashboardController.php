<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;
use App\Models\User;
use App\Models\Issue;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get user
        $user = auth()->user();

        // Get all departments
        $departments = Department::all();

        // If user is admin, get all issues
        if ($user->type === 1) {
            $issues = Issue::limit(5)
                ->latest()
                ->get()
                ->load('user', 'department');
        }
        // If user is staff, get issues of the department
        else {
            $issues = Issue::where('user_id', $user->id)
                ->orWhere('department_id', $user->department_id)
                ->limit(5)
                ->latest()
                ->get()
                ->load('user', 'department');
        }

        // Return dashboard
        return Inertia::render('Dashboard', [
            'departments' => $departments,
            'issues' => $issues,
        ]);
    }
}
