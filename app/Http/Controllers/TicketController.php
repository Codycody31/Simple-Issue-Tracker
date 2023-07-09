<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Issue;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    /**
     * Show the tickets page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Get user
        $user = \App\Models\User::find(auth()->user()->id)->load('department');

        // Get tickets in user's department
        $tickets = Issue::where('department_id', $user->department->id)->get();

        // Return tickets view
        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets
        ]);
    }
}
