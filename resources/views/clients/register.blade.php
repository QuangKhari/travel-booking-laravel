@include('clients.blocks.header')

<div class="auth-template">
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Đăng ký</h2>
                        <form action="{{ route('register.submit') }}" method="POST" class="register-form" id="register-form1">
                            @csrf
                            <div class="form-group">
                                <label for="username_register"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username_register" id="username_register"
                                    value="{{ old('username_register') }}" placeholder="Tên người dùng" />
                            </div>
                            @error('username_register')
                            <div style="margin-top:-15px; color: red;">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label for="email_register"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email_register" id="email_register"
                                    value="{{ old('email_register') }}" placeholder="Email của bạn" />
                            </div>
                            @error('email_register')
                            <div style="margin-top:-15px; color: red;">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label for="password_register"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password_register" id="password_register" placeholder="Mật khẩu" />
                                <i class="zmdi zmdi-eye toggle-password" toggle="#password_register"></i>
                            </div>
                            @error('password_register')
                            <div style="margin-top:-15px; color: red;">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Xác nhận mật khẩu" />
                                <i class="zmdi zmdi-eye toggle-password" toggle="#re_pass"></i>
                            </div>
                            @error('re_pass')
                            <div style="margin-top:-15px; color: red;">{{ $message }}</div>
                            @enderror

                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Đăng ký" />
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="{{ asset('clients/assets/images/login/signup-image.jpg') }}" alt="sing up image"></figure>
                        {{-- Thay đổi link tại đây --}}
                        <a href="{{ route('login') }}" class="signup-image-link">Tôi đã là thành viên</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="{{ asset('clients/assets/js/custom-js.js') }}"></script>

@include('clients.blocks.footer')