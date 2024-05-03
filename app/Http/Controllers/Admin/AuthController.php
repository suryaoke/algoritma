<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.site.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        $attempts = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $user = User::where('email', $attempts['email'])->first();

        if ($user) {
            // Cek apakah status pengguna adalah 0
            if ($user->status === 0) {
                $notification = [
                    'message' => 'Anda tidak diizinkan untuk login.',
                    'alert-type' => 'warning'
                ];
                return back()->with($notification);
            }

            // Melanjutkan proses login jika status bukan 0
            if (Auth::attempt($attempts)) {
                $notification = [
                    'message' => 'User Login Successfully',
                    'alert-type' => 'success'
                ];
                return redirect()->route('admin.dashboard')->with($notification);
            }
        }

        $notification = [
            'message' => 'Email dan Password Salah',
            'alert-type' => 'warning'
        ];

        return back()->with($notification);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/')->with($notification);
    }
}
