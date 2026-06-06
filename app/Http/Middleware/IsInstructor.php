<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsInstructor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role === 'student') {
            Auth::logout();
            return redirect('/login')->withErrors(['error' => 'Akses ditolak! Web ini khusus Instruktur/Admin.']);
        }

        return $next($request);
    }
}