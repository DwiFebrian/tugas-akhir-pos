<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next, $role)
    {
        if (Session::get('role') !== $role) {
            // Jika peran pengguna tidak sesuai, redirect ke halaman yang sesuai
            if (Session::get('role') === 'admin') {
                return redirect('/dashboard');
            } elseif (Session::get('role') === 'kasir') {
                return redirect('/kasir');
            } elseif (Session::get('role') === 'gudang') {
                return redirect('/kategori');
            }
        }

        return $next($request);
    }
}
