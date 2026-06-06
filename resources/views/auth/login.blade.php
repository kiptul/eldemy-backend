<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Eldemy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
    <style>
        body {
            overflow-y: auto !important;
            min-height: 100vh;
        }
        .auth-container {
            margin: 40px auto;
        }
    </style>
</head>

<body>

    <div class="bg-glow bg-glow-tl"></div>
    <div class="bg-glow bg-glow-br"></div>

    <div class="auth-container">

        <div class="auth-card auth-left">
            <div class="left-text">
                <h1>Selamat<br>Datang,<br>Instruktur!</h1>
                <p>Studio Anda telah siap. Mari ciptakan<br>sesuatu yang luar biasa hari ini.</p>
            </div>
            <div class="left-image"></div>
        </div>

        <div class="auth-card auth-right">

            <div class="brand-logo">
                ELDEMY
                <div class="brand-line"></div>
            </div>

            @if($errors->any())
                <div class="error-alert">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" class="form-input" placeholder="instruktur@eldemy.com" required>
                </div>

                <div class="form-group" style="margin-bottom: 8px;">
                    <label>Kata Sandi</label>
                    <div style="position: relative; width: 100%;">
                        <input type="password" name="password" id="password" class="form-input" style="padding-right: 50px;" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye" id="togglePasswordIcon"
                           style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); cursor: pointer; color: #888; font-size: 1rem; z-index: 2;"
                           onclick="togglePassword('password', this)"></i>
                    </div>
                </div>

                <div class="forgot-link-wrapper">
                    <a href="#" class="forgot-pw">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" class="btn-primary">Masuk</button>
            </form>

            <div class="divider"><span>ATAU LANJUTKAN DENGAN</span></div>

            <div class="social-login">
                <a href="{{ route('social.redirect', 'google') }}" class="btn-social" style="width: 100%;">
                    <i class="fa-brands fa-google text-dark"></i> <span class="social-text">Lanjutkan dengan Google</span>
                </a>
            </div>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>

            <div style="display: flex; justify-content: center; gap: 16px; margin-top: 18px; flex-wrap: wrap;">
                <a href="/terms" style="color: #888; font-size: 0.78rem; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#F06292'" onmouseout="this.style.color='#888'">
                    <i class="fa-solid fa-gavel" style="margin-right: 4px;"></i>Syarat & Ketentuan
                </a>
                <a href="/refund-policy" style="color: #888; font-size: 0.78rem; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#F06292'" onmouseout="this.style.color='#888'">
                    <i class="fa-solid fa-hand-holding-dollar" style="margin-right: 4px;"></i>Refund Policy
                </a>
                <a href="/faq" style="color: #888; font-size: 0.78rem; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#F06292'" onmouseout="this.style.color='#888'">
                    <i class="fa-solid fa-circle-question" style="margin-right: 4px;"></i>FAQ
                </a>
            </div>

        </div>

    </div>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>