<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // ✅ Base validation (untuk semua role)
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        // ✅ Additional validation ONLY for customer role
        if (!$request->has('role') || $request->role === 'customer') {
            $rules = array_merge($rules, [
                'no_hp' => 'required|string|max:12|unique:users',
                'no_hp2' => 'required|string|max:12|unique:users',
                'nama_no_hp2' => 'required|string|max:255',
                'relasi_no_hp2' => 'required|string|max:255',
                'NIK' => 'required|string|size:16|unique:users',
                'Norek' => 'required|string|max:20|unique:users',
                'Nama_Ibu' => 'required|string|max:255',
                'Pekerjaan' => 'required|string|max:255',
                'Gaji' => 'required|string|max:16',
                'alamat' => 'required|string',
                'kode_bank' => 'required|string|exists:banks,kode_bank',
            ]);
        }

        $validated = $request->validate($rules);
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = $validated['role'] ?? 'customer';

        $user = User::create($validated);

        // ✅ Create token with abilities based on role
        $abilities = $this->getAbilitiesByRole($user->role);
        $token = $user->createToken('auth-token', $abilities)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registration successful'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // ✅ Create token with abilities based on role
        $abilities = $this->getAbilitiesByRole($user->role);
        $token = $user->createToken('auth-token', $abilities)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login successful'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    private function getAbilitiesByRole($role)
    {
        switch ($role) {
            case 'owner':
                return ['*']; // All permissions
            case 'admin':
                return [
                    'peminjaman:read',
                    'peminjaman:approve',
                    'peminjaman:update',
                ];
            case 'customer':
            default:
                return [
                    'peminjaman:read',
                    'peminjaman:create',
                ];
        }
    }
}
