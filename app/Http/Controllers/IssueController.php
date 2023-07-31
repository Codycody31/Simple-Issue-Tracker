<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Issue;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class IssueController extends Controller
{
    /**
     * Show all issues.
     *
     * @return void
     */
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

        // Get departments
        $departments = \App\Models\Department::all();

        // Return issues
        return Inertia::render('Issues/Index', [
            'issues' => $issues,
            'departments' => $departments,
        ]);
    }

    /**
     * Create issue.
     *
     * @param Request $request The request object
     * @return void
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'department_id' => 'required|integer',
            ],
            [
                'title.required' => 'Title is required.',
                'title.string' => 'Title must be a string.',
                'title.max' => 'Title must not exceed 255 characters.',
                'description.required' => 'Description is required.',
                'description.string' => 'Description must be a string.',
                'department_id.required' => 'Department is required.',
                'department_id.integer' => 'Department must be an integer.',
            ]
        );

        // Create issue
        Issue::create([
            'title' => $request->title,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'user_id' => auth()->user()->id,
        ]);

        // Return issues
        return Redirect::route('issues.index')->with('success', 'Issue created successfully.');
    }

    /**
     * Show issue.
     * 
     * @param int $id The id of the issue
     * @return void
     */
    public function show($id)
    {
        // Get issue
        $issue = Issue::find($id);

        // Validate if user is authorized to view issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to view it.');
        }

        // Load issue with user, department and comments
        $issue->load('user', 'department', 'comments');

        // Pull the user for each comment
        foreach ($issue->comments as $comment) {
            $comment->load('user');
        }

        // Convert issue description markdown to html
        $description = Str::markdown($issue->description);

        // Place description in issue
        $issue->description = $description;

        // Return issue
        return Inertia::render('Issues/Show', [
            'issue' => $issue,
        ]);
    }

    /**
     * Delete issue.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // Validate request
        $id = $request->id;
        $title = $request->title;

        // Validate request
        $request->validate(
            [
                'id' => 'required|integer|exists:issues,id',
                'title' => 'required|string|exists:issues,title'
            ],
            [
                'id.required' => 'Please select an issue to delete.',
                'id.integer' => 'Issue ID must be an integer.',
                'id.exists' => 'Issue does not exist.',
                'title.required' => 'Please select an issue to delete.',
                'title.string' => 'Issue title must be a string.',
                'title.exists' => 'Issue does not exist.'
            ]
        );

        // Get issue
        $issue = Issue::findOrFail($id)->load('user', 'department', 'comments');

        // Validate if user is authorized to access issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->back()->withErrors(['issue' => 'This issue does not exist or you are not authorized to access it.']);
        }

        // Delete issue
        $issue->delete();

        // Return issues
        return Redirect::route('issues.index')->with('success', 'Issue deleted successfully.');
    }

    /**
     * Update issue status (open/closed).
     * 
     * @param int $issueId The issue id
     * @param int $statusId The status id
     * @return void
     */
    public function updateStatus($issueId, $statusId)
    {
        // Get issue
        $issue = Issue::findOrFail($issueId)->load('user', 'department', 'comments');

        // Validate if user is authorized to access issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to access it.');
        }

        // Validate status
        if ($statusId != 0 && $statusId != 1) {
            return redirect()->route('issues.show', $issue->id)->with('error', 'Invalid status.');
        }

        // Update issue status
        $issue->update([
            'status' => $statusId,
        ]);

        // Return issues
        return Redirect::route('issues.show', $issue->id)->with('success', 'Issue status updated successfully.');
    }
}
