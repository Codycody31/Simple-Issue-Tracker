<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Issue;
use Illuminate\Support\Facades\Redirect;

class IssueCommentsController extends Controller
{
    /**
     * Store comment.
     *
     * @param int $issueId The issue id
     * @param Request $request The request
     * @return void
     */
    public function store($issueId, Request $request)
    {
        // Validate request
        $request->validate(
            [
                'comment' => 'required|string',
            ],
            [
                'comment.required' => 'Please enter a comment.',
                'comment.string' => 'Comment must be a string.',
            ]
        );

        // Get issue
        $issue = Issue::findOrFail($issueId)->load('user', 'department');

        // Validate if user is authorized to view issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to access it.');
        }

        // Create comment
        $issue->comments()->create([
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
        ]);

        // Return issue
        return Redirect::route('issues.show', $issue->id)->with('success', 'Comment added successfully.');
    }

    /**
     * Update comment.
     *
     * @param int $issueId The issue id
     * @param Request $request The request
     * @return void
     */
    public function update($issueId, Request $request)
    {
        // Get comment id
        $commentId = $request->id;
        
        // Validate request
        $request->validate(
            [
                'id' => 'required|integer|exists:comments,id',
                'comment' => 'required|string',
            ],
            [
                'id.required' => 'Please enter a comment id.',
                'id.integer' => 'Comment id must be an integer.',
                'id.exists' => 'Comment does not exist.',
                'comment.required' => 'Please enter a comment.',
                'comment.string' => 'Comment must be a string.',
            ]
        );

        // Get issue
        $issue = Issue::findOrFail($issueId)->load('user', 'department');

        // Validate if user is authorized to view issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to access it.');
        }

        // Get comment
        $comment = $issue->comments()->findOrFail($commentId);

        // Update comment
        $comment->update([
            'comment' => $request->comment,
        ]);

        // Return issue
        return Redirect::route('issues.show', $issue->id)->with('success', 'Comment updated successfully.');
    }

    /**
     * Delete comment.
     *
     * @param int $issueId The issue id
     * @param int $commentId The comment id
     * @return void
     */
    public function destroy($issueId, $commentId)
    {
        // Get issue
        $issue = Issue::findOrFail($issueId)->load('user', 'department');

        // Validate if user is authorized to view issue
        if (!$issue || ($issue->user_id !== auth()->user()->id && $issue->department_id !== auth()->user()->department_id && auth()->user()->type !== 1)) {
            return redirect()->route('issues.index')->with('error', 'This issue does not exist or you are not authorized to access it.');
        }

        // Get comment
        $comment = $issue->comments()->findOrFail($commentId);

        // Delete comment
        $comment->delete();

        // Return issue
        return Redirect::route('issues.show', $issue->id)->with('success', 'Comment deleted successfully.');
    }
}
