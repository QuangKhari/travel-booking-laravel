<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Login;

class LoginController extends Controller
{
    private $login;

    public function __construct()
    {
        $this->login = new Login();
    }

    public function index()
    {
        $title = 'Đăng nhập';
        return view('clients.login', compact('title'));
    }

    // Xử lý người dùng đăng nhập
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $data_login = [
            'username' => $username,
            'password' => md5($password)
        ];

        $user = $this->login->login($data_login);

        if ($user) {
            if ($user->status == 'b') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản đã bị chặn'
                ]);
            }

            $request->session()->put('username', $username);

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'redirectUrl' => route('home')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin tài khoản không chính xác!',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('username');
        return redirect()->route('home');
    }
}