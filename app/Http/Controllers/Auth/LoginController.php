<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
    
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'E-posta alanı doldurulmalıdır.',
                'email.email' => 'Geçersiz e-posta adresi.',
                'password.required' => 'Şifre alanı doldurulmalıdır.'
            ]);
      
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return redirect()->intended(default: '/')->with('token', $token)->with('user', $user);
         
        }

        if (!Auth::attempt($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            if (!$user) {
                return back()->withErrors([
                    'email' => 'E-posta adresiniz hatalı.',
                ]);
            } else if (!Auth::validate(['email' => $user->email, 'password' => $credentials['password']])) {
                return back()->withErrors([
                    'password' => 'Şifreniz hatalı.',
                ]);
            } else {
                return back()->withErrors([
                    'email' => 'E-posta adresiniz ve şifreniz hatalı.',
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/'); // Eğer kullanıcı zaten giriş yapmışsa, ana sayfaya yönlendir
        }
        return view('login.login');
    }

    public function showResetForm()
    {
        if (Auth::check()) {
            return redirect('/'); // Eğer kullanıcı zaten giriş yapmışsa, ana sayfaya yönlendir
        }
        return view('login.reset_password');
    }
  
}
