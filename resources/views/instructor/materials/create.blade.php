<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelajaran Baru - Eldemy</title>
    <!-- Fonts already in dashboard.css but let's keep the link just in case -->
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

        .file-upload-wrapper {
            background: var(--white);
            padding: 15px;
            border-radius: 15px;
            border: 2px dashed rgba(0, 0, 0, 0.1);
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .file-upload-wrapper:hover {
            border-color: var(--pink-card);
        }

        .file-upload-wrapper input[type="file"] {
            width: 100%;
            font-size: 0.9rem;
            color: var(--gray-text);
            font-weight: 600;
        }

        .file-upload-wrapper input[type="file"]::file-selector-button {
            background: var(--pink-card);
            color: var(--dark);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            margin-right: 15px;
            transition: 0.2s;
            font-family: "Plus Jakarta Sans", sans-serif;
        }

        .file-upload-wrapper input[type="file"]::file-selector-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .btn-add-pdf {
            background: rgba(255, 142, 142, 0.2);
            color: #d14949;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-add-pdf:hover {
            background: rgba(255, 142, 142, 0.4);
        }

        .btn-remove-pdf {
            background: #fee;
            color: #c00;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-remove-pdf:hover {
            background: #fcc;
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

        .media-box {
            background-color: var(--bg-body);
            padding: 30px;
            border-radius: 25px;
            margin-bottom: 20px;
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
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kurikulum
                </a>
                <h1 style="font-size: 2.2rem; font-weight: 900; margin-bottom: 5px; color: var(--dark);">Tambah Pelajaran Baru</h1>
                <p style="color: var(--gray-text); font-weight: 600; font-size: 1.05rem;">Kursus: {{ $course->title }}</p>
            </div>
        </header>

        <div class="form-card" style="max-width: 900px; margin: 0 auto;">

            @if($errors->any())
                <div style="background-color: #fee; color: #c00; padding: 20px; border-radius: 15px; margin-bottom: 30px; border: 1px solid #fcc;">
                    <ul style="margin: 0; padding-left: 20px; font-weight: 600; font-family: 'Manrope', sans-serif;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('instructor.materials.store', $course->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Judul Pelajaran *</label>
                    <input type="text" name="title" class="form-control" required
                        placeholder="Contoh: Pengenalan Dasar UI/UX">
                </div>

                <div class="form-group" style="margin-bottom: 35px;">
                    <label class="form-label">Deskripsi Singkat (Opsional)</label>
                    <textarea name="description" class="form-control" style="min-height: 100px; resize: vertical;"
                        placeholder="Apa yang akan dipelajari di sesi ini?"></textarea>
                </div>

                <div style="text-align: center; margin: 40px 0;">
                    <span style="background: var(--yellow); color: var(--dark); padding: 5px 15px; border-radius: 20px; font-weight: 800; font-size: 0.8rem; letter-spacing: 1px; font-family: 'Plus Jakarta Sans', sans-serif;">MATERI BELAJAR</span>
                    <p style="font-size: 0.95rem; color: var(--gray-text); font-weight: 600; margin-top: 10px;">
                        Isi salah satu atau keduanya sekaligus
                    </p>
                </div>

                <div class="media-box">
                    <label class="form-label" style="color: var(--dark); font-size: 1.1rem;"><i class="fa-brands fa-youtube" style="color: #FF0000; margin-right: 8px;"></i> Tautkan Video YouTube</label>
                    <p style="font-size: 0.85rem; color: var(--gray-text); margin-bottom: 15px; font-weight: 500;">Masukkan URL video YouTube atau materi MP4 Anda.</p>
                    <input type="url" name="video_url" class="form-control" style="background: var(--white);"
                        placeholder="https://youtube.com/...">
                </div>

                <div class="media-box" style="background-color: rgba(255, 142, 142, 0.05);">
                    <label class="form-label" style="color: var(--dark); font-size: 1.1rem;"><i class="fa-solid fa-file-pdf" style="color: var(--pink-card); margin-right: 8px;"></i> Unggah Modul PDF</label>
                    <p style="font-size: 0.85rem; color: var(--gray-text); margin-bottom: 15px; font-weight: 500;">Unggah file langsung dari perangkat Anda (Maks: 15MB per file).</p>
                    
                    <div id="pdf-container">
                        <div class="file-upload-wrapper">
                            <input type="file" name="pdf_file[]" accept=".pdf">
                        </div>
                    </div>
                    
                    <button type="button" class="btn-add-pdf" onclick="addPdfField()">
                        <i class="fa-solid fa-plus"></i> Tambah Modul PDF Lainnya
                    </button>
                </div>

                <div style="margin-top: 40px;">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-check"></i> Simpan Pelajaran Ini
                    </button>
                </div>
            </form>

        </div>
    </main>

    <script>
        function addPdfField() {
            const container = document.getElementById('pdf-container');
            
            const wrapper = document.createElement('div');
            wrapper.className = 'file-upload-wrapper';
            
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'pdf_file[]';
            input.accept = '.pdf';
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove-pdf';
            removeBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
            removeBtn.onclick = function() {
                container.removeChild(wrapper);
            };
            
            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            
            container.appendChild(wrapper);
        }
    </script>
</body>

</html>