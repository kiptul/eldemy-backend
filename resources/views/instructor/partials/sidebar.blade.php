@php
    $isCoursesSection = request()->routeIs('instructor.courses.*')
                     || request()->routeIs('instructor.materials.*')
                     || request()->routeIs('instructor.quizzes.*');
    $isDashboard = request()->routeIs('instructor.dashboard');
    $isStudents  = request()->routeIs('instructor.students.*');
@endphp

<aside class="sidebar">
    <div class="brand">
        <h2>Eldemy</h2>
        <p>Instruktur Pro</p>
    </div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('instructor.dashboard') }}"
               class="nav-link {{ $isDashboard ? 'active' : '' }}">
                <i class="fa-solid fa-border-all"></i> Beranda
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('instructor.courses.index') }}"
               class="nav-link {{ $isCoursesSection ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i> Kelola Kursus
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('instructor.students.index') }}"
               class="nav-link {{ $isStudents ? 'active' : '' }}">
                <i class="fa-solid fa-user-group"></i> Daftar Siswa
            </a>
        </li>
    </ul>

    <div style="margin-top: auto; padding-top: 20px;">
        <form action="{{ route('logout') }}" method="POST" style="margin-bottom: 15px;">
            @csrf
            <button type="submit" class="nav-link" style="width: 100%; border: none; background: #fce8e8; border-radius: 12px; cursor: pointer; color: #d93025; text-align: left; font-weight: 700; transition: background 0.2s;" onmouseover="this.style.background='#f8d4d4'" onmouseout="this.style.background='#fce8e8'">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
            </button>
        </form>

        <div style="display: flex; align-items: center; gap: 10px; background: rgba(0,0,0,0.06); padding: 12px 14px; border-radius: 16px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--yellow); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1A1A1A; font-size: 0.9rem;">
                {{ substr(Auth::user()->name ?? 'I', 0, 1) }}
            </div>
            <div style="flex: 1; overflow: hidden;">
                <p style="font-size: 0.8rem; font-weight: 700; color: var(--dark); white-space: nowrap; text-overflow: ellipsis; overflow: hidden; margin: 0;">{{ Auth::user()->name ?? 'Instruktur' }}</p>
                <p style="font-size: 0.65rem; color: var(--gray-text); margin: 0; font-weight: 600; letter-spacing: 0.3px;">MEMBER PREMIUM</p>
            </div>
        </div>
    </div>
</aside>
