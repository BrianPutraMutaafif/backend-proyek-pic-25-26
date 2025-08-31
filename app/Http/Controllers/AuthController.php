<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penjual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Public register = buat akun seller secara self-register
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'seller'; // public register hanya buat seller

        $user = User::create($data);

        // buat entry penjual kosong/awalan untuk seller
        Penjual::create([
            'user_id' => $user->id,
            'nama'    => $user->username
        ]);

        return response()->json(['message' => 'Register berhasil', 'user' => $user], 201);
    }

    // Admin-only register: admin bisa membuat user (admin atau seller).
    // Jika role = seller, otomatis buat penjual.
    public function registerByAdmin(Request $request)
    {
        // route yang memanggil method ini harus memakai middleware 'auth:sanctum' & 'role:admin'
        $data = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,seller'
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if ($user->role === 'seller') {
            Penjual::create([
                'user_id' => $user->id,
                'nama'    => $user->username
            ]);
        }

        return response()->json(['message' => 'User dibuat oleh admin', 'user' => $user], 201);
    }


    // public function tampillogin()
    // {
    //     return view('login');
    // }

    // public function tampilwelcome()
    // {
    //     return redirect('/');
    // }

    // Login: terima field 'login' (username atau email) + password
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string'
        ]);

        $login = $request->input('login');
        $user = User::where('username', $login)->orWhere('email', $login)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Username/email atau password salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'role' => $user->role,
        ]);
    }

    // Logout: hapus token
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    // Get authenticated user profile
    public function profile(Request $request)
    {
        //Biar gampang pas frontend request ror
        $user = $request->user();
        
        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at
        ]);
    }
}
