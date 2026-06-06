<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kursus - Eldemy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/courses.css') }}">

    <style>
        .course-grid {
            grid-auto-rows: minmax(220px, auto) !important;
        }

        .c-card {
            height: 100%;
        }

        .c-dark {
            grid-column: span 2 !important;
        }

        .c-white {
            grid-column: span 1 !important;
        }
    </style>
</head>

<body>

    @include('instructor.partials.sidebar')


    <main class="main-content">

        <header class="page-header">
            <div class="page-title">
                <h1>Kursus Anda</h1>
            </div>
            <div class="header-actions">
                <form action="{{ route('instructor.courses.index') }}" method="GET" class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Cari kursus..." value="{{ request('search') }}">
                </form>
                <a href="{{ route('instructor.courses.create') }}" class="btn-action" style="text-decoration: none; background: var(--dark); color: #fff; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-plus"></i> Tambah Kursus
                </a>
            </div>
        </header>

        <div class="course-grid">

            @forelse($courses as $index => $course)
                @php
                    $mod = $index % 4;
                    $cardClass = '';
                    if ($mod == 0)
                        $cardClass = 'c-yellow';
                    elseif ($mod == 1)
                        $cardClass = 'c-pink';
                    elseif ($mod == 2)
                        $cardClass = 'c-dark';
                    else
                        $cardClass = 'c-white';
                @endphp

                <div class="c-card {{ $cardClass }}" style="display: flex; flex-direction: column;">

                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <span class="card-tag"
                            style="padding: 6px 12px; font-size: 0.7rem;">{{ $course->purchases_count > 0 ? 'AKTIF' : 'DRAFT BARU' }}</span>
                        <span style="font-size: 0.8rem; font-weight: 700; opacity: 0.8;">
                            <i class="fa-solid fa-users"></i> {{ $course->purchases_count }} Siswa
                        </span>
                    </div>

                    <h3 class="card-title"
                        style="{{ ($mod == 0 || $mod == 2) ? 'font-size: 2.2rem;' : 'font-size: 1.6rem;' }} margin-bottom: 8px;">
                        {{ $course->title }}
                    </h3>

                    <div
                        style="font-size: 0.75rem; font-weight: 800; opacity: 0.7; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <span style="background: rgba(0,0,0,0.1); padding: 4px 8px; border-radius: 6px; margin-right: 5px;">{{ $course->skill_level ?? 'UMUM' }}</span>
                        @if(($course->skill_level ?? 'UMUM') === 'UMUM' && $course->category)
                            <i class="fa-solid fa-tag"></i> {{ $course->category }}
                        @endif
                    </div>

                    <p class="card-desc"
                        style="flex-grow: 1; margin-bottom: 25px; opacity: 0.85; font-size: 0.9rem; line-height: 1.5;">
                        {{ $course->description }}
                    </p>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; flex-wrap: wrap; gap: 10px;">
                        <span
                            style="font-weight: 900; font-size: 1.4rem;">Rp{{ number_format($course->base_price, 0, ',', '.') }}</span>

                        <a href="{{ route('instructor.materials.index', $course->id) }}" class="btn-action"
                            style="background: #111; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 15px; font-size: 0.9rem; font-weight: 700; transition: 0.2s;"
                            onmouseover="this.style.background='#333'" onmouseout="this.style.background='#111'">
                            <i class="fa-solid fa-pen"></i> Edit Kursus
                        </a>
                    </div>

                </div>

            @empty
                <div style="grid-column: span 3; text-align: center; padding: 50px; color: #888;">
                    <h3>Anda belum memiliki kursus.</h3>
                    <p>Mulai bagikan pengetahuan Anda hari ini!</p>
                </div>
            @endforelse

            <a href="{{ route('instructor.courses.create') }}" class="c-card c-add">
                <div>
                    <div class="add-icon-btn">+</div>
                    Tambah Kursus Baru
                </div>
            </a>

        </div>

    </main>

</body>

</html>