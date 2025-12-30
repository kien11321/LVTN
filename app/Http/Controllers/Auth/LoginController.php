<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Tìm user trong bảng nguoidung
        $user = \App\Models\NguoiDung::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Không tìm thấy tài khoản với email này.'],
            ]);
        }

        // Kiểm tra password
        if (!\Hash::check($password, $user->matkhau)) {
            throw ValidationException::withMessages([
                'email' => ['Mật khẩu không chính xác.'],
            ]);
        }

        // Đăng nhập thành công
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

