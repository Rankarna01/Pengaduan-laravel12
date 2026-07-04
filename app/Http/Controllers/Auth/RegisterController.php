<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nik'                   => ['required', 'string', 'digits:16', 'unique:users'],
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'alamat'                => ['required', 'string'],
            'dusun'                 => ['required', 'in:Penam Raya,Buin Gali,Kabuyit Timur,Langam,Bringin Dalam,Lagenang,Sigar Mandang,Kabuyit Barat,Buin Panan'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.digits'            => 'NIK harus 16 digit.',
            'nik.unique'            => 'NIK sudah terdaftar.',
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar.',
            'alamat.required'       => 'Alamat wajib diisi.',
            'dusun.required'        => 'Dusun wajib dipilih.',
            'dusun.in'              => 'Pilihan dusun tidak valid.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'nik'      => $validated['nik'],
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'alamat'   => $validated['alamat'],
            'dusun'    => $validated['dusun'],
            'password' => Hash::make($validated['password']),
            'role'     => 'masyarakat',
        ]);

        Auth::login($user);

        return redirect()->route('member.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
    }
}
