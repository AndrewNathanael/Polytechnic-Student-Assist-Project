<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCheck
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->role === 'student' && str_ends_with($user->email, '@student.com')) {
            return $next($request);
        }

        if ($request->wantsJson() || $request->inertia()) {
            abort(403, 'Access denied. Only students can access this page.');
        }
        return redirect()->route('home')->with('error', 'Access denied. Only students can access this page.');
    }
}
