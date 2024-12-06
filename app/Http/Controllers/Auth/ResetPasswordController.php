<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'],[
            'email.required' => 'E-posta alanı doldurulmalıdır',
            'email.email' => 'Geçerli bir e-posta adresi giriniz',
            'email.exists' => 'Bu e-posta adresi ile bir kullanıcı bulunamadı',
        ]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $this->validateEmail($request);
     
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            if ($response == Password::RESET_LINK_SENT) {
                return redirect()->back()->with('status', 'Şifre sıfırlama linki e-posta adresinize gönderildi.');
            } else {
                return redirect()->back()->withErrors(['email' => 'Şifre sıfırlama linki gönderilemedi.']);
            }
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Beklenmedik bir hata oluştu: ' . $th->getMessage()]);
        }
    }
}
