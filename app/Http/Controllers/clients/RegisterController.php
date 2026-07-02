<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistration()
    {
        $title = 'Đăng ký';
        return view('clients.register', compact('title'));
    }

    public function register(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'username_register' => 'required|string|max:255',
            'email_register' => 'required|email|unique:tbl_users,email',
            'password_register' => 'required|min:6',
            're_pass' => 'required|same:password_register',
        ], [
            'required' => 'Trường không được để trống.',
            'email' => 'Email không đúng định dạng.',
            'min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'same' => 'Mật khẩu xác nhận không khớp.',
            'unique' => 'Email này đã được sử dụng.',
        ]);

        // 2. Lưu vào database (Sử dụng Model User của Laravel)
        // Lưu ý: Dùng đúng tên biến đã validate
        $user = User::create([
            'username' => $request->input('username_register'),
            'email' => $request->input('email_register'),
            'password' => Hash::make($request->input('password_register')),
        ]);

        // 3. Chuyển hướng về trang đăng nhập kèm thông báo
        return redirect()->route('login')->with('success', 'Tạo tài khoản thành công! Bạn có thể đăng nhập ngay.');
    }
}
