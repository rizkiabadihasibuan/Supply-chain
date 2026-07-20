<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    /**
     * login method — Authenticate user and return profile + role
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($credentials)) {
                return $this->sendError('Email atau password salah', [], 401);
            }

            /** @var User $user */
            $user = Auth::user();
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => (string) ($user->role?->value ?? $user->role),
                        'is_admin' => $user->isAdmin(),
                    ],
                    'redirect_url' => $user->isAdmin() ? route('admin.dashboard') : route('dashboard'),
                ],
            ], 200);
        } catch (ValidationException $e) {
            return $this->sendError('Validasi gagal', $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError('Gagal melakukan login', [$e->getMessage()], 500);
        }
    }

    /**
     * register method — Register new user with Admin or User role
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:admin,user',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi akun berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => (string) ($user->role?->value ?? $user->role),
                        'is_admin' => $user->isAdmin(),
                    ],
                    'redirect_url' => $user->isAdmin() ? route('admin.dashboard') : route('dashboard'),
                ],
            ], 201);
        } catch (ValidationException $e) {
            return $this->sendError('Validasi gagal', $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError('Gagal melakukan registrasi', [$e->getMessage()], 500);
        }
    }

    /**
     * logout method — Logout user
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Gagal melakukan logout', [$e->getMessage()], 500);
        }
    }
}