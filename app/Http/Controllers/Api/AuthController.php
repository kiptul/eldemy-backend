<?php

namespace App\Http\Controllers\Api;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah!',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'data' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'role' => 'student',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Welcome notification
        Notification::send(
            $user->id,
            'system',
            'Selamat Datang di Eldemy! 👋',
            'Hai ' . $user->name . '! Selamat bergabung di platform Eldemy. Mulai jelajahi kursus-kursus menarik dan tingkatkan kemampuan Anda.',
            []
        );

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Selamat datang di Eldemy.',
            'data' => $user,
            'token' => $token
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    public function updateNickname(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:50'
        ]);

        $user = $request->user();
        $user->nickname = $request->nickname;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nama panggilan berhasil disimpan!',
            'data' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096'
        ]);

        $user = $request->user();

        if ($request->has('name') && !empty($request->name)) {
            $user->name = $request->name;
        }

        if ($request->has('nickname')) {
            $user->nickname = $request->nickname;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . Str::slug($user->name ?? 'user') . '.' . $file->getClientOriginalExtension();
            
            // Simpan LANGSUNG ke folder public agar bisa diakses web secara langsung
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            
            // Simpan path relatif di database
            $user->avatar_url = 'uploads/avatars/' . $filename;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'data' => $user->fresh()
        ]);
    }

    public function getAvatar($filename)
    {
        // Cek di folder public terlebih dahulu (metode baru)
        $publicPath = public_path('uploads/avatars/' . $filename);
        if (file_exists($publicPath)) {
            return response()->file($publicPath);
        }

        // Fallback: cek di storage lama
        $storagePath = storage_path('app/public/avatars/' . $filename);
        if (file_exists($storagePath)) {
            return response()->file($storagePath);
        }

        abort(404);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'access_token' => 'required_without:id_token|string|nullable',
            'id_token' => 'required_without:access_token|string|nullable',
        ]);

        try {
            if ($request->filled('id_token')) {
                // Panggil Google API tokeninfo untuk memverifikasi ID Token
                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->get('https://oauth2.googleapis.com/tokeninfo', [
                        'id_token' => $request->id_token
                    ]);
            } else {
                // Panggil Google API dengan timeout lebih panjang (30 detik)
                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->get('https://www.googleapis.com/oauth2/v3/userinfo', [
                        'access_token' => $request->access_token
                    ]);
            }

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google tidak valid atau kadaluarsa. HTTP ' . $response->status()
                ], 401);
            }

            $googleUser = $response->json();
            
            // Pastikan data yang diperlukan ada
            if (!isset($googleUser['email'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengambil data email dari Google.'
                ], 401);
            }

            $user = User::where('email', $googleUser['email'])->first();

            if ($user) {
                // Gunakan getRawOriginal agar accessor tidak mengubah value saat dibaca
                $currentRawAvatar = $user->getRawOriginal('avatar_url');
                
                // Jika user sudah punya avatar lokal (bukan dari Google), pertahankan
                $newAvatar = $currentRawAvatar;
                if (!$currentRawAvatar || str_starts_with($currentRawAvatar, 'http')) {
                    // Belum ada avatar lokal, gunakan dari Google
                    $newAvatar = $googleUser['picture'] ?? $currentRawAvatar;
                }

                $user->google_id = $googleUser['sub'] ?? null;
                $user->avatar_url = $newAvatar;
                $user->save();
                $user = $user->fresh();
            } else {
                $user = User::create([
                    'name' => $googleUser['name'] ?? 'Siswa Eldemy',
                    'email' => $googleUser['email'],
                    'password' => Hash::make(Str::random(24)),
                    'google_id' => $googleUser['sub'] ?? null,
                    'avatar_url' => $googleUser['picture'] ?? null,
                    'role' => 'student',
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login Google berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal autentikasi Google: ' . $e->getMessage()
            ], 401);
        }
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dihapus.'
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini salah.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui.'
        ]);
    }
}