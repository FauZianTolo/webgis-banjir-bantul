<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\AdminRegistered;
use App\Mail\NewAdminRegistration;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'admin', // Default role
        'is_approved' => false, // ⭐ NOT APPROVED BY DEFAULT
    ]);

    event(new Registered($user));

    // ⭐ KIRIM EMAIL KE USER (Pending Approval)
    Mail::to($user->email)->send(new AdminRegistered($user));

    // ⭐ KIRIM EMAIL KE SUPER ADMIN
    $superAdmins = User::where('role', 'super_admin')->get();
    foreach ($superAdmins as $admin) {
        Mail::to($admin->email)->send(new NewAdminRegistration($user));
    }

    // ⭐ JANGAN AUTO LOGIN!
    // Auth::login($user); // HAPUS INI!

    // ⭐ REDIRECT KE HALAMAN PENDING
    return redirect()->route('approval.pending');
}
}
