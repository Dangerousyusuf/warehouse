<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Kullanıcının oturum açıp açmadığını kontrol et
        if (!Auth::check()) {
            abort(403, 'Bu sayfaya erişim izniniz yok.');
        }

        // Kullanıcının rollerini kontrol et
        $userRoles = Auth::user()->getRoleNames(); // Kullanıcının rollerini al

        // Kullanıcının rollerinin belirtilen rollerle eşleşip eşleşmediğini kontrol et
        if (!$userRoles->intersect($roles)->isNotEmpty()) {
            abort(403, 'Bu sayfaya erişim izniniz yok.');
        }

        return $next($request);
    }
}