<?php

namespace App\Http\Controllers\Web;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'student') {
                Auth::logout();
                return back()->withErrors(['email' => 'Siswa tidak diizinkan masuk ke Web Dashboard.']);
            }

            return redirect()->intended('/instructor/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau Password salah.']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function showRegister()
    {
        return view('auth.register');
    }

    public function doRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'instructor',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/instructor/dashboard');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login menggunakan ' . ucfirst($provider)]);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update([
                $provider . '_id' => $socialUser->getId(),
            ]);
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?? 'Instruktur Eldemy',
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                $provider . '_id' => $socialUser->getId(),
                'role' => 'instructor',
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->intended('/instructor/dashboard');
    }

    public function setUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|alpha_dash|max:50|unique:users,username'
        ], [
            'username.unique' => 'Username ini sudah dipakai, silakan pilih yang lain.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip (-), atau underscore (_).'
        ]);

        $user = Auth::user();

        $user->update([
            'username' => strtolower($request->username)
        ]);
        return back();
    }
}