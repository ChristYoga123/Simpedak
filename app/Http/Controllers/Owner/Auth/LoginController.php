<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view("layouts.owner.guest");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                "email.required" => "Kolom email harap diisi",
                "email.email" => "Kolom email harap diisi dengan format email yang benar",
                "password" => "Kolom password harap diisi"
            ]
        );

        if (Auth::attempt($credentials)) {
            if (Auth::user()->hasRole("Owner")) {
                $request->session()->regenerate();

                return redirect()->route("owner.dashboard.index");
            }

            return redirect()->back()->with("error", "Akun Anda bukan merupakan akun Owner sehingga Anda tidak bisa masuk ke halaman Owner");
        }

        return redirect()->back()->with("error", "Maaf, akun atau password belum terdaftar. Harap registrasi terlebih dahulu");
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route("owner.login.index");
    }
}
