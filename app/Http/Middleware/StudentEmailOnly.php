<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentEmailOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || $user->role !== 'student' || !str_ends_with($user->email, '@student.com')) {
            if ($request->wantsJson() || $request->inertia()) {
                abort(403, 'Access denied. Only students can access this page.');
            }
            return redirect()->route('home')->with('error', 'Access denied. Only students can access this page.');
        }

        return $next($request);
    }
}
