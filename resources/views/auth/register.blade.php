<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Eldemy</title>
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
                <h1>Mari<br>Bergabung,<br>Instruktur!</h1>
                <p>Bagikan ilmu Anda ke seluruh dunia.<br>Perjalanan luar biasa Anda dimulai di sini.</p>
            </div>
            <div class="left-image"
                style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop');">
            </div>
        </div>

        <div class="auth-card auth-right">

            <div class="brand-logo">
                ELDEMY
                <div class="brand-line"></div>
            </div>

            @if($errors->any())
                <div class="error-alert">
                    <ul style="margin: 0; padding-left: 20px; text-align: left;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Budi Santoso"
                        value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" class="form-input" placeholder="instruktur@eldemy.com"
                        value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Kata Sandi</label>
                    <div style="position: relative; width: 100%;">
                        <input type="password" name="password" id="password" class="form-input" style="padding-right: 50px;" placeholder="Minimal 8 karakter" required>
                        <i class="fa-solid fa-eye"
                           style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); cursor: pointer; color: #888; font-size: 1rem; z-index: 2;"
                           onclick="togglePassword('password', this)"></i>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label>Konfirmasi Kata Sandi</label>
                    <div style="position: relative; width: 100%;">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" style="padding-right: 50px;"
                            placeholder="Ulangi kata sandi" required>
                        <i class="fa-solid fa-eye"
                           style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); cursor: pointer; color: #888; font-size: 1rem; z-index: 2;"
                           onclick="togglePassword('password_confirmation', this)"></i>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Daftar Sekarang</button>
            </form>

            <div class="divider"><span>ATAU DAFTAR DENGAN</span></div>

            <div class="social-login">
                <a href="{{ route('social.redirect', 'google') }}" class="btn-social" style="width: 100%;">
                    <i class="fa-brands fa-google text-dark"></i> <span class="social-text">Daftar dengan Google</span>
                </a>
            </div>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
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