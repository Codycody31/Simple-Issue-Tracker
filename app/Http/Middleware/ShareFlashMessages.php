<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareFlashMessages
{
    public function handle(Request $request, Closure $next)
    {
        // Share flash messages with every response
        Inertia::share('flash', function () {
            return [
                'success' => fn () => session('success'),
                'error' => fn () => session('error')
            ];
        });

        return $next($request);
    }
}
