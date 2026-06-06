<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Instruktur - Eldemy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    @if(is_null(Auth::user()->username))
        <div class="username-overlay">
            <div class="username-modal">
                <div class="modal-icon">
                    <i class="fa-solid fa-at"></i>
                </div>
                <h2>Pilih Username Anda</h2>
                <p>Satu langkah lagi! Buat identitas unik Anda agar siswa mudah mengenali Anda di Eldemy.</p>

                @if($errors->has('username'))
                    <div
                        style="background: #fee; color: #c00; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 0.85rem;">
                        {{ $errors->first('username') }}
                    </div>
                @endif

                <form action="{{ route('set.username') }}" method="POST">
                    @csrf
                    <div class="input-wrapper">
                        <span class="prefix">@</span>
                        <input type="text" name="username" placeholder="budi_instruktur" required pattern="^[a-zA-Z0-9_-]+$"
                            title="Hanya huruf, angka, strip, dan underscore.">
                    </div>
                    <button type="submit" class="btn-save-username">Simpan & Lanjutkan</button>
                </form>
            </div>
        </div>
    @endif
    @include('instructor.partials.sidebar')

    <main class="main-content">

        <header class="header">
            <div class="header-text">
                <h1>Dasbor Instruktur</h1>
                <p>Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}. Berikut adalah ringkasan progres
                    Anda.</p>
            </div>
            <div class="header-icons">
                <button class="icon-btn"><i class="fa-regular fa-bell"></i></button>
                <div style="position: relative;" id="settingsDropdownContainer">
                    <button class="icon-btn" onclick="toggleSettings()"><i class="fa-solid fa-gear"></i></button>
                    <div id="settingsDropdown" style="display: none; position: absolute; right: 0; top: 55px; background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 10px; width: 160px; z-index: 100;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="width: 100%; border: none; background: transparent; cursor: pointer; color: #d93025; text-align: left; padding: 10px; font-weight: 600; border-radius: 8px;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='transparent'">
                                <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 8px;"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="bento-grid">

            <div class="card chart-card">
                <div>
                    <span class="badge-live">WAWASAN LANGSUNG</span>
                    <span style="font-size: 0.8rem; font-weight: 600; color: #888; margin-left: 10px;">Berdasarkan
                        penjualan</span>
                </div>
                <div class="chart-header">
                    <h2>{{ $totalCourses > 0 ? 'Aktif' : 'Baru' }}</h2>
                    <p>Status Pengajar Anda</p>
                </div>

                <svg class="svg-chart" viewBox="0 0 500 100" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="grad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:rgba(136, 110, 40, 0.2);stop-opacity:1" />
                            <stop offset="100%" style="stop-color:rgba(255,255,255,0);stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <path d="{{ $pathD }}" fill="url(#grad)" />
                    <path d="{{ $lineD }}" fill="none" stroke="#886e28" stroke-width="3" />
                    @foreach($svgPoints as $point)
                        @php
                            list($cx, $cy) = explode(',', $point);
                        @endphp
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="4" fill="#111" />
                    @endforeach
                </svg>
                <div class="days-row">
                    @for($i = 6; $i >= 0; $i--)
                        <span>{{ strtoupper(\Carbon\Carbon::now()->subDays($i)->isoFormat('ddd')) }}</span>
                    @endfor
                </div>
            </div>

            <div class="card leaderboard-card">
                <h3>Siswa Terbaru <i class="fa-solid fa-ellipsis"></i></h3>

                @forelse($recentStudents as $index => $student)
                    <a href="{{ route('instructor.students.index') }}" style="text-decoration: none; color: inherit; display: block;">
                        <div class="student-item" style="cursor: pointer; transition: background 0.2s ease;">
                            <span class="rank {{ $index == 0 ? 'rank-1' : '' }}">#{{ $index + 1 }}</span>
                            <div class="student-avatar"
                                style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random');">
                            </div>
                            <div class="student-detail">
                                <h4>{{ $student->name }}</h4>
                                <p>Siswa Aktif</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p style="font-size: 0.85rem; color: #888; text-align: center; margin-top: 40px;">Belum ada siswa yang
                        membeli kursus Anda.</p>
                @endforelse

                <a href="{{ route('instructor.students.index') }}" class="view-all">Lihat Semua Peringkat <i
                        class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="bottom-stats">

                <div class="card stat-card card-yellow">
                    <div class="circle-deco"></div>
                    <div class="stat-icon"><i class="fa-solid fa-book"></i></div>
                    <div>
                        <h4>Total Kursus</h4>
                        <h2>{{ $totalCourses }}</h2>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill"></div>
                        </div>
                    </div>
                </div>

                <div class="card stat-card card-dark">
                    <div class="shape-deco"></div>
                    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <h4>Siswa Aktif</h4>
                        <h2>{{ number_format($activeStudents, 0, ',', '.') }}</h2>
                        <div class="avatar-group">
                            <div class="ava" style="background-color: #555;"></div>
                            <div class="ava" style="background-color: #666;"></div>
                            <div class="ava" style="background-color: #777;"></div>
                        </div>
                    </div>
                </div>

                <div class="card stat-card card-pink">
                    <i class="fa-solid fa-money-bill-wave money-deco"></i>
                    <div class="stat-icon" style="color: #8a2020;"><i class="fa-solid fa-wallet"></i></div>
                    <div>
                        <h4>Pendapatan Bersih</h4>
                        <h2 style="font-size: 2.2rem;">Rp{{ number_format($netRevenue, 0, ',', '.') }}</h2>
                        <span class="target-badge">REAL-TIME DATA</span>
                    </div>
                </div>

            </div>

        </div>

    </main>
    <script>
        function toggleSettings() {
            const dropdown = document.getElementById('settingsDropdown');
            if(dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        document.addEventListener('click', function(event) {
            const container = document.getElementById('settingsDropdownContainer');
            const dropdown = document.getElementById('settingsDropdown');
            if (container && !container.contains(event.target) && dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>

</html>