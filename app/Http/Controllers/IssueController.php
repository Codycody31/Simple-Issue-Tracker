<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Issue;
use Illuminate\Support\Facades\Redirect;

class IssueController extends Controller
{
    public function index()
    {
        // Get user
        $user = auth()->user();

        // If user is admin, get all issues
        if ($user->type === 1) {
            $issues = Issue::all()->load('user', 'department');
        }
        // If user is staff, get issues of the department
        else {
            $issues = Issue::where('user_id', $user->id)
                ->orWhere('department_id', $user->department_id)
                ->get()
                ->load('user', 'department');
        }

        // Return issues
        return Inertia::render('Issues/Index', [
            'issues' => $issues,
        ]);
    }

    public function create()
    {
        return Inertia::render('Issues/Create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        // Get issue
        $issue = Issue::findOrFail($id)->load('user', 'department', 'comments');

        // Validate if user is authorized to view issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to view it.');
        }

        // Pull the user for each comment
        foreach ($issue->comments as $comment) {
            $comment->load('user');
        }

        // Return issue
        return Inertia::render('Issues/Show', [
            'issue' => $issue,
        ]);
    }

    /**
     * Delete issue.
     *
     * @param int $id The id of the issue
     * @return void
     */
    public function destroy($id)
    {
        // Get issue
        $issue = Issue::findOrFail($id)->load('user', 'department', 'comments');

        // Validate if user is authorized to access issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to access it.');
        }

        // Delete issue
        $issue->delete();

        // Return issues
        return Redirect::route('issues.index')->with('success', 'Issue deleted successfully.');
    }
}
