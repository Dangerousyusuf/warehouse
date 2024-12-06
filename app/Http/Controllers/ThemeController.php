<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function saveTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|string',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $user->theme = $request->theme;
            $user->save();
        }

        return redirect()->back()->with('success', 'Tema başarıyla değiştirildi.');
    }
}
