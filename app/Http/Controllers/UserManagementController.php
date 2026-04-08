<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\AdminApproved;
use Illuminate\Support\Facades\Mail;

class UserManagementController extends Controller
{
    // Halaman daftar user
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $data = [
            'title' => 'Kelola Admin',
            'users' => $users,
            'pending' => User::where('is_approved', false)->count(),
            'approved' => User::where('is_approved', true)->count(),
        ];

        return view('admin.users.index', $data);
    }

    // Approve user
    public function approve($id)
{
    $user = User::findOrFail($id);
    $user->is_approved = true;
    $user->save();

    // ⭐ KIRIM EMAIL KE USER
    Mail::to($user->email)->send(new AdminApproved($user));

    return redirect()->route('admin.users.index')
        ->with('success', 'User berhasil di-approve dan email notifikasi telah dikirim!');
}

    // Reject/hapus user
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User ' . $user->name . ' berhasil dihapus!');
    }
}
