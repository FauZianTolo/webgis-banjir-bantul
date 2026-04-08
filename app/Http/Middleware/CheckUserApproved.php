<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_approved) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda belum disetujui oleh Super Admin. Silakan tunggu notifikasi email.');
        }

        return $next($request);
    }
}
