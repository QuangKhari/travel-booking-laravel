@include('clients.blocks.header')

<div class="auth-template">
    <div class="main">
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="{{ asset('clients/assets/images/login/signin-image.jpg') }}" alt="sing up image"></figure>
                        {{-- Thay đổi link tại đây --}}
                        <a href="{{ route('register') }}" class="signup-image-link">Tạo tài khoản</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Đăng nhập</h2>

                        @if(session('success'))
                        <div class="alert alert-success" style="color: #63AB45; font-weight: bold; margin-bottom: 20px; padding: 10px; border: 1px solid #63AB45; border-radius: 5px;">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ route('user-login') }}" method="POST" class="login-form" id="login-form1">
                            @csrf
                            <div class="form-group">
                                <label for="username_login"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username_login" id="username_login" placeholder="Tên người dùng" />
                            </div>
                            <div class="invalid-feedback" style="margin-top: -15px" id="validate_username"></div>

                            <div class="form-group">
                                <label for="password_login"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password_login" id="password_login" placeholder="Mật khẩu" />
                            </div>
                            <div class="invalid-feedback" style="margin-top:-15px" id="validate_password"></div>

                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit" value="Đăng nhập" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@include('clients.blocks.footer')