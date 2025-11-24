<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Logika Registrasi Pengguna Baru (Mengembalikan JSON)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil. Silakan login.',
            'user' => $user,
        ], 201);
    }

    /**
     * Logika Login (Mengembalikan User dan Token Sanctum)
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // 1. Cek apakah email dan password cocok
        if (! Auth::attempt($request->only('email', 'password'))) {
            // Jika gagal, kembalikan respons JSON dengan error 401
            return response()->json([
                'message' => 'Kredensial (email atau password) tidak valid.',
            ], 401); 
        }

        // 2. Dapatkan objek user dan beri petunjuk tipe eksplisit
        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        // 3. Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken; 

        // 4. Kembalikan respons JSON yang berisi user dan token (DI DALAM FUNGSI)
        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    } // <-- Fungsi Login ditutup di sini

    /**
     * Logika Logout (Menghapus Token)
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan oleh user
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil, token dihapus.'], 200);
    }
}