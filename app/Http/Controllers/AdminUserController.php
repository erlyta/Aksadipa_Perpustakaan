<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function users()
    {
        $users = User::where('role', 'peminjam')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function destroyUser(User $user)
    {
        if ($user->role !== 'peminjam') {
            abort(404);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Akun user berhasil dihapus.');
    }

    public function staff()
    {
        $staff = User::where('role', 'petugas')->latest()->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $username = explode('@', $validated['email'])[0];

        User::create([
            'full_name' => $validated['full_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas',
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Akun petugas berhasil ditambahkan.');
    }

    public function destroyStaff(User $user)
    {
        if ($user->role !== 'petugas') {
            abort(404);
        }

        $user->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Akun petugas berhasil dihapus.');
    }

    public function updateStaff(Request $request, User $user)
    {
        if ($user->role !== 'petugas') {
            abort(404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.staff.index')->with('success', 'Akun petugas berhasil diperbarui.');
    }
}
