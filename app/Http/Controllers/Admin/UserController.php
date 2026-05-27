<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'masyarakat')->latest();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $users = $query->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'      => ['required', 'string', 'digits:16', 'unique:users'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'alamat'   => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $user = User::create([
            'nik'      => $validated['nik'],
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'alamat'   => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role'     => 'masyarakat',
        ]);
        return response()->json(['message' => 'Pengguna berhasil ditambahkan.', 'status' => 'success', 'user' => $user]);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nik'      => ['required', 'string', 'digits:16', 'unique:users,nik,' . $user->id],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
            'alamat'   => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);
        $data = [
            'nik'   => $validated['nik'],
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'alamat'=> $validated['alamat'],
        ];
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }
        $user->update($data);
        return response()->json(['message' => 'Data pengguna berhasil diperbarui.', 'status' => 'success', 'user' => $user->fresh()]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus.', 'status' => 'success']);
    }
}
