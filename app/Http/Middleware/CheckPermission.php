<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // Kullanıcının oturum açıp açmadığını kontrol et
        if (!Auth::check()) {
            abort(403, 'Bu sayfaya erişim izniniz yok.');
        }

        // Kullanıcının rollerini kontrol et
        $userRole = Auth::user()->roles()->pluck('id'); // Kullanıcının rolünün id sini al
        $rolePermissions = Role::whereIn('id', $userRole)->with('permissions')->get(); // Rolün izinlerini al
        $permissionKey = $rolePermissions->pluck('permissions')->flatten()->pluck('name'); // İzinlerin anahtarını al
        // Kullanıcının rolünün belirtilen izinlerle eşleşip eşleşmediğini kontrol et
        if (!$permissionKey->intersect($permissions)->isNotEmpty()) {
            abort(403, $permissionKey);
        }

        return $next($request);
    }
}