<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        $title = 'Đăng nhập';
        return view('clients.login', compact('title'));
    }

    public function login(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'email_login' => 'required|email',
            'password_login' => 'required',
        ], [
            'email_login.required' => 'Email không được để trống.',
            'email_login.email' => 'Email không đúng định dạng.',
            'password_login.required' => 'Mật khẩu không được để trống.',
        ]);

        $email = $request->email_login;
        $password = $request->password_login;

        // 2. Tìm User trong database dựa trên 'email'
        $user = User::where('email', $email)->first();

        // 3. Kiểm tra user và mật khẩu
        if ($user && Hash::check($password, $user->password)) {
            // Kiểm tra trạng thái
            if ($user->status == 'b') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản đã bị chặn'
                ]);
            }

            // Đăng nhập thành công, lưu session
            $request->session()->put('userId', $user->userId);
            $request->session()->put('username', $user->username);
            return redirect()->route('home');
        } else {
            return redirect()->back()
                ->withErrors(['login_error' => 'Email hoặc mật khẩu không chính xác!'])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('username');
        return redirect()->route('home');
    }
}
