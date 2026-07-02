<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // ĐỒNG BỘ: Dùng Model User chuẩn giống LoginController

class UserProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = 'Thông tin cá nhân';
        $userId = $this->getUserId(); // Lấy userId từ session của bạn

        // 1. Kiểm tra nếu chưa đăng nhập
        if (!$userId) {
            return redirect('/login')->withErrors('Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy user từ DB bằng Eloquent chuẩn của Laravel
        $user = User::find($userId);

        // 3. Kiểm tra nếu user không tồn tại trong DB
        if (!$user) {
            // Xóa toàn bộ session liên quan đến đăng nhập
            session()->forget(['userId', 'username', 'avatar']);
            return redirect('/login')->withErrors('Tài khoản không tồn tại.');
        }

        return view('clients.user-profile', compact('title', 'user'));
    }
    
    public function update(Request $req)
    {
        $userId = $this->getUserId();
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Người dùng không tồn tại!'], 404);
        }

        $dataUpdate = [
            'fullName'    => $req->fullName,
            'address'     => $req->address,
            'email'       => $req->email,
            'phoneNumber' => $req->phone
        ];

        // Thực hiện cập nhật bằng Eloquent
        $update = $user->update($dataUpdate);

        if (!$update) {
            return response()->json(['error' => true, 'message' => 'Cập nhật thông tin thất bại!']);
        }

        // Cập nhật lại session tên hiển thị phòng trường hợp người dùng đổi tên mới
        session()->put('username', $req->fullName);

        return response()->json(['success' => true, 'message' => 'Cập nhật thông tin thành công!']);
    }

    public function changePassword(Request $req)
    {
        $userId = $this->getUserId();
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Không tìm thấy người dùng!'], 404);
        }

        // Kiểm tra mật khẩu cũ (Giữ nguyên thuật toán MD5 theo code hiện tại của bạn)
        if (md5($req->oldPass) === $user->password) {
            $update = $user->update(['password' => md5($req->newPass)]);
            
            if (!$update) {
                return response()->json(['error' => true, 'message' => 'Đổi mật khẩu thất bại!']);
            }
            return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
        } else {
            return response()->json(['error' => true, 'message' => 'Mật khẩu cũ không chính xác.'], 400);
        }
    }

    public function changeAvatar(Request $req)
    {
        $userId = $this->getUserId();
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Không tìm thấy người dùng!'], 404);
        }

        $req->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        $avatar = $req->file('avatar');
        $filename = time() . '.' . $avatar->getClientOriginalExtension();

        if ($user->avatar) {
            $oldAvatarPath = public_path('admin/assets/images/user-profile/' . $user->avatar);
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        $avatar->move(public_path('admin/assets/images/user-profile'), $filename);
        
        // Cập nhật tên file ảnh vào DB
        $update = $user->update(['avatar' => $filename]);
        
        if (!$update) {
            return response()->json(['error' => true, 'message' => 'Có vấn đề khi cập nhật ảnh!']);
        }

        $req->session()->put('avatar', $filename);
        return response()->json(['success' => true, 'message' => 'Cập nhật ảnh thành công!']);
    }
}