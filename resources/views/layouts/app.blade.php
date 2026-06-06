<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eldemy - Dasbor Instruktur</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #faf7f2;
            color: #333;
        }

        .navbar-eldemy {
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-radius: 20px;
            margin-top: 20px;
            padding: 15px 20px;
        }

        .brand-logo-nav {
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #111;
            font-size: 1.4rem;
            text-decoration: none;
        }

        .btn-logout {
            background-color: #fce8e8;
            color: #d93025;
            border: none;
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background-color: #f8d4d4;
            color: #b0261d;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-eldemy d-flex justify-content-between align-items-center mb-4">
            <a class="brand-logo-nav" href="#">ELDEMY</a>

            @auth
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted fw-medium d-none d-md-block">Halo, <span
                            class="text-dark">{{ Auth::user()->name }}</span></span>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i>
                            Keluar</button>
                    </form>
                </div>
            @endauth
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>