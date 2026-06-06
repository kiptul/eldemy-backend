<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kuis - Eldemy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <style>
        .page-header {
            background-color: var(--white);
            padding: 30px 40px;
            border-radius: var(--radius-lg);
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .form-card {
            background-color: var(--white);
            padding: 40px;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .form-label {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-weight: 700;
            color: var(--dark);
            font-size: 0.95rem;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            background-color: var(--bg-body);
            border: 2px solid transparent;
            padding: 15px 20px;
            border-radius: 15px;
            font-family: "Manrope", sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--dark);
            transition: all 0.2s;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--yellow);
            background-color: var(--white);
            outline: none;
            box-shadow: 0 4px 15px rgba(240, 180, 41, 0.1);
        }

        .question-card {
            background: var(--bg-body);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            position: relative;
            border: 2px solid transparent;
            transition: 0.2s;
        }

        .question-card:hover {
            border-color: rgba(240, 180, 41, 0.3);
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-weight: 800;
            font-family: "Plus Jakarta Sans", sans-serif;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .btn-remove-q {
            background: #fee;
            color: #c00;
            border: none;
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
            font-family: "Plus Jakarta Sans", sans-serif;
        }

        .btn-remove-q:hover {
            background: #fcc;
            transform: translateY(-1px);
        }

        .option-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }

        .option-label {
            font-weight: 800;
            font-family: "Plus Jakarta Sans", sans-serif;
            background: var(--white);
            color: var(--dark);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            flex-shrink: 0;
        }

        .correct-answer-select {
            width: 100%;
            padding: 15px 20px;
            border-radius: 15px;
            border: 2px solid transparent;
            background: var(--white);
            font-weight: 700;
            font-family: "Manrope", sans-serif;
            color: var(--dark);
            outline: none;
            transition: 0.2s;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .correct-answer-select:focus {
            border-color: var(--yellow);
        }

        .btn-submit {
            background: var(--dark);
            color: var(--white);
            border: none;
            padding: 18px 30px;
            border-radius: 20px;
            font-weight: 800;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 1.05rem;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .btn-submit:hover {
            background: var(--yellow);
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 180, 41, 0.3);
        }

        .btn-add-q {
            width: 100%;
            background: var(--white);
            border: 2px dashed rgba(0,0,0,0.1);
            color: var(--gray-text);
            padding: 20px;
            border-radius: 20px;
            font-weight: 800;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 40px;
            transition: 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-add-q:hover {
            border-color: var(--yellow);
            color: var(--dark);
            background: rgba(240, 180, 41, 0.05);
        }

        .settings-row {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px dashed rgba(0,0,0,0.05);
        }

        .settings-row .form-group {
            margin-bottom: 0;
        }
    </style>
</head>

<body>

    @include('instructor.partials.sidebar')

    <main class="main-content">

        <header class="page-header">
            <div>
                <a href="{{ route('instructor.materials.index', $course->id) }}"
                    style="color: var(--gray-text); text-decoration: none; font-size: 0.95rem; font-weight: 700; margin-bottom: 15px; display: inline-block;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Kurikulum
                </a>
                <h1 style="font-size: 2.2rem; font-weight: 900; margin-bottom: 5px; color: var(--dark);">Kelola Kuis</h1>
                <p style="color: var(--gray-text); font-weight: 600; font-size: 1.05rem;">Materi: {{ $material->title }}</p>
            </div>
        </header>

        <div class="form-card" style="max-width: 900px; margin: 0 auto;">

            @if(session('success'))
                <div style="background-color: #e6f4ea; color: #1e8e3e; padding: 20px; border-radius: 15px; margin-bottom: 30px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;">
                    <i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('instructor.quizzes.store', [$course->id, $material->id]) }}" method="POST"
                id="quizForm">
                @csrf

                <div class="settings-row">
                    <div class="form-group" style="flex: 3;">
                        <label class="form-label">Judul Kuis *</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ $quiz->title ?? 'Kuis: ' . $material->title }}" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Durasi (Menit) *</label>
                        <input type="number" name="duration" class="form-control" value="{{ $quiz->duration ?? 5 }}"
                            min="1" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Minimal Kelulusan (%)</label>
                        <input type="number" name="min_score" class="form-control" value="{{ $quiz->min_score ?? 70 }}"
                            min="0" max="100">
                    </div>
                </div>

                <div id="questionsContainer">
                </div>

                <button type="button" class="btn-add-q" onclick="addQuestion()">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Pertanyaan Baru
                </button>

                <div style="display: flex; gap: 15px; align-items: center;">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Seluruh Kuis
                    </button>
                </div>
            </form>

            @if(isset($quiz))
                <form action="{{ route('instructor.quizzes.destroy', [$course->id, $material->id]) }}" method="POST"
                    style="margin-top: 40px; border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 30px; text-align: center;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh kuis ini secara permanen?')"
                        style="background: transparent; border: none; color: #c00; font-weight: 800; font-size: 0.95rem; cursor: pointer; transition: 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;" onmouseover="this.style.color='#f00'" onmouseout="this.style.color='#c00'">
                        <i class="fa-solid fa-trash-can" style="margin-right: 5px;"></i> Hapus Seluruh Kuis
                    </button>
                </form>
            @endif

        </div>
    </main>

    <script>
        let questionCount = 0;
        const container = document.getElementById('questionsContainer');

        function generateQuestionHTML(index, data = null) {
            const qText = data ? data.question : '';
            const optA = data ? data.options[0] : '';
            const optB = data ? data.options[1] : '';
            const optC = data ? data.options[2] : '';
            const optD = data ? data.options[3] : '';
            const correct = data ? data.correct_answer : 'A';

            return `
            <div class="question-card" id="q-card-${index}">
                <div class="question-header">
                    <span><i class="fa-solid fa-circle-question" style="color: var(--yellow); margin-right: 8px;"></i> Pertanyaan #${index + 1}</span>
                    <button type="button" class="btn-remove-q" onclick="removeQuestion(${index})"><i class="fa-solid fa-xmark"></i> Hapus</button>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <input type="text" name="questions[${index}][text]" class="form-control" style="background: var(--white); font-weight: 700;" placeholder="Tuliskan pertanyaan di sini..." value="${qText}" required>
                </div>

                <div class="option-row">
                    <span class="option-label">A</span>
                    <input type="text" name="questions[${index}][option_a]" class="form-control" placeholder="Pilihan Jawaban A" value="${optA}" required>
                </div>
                <div class="option-row">
                    <span class="option-label">B</span>
                    <input type="text" name="questions[${index}][option_b]" class="form-control" placeholder="Pilihan Jawaban B" value="${optB}" required>
                </div>
                <div class="option-row">
                    <span class="option-label">C</span>
                    <input type="text" name="questions[${index}][option_c]" class="form-control" placeholder="Pilihan Jawaban C" value="${optC}" required>
                </div>
                <div class="option-row">
                    <span class="option-label">D</span>
                    <input type="text" name="questions[${index}][option_d]" class="form-control" placeholder="Pilihan Jawaban D" value="${optD}" required>
                </div>

                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px dashed rgba(0,0,0,0.05);">
                    <label class="form-label" style="color: var(--dark);"><i class="fa-solid fa-key" style="color: #4CAF50; margin-right: 5px;"></i> Kunci Jawaban Benar:</label>
                    <select name="questions[${index}][correct]" class="correct-answer-select">
                        <option value="A" ${correct === 'A' ? 'selected' : ''}>Pilihan A</option>
                        <option value="B" ${correct === 'B' ? 'selected' : ''}>Pilihan B</option>
                        <option value="C" ${correct === 'C' ? 'selected' : ''}>Pilihan C</option>
                        <option value="D" ${correct === 'D' ? 'selected' : ''}>Pilihan D</option>
                    </select>
                </div>
            </div>`;
        }

        function addQuestion() {
            container.insertAdjacentHTML('beforeend', generateQuestionHTML(questionCount));
            questionCount++;
        }

        function removeQuestion(index) {
            document.getElementById(`q-card-${index}`).remove();
        }

        const existingQuiz = @json($quiz);

        if (existingQuiz && existingQuiz.questions && existingQuiz.questions.length > 0) {
            existingQuiz.questions.forEach((qData) => {
                container.insertAdjacentHTML('beforeend', generateQuestionHTML(questionCount, qData));
                questionCount++;
            });
        } else {
            addQuestion();
        }
    </script>
</body>

</html>