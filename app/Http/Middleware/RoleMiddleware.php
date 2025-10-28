<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->perfil;

        if ($userRole !== $role) {
           
            // Auth::logout(); 

            if ($userRole === 'mec') {
                 return redirect()->route('mec');
            }
            if ($userRole === 'almox') {
                 return redirect()->route('almox');
            }
            return response()->view('errors.403', [], 403);
        }
        return $next($request);
    }
}