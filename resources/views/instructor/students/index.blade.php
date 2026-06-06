<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa - Eldemy Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .courses-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .course-accordion {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .course-accordion:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.04);
        }

        .course-header {
            padding: 25px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            background-color: #fff;
            position: relative;
        }

        .course-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 30px;
            right: 30px;
            height: 1px;
            background-color: #f0f0f0;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .course-accordion.active .course-header::after {
            opacity: 1;
        }

        .course-title-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .course-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #fdf2d0, #f8e19c);
            color: #b8860b;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .course-info h3 {
            font-weight: 800;
            font-size: 1.2rem;
            color: #111;
            margin-bottom: 4px;
        }

        .course-info p {
            font-size: 0.85rem;
            color: #666;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-count-badge {
            background-color: #111;
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .toggle-icon {
            color: #888;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .course-accordion.active .toggle-icon {
            transform: rotate(180deg);
        }

        .course-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #fafafa;
        }

        .course-accordion.active .course-body {
            max-height: 2000px; 
        }

        .students-list {
            padding: 10px 30px 30px;
        }

        .student-card {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-top: 15px;
            border: 1px solid #eee;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
        }

        .student-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .student-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
        }

        .student-info h4 {
            font-weight: 700;
            color: #111;
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .student-info p {
            font-size: 0.8rem;
            color: #888;
        }

        .student-join-date {
            font-size: 0.75rem;
            color: #aaa;
            font-weight: 600;
            text-align: right;
        }

        .student-metrics {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 20px;
            background-color: #fdfdfd;
            border-radius: 12px;
            padding: 15px;
            border: 1px solid #f5f5f5;
        }

        .metric-group h5 {
            font-size: 0.75rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .progress-container {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-bar-bg {
            flex-grow: 1;
            height: 8px;
            background-color: #eee;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #d4af37, #f3e5ab);
            border-radius: 4px;
            transition: width 1s ease;
        }

        .progress-text {
            font-size: 0.85rem;
            font-weight: 700;
            color: #333;
            min-width: 40px;
        }

        .last-accessed {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #444;
            font-weight: 600;
        }

        .last-accessed i {
            color: #d4af37;
        }

        .quiz-scores-container {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #eee;
        }

        .quiz-scores-title {
            font-size: 0.75rem;
            color: #888;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .quiz-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .quiz-pill {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-weight: 600;
        }

        .quiz-pill .score {
            background-color: #111;
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 0.75rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    @include('instructor.partials.sidebar')

    <main class="main-content">

        <header class="page-header" style="margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 2.5rem; font-weight: 900; letter-spacing: -1px;">Daftar Siswa</h1>
                <p style="color: #777; font-weight: 500;">Pantau siswa dan kemajuan belajar mereka berdasarkan kursus.</p>
            </div>
        </header>

        <div class="courses-container">
            @forelse($courseData as $course)
                <div class="course-accordion">
                    <div class="course-header" onclick="toggleAccordion(this)">
                        <div class="course-title-section">
                            <div class="course-icon">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div class="course-info">
                                <h3>{{ $course['title'] }}</h3>
                                <p>Kurikulum dan Kuis</p>
                            </div>
                        </div>
                        <div class="header-actions">
                            <span class="student-count-badge">{{ $course['total_students'] }} Siswa</span>
                            <i class="fa-solid fa-chevron-down toggle-icon"></i>
                        </div>
                    </div>

                    <div class="course-body">
                        <div class="students-list">
                            @if($course['total_students'] > 0)
                                @foreach($course['students'] as $student)
                                    <div class="student-card">
                                        <div class="student-card-top">
                                            <div class="student-profile">
                                                <div class="student-avatar"
                                                    style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student['name']) }}&background=f0ede6&color=111');">
                                                </div>
                                                <div class="student-info">
                                                    <h4>{{ $student['name'] }}</h4>
                                                    <p>{{ $student['email'] }}</p>
                                                </div>
                                            </div>
                                            <div class="student-join-date">
                                                Bergabung:<br>
                                                <span style="color: #333;">{{ $student['enroll_date'] }}</span>
                                            </div>
                                        </div>

                                        <div class="student-metrics">
                                            <div class="metric-group">
                                                <h5>Progress Belajar</h5>
                                                <div class="progress-container">
                                                    <div class="progress-bar-bg">
                                                        <div class="progress-bar-fill" style="width: {{ $student['progress'] }}%;"></div>
                                                    </div>
                                                    <span class="progress-text">{{ $student['progress'] }}%</span>
                                                </div>
                                            </div>
                                            
                                            <div class="metric-group">
                                                <h5>Materi Terakhir Diakses</h5>
                                                <div class="last-accessed">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                    <span>{{ $student['last_material'] }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        @if(count($student['quiz_scores']) > 0)
                                            <div class="quiz-scores-container">
                                                <div class="quiz-scores-title">Nilai Kuis Tertinggi</div>
                                                <div class="quiz-pills">
                                                    @foreach($student['quiz_scores'] as $quizScore)
                                                        <div class="quiz-pill">
                                                            <span class="title">{{ Str::limit($quizScore['title'], 20) }}</span>
                                                            <span class="score">{{ $quizScore['score'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-graduate"></i>
                                    <h4 style="font-weight: 700; color: #444; margin-bottom: 5px;">Belum ada siswa</h4>
                                    <p style="font-size: 0.9rem;">Belum ada siswa yang mendaftar di kursus ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 50px; background: #fff; border-radius: 20px;">
                    <i class="fa-solid fa-folder-open" style="font-size: 4rem; color: #eee; margin-bottom: 20px;"></i>
                    <h3 style="font-weight: 800; color: #333;">Belum Ada Kursus</h3>
                    <p style="color: #777;">Anda belum membuat kursus satupun.</p>
                </div>
            @endforelse
        </div>

    </main>

    <script>
        function toggleAccordion(element) {
            const accordion = element.closest('.course-accordion');

            accordion.classList.toggle('active');
        }
    </script>
</body>

</html>