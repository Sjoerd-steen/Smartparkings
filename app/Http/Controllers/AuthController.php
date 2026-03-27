<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'E-mailadres is verplicht.',
            'email.email'       => 'Voer een geldig e-mailadres in.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.min'      => 'Wachtwoord moet minimaal 6 tekens zijn.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_banned) {
                Auth::logout();
                return back()->withErrors(['email' => 'Uw account is geblokkeerd.']);
            }

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors([
            'email' => 'E-mailadres of wachtwoord is onjuist.',
        ])->withInput($request->except('password'));
    }

    public function registerForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required'      => 'Naam is verplicht.',
            'email.required'     => 'E-mailadres is verplicht.',
            'email.unique'       => 'Dit e-mailadres is al in gebruik.',
            'password.min'       => 'Wachtwoord moet minimaal 6 tekens zijn.',
            'password.confirmed' => 'Wachtwoorden komen niet overeen.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        Auth::login($user);
        return redirect()->route('user.dashboard')->with('success', 'Account aangemaakt! Welkom bij SmartParking.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
