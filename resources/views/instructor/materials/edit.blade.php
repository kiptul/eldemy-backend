<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelajaran - Eldemy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* ── Section Divider ── */
        .section-divider {
            text-align: center;
            margin: 40px 0 30px;
        }
        .section-badge {
            background: var(--yellow);
            color: var(--dark);
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: inline-block;
        }
        .section-subtitle {
            font-size: 0.9rem;
            color: var(--gray-text);
            font-weight: 600;
            margin-top: 10px;
        }

        /* ── Media Box ── */
        .media-box {
            background-color: var(--bg-body);
            padding: 28px;
            border-radius: 25px;
            margin-bottom: 24px;
        }
        .media-box-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .media-box-header h3 {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--dark);
            margin: 0;
        }
        .media-box-desc {
            font-size: 0.82rem;
            color: var(--gray-text);
            font-weight: 500;
            margin-bottom: 18px;
        }

        /* ── Existing Item Card ── */
        .existing-item {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--white);
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 10px;
            border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.2s;
        }
        .existing-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            transform: translateY(-1px);
        }
        .existing-item .item-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .item-icon.pdf-icon {
            background: rgba(255, 142, 142, 0.15);
            color: #e74c3c;
        }
        .item-icon.video-icon {
            background: rgba(255, 0, 0, 0.08);
            color: #FF0000;
        }
        .existing-item .item-info {
            flex: 1;
            min-width: 0;
        }
        .existing-item .item-title {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .existing-item .item-meta {
            font-size: 0.75rem;
            color: var(--gray-text);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-delete-item {
            background: #fee;
            color: #c00;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: 0.2s;
            flex-shrink: 0;
        }
        .btn-delete-item:hover {
            background: #fcc;
            transform: scale(1.05);
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: var(--gray-text);
            font-weight: 600;
            font-size: 0.85rem;
        }
        .empty-state i {
            display: block;
            font-size: 1.8rem;
            margin-bottom: 8px;
            opacity: 0.3;
        }

        /* ── Add New Input Row ── */
        .add-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .add-row .input-col {
            flex: 1;
            min-width: 0;
        }
        .add-row .form-control {
            background: var(--white);
            font-size: 0.88rem;
            padding: 12px 16px;
        }
        .add-row .form-control-sm {
            padding: 12px 16px;
            font-size: 0.85rem;
            margin-bottom: 6px;
        }

        .file-upload-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .file-upload-row .input-col {
            flex: 1;
            min-width: 0;
        }
        .file-upload-wrapper {
            background: var(--white);
            padding: 10px 14px;
            border-radius: 14px;
            border: 2px dashed rgba(0, 0, 0, 0.08);
            transition: 0.2s;
        }
        .file-upload-wrapper:hover {
            border-color: var(--pink-card);
        }
        .file-upload-wrapper input[type="file"] {
            width: 100%;
            font-size: 0.82rem;
            color: var(--gray-text);
            font-weight: 600;
        }
        .file-upload-wrapper input[type="file"]::file-selector-button {
            background: var(--pink-card);
            color: var(--dark);
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            margin-right: 10px;
            transition: 0.2s;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 0.8rem;
        }
        .file-upload-wrapper input[type="file"]::file-selector-button:hover {
            opacity: 0.9;
        }

        .btn-add-new {
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
        }
        .btn-add-video {
            background: rgba(255, 0, 0, 0.08);
            color: #c00;
        }
        .btn-add-video:hover { background: rgba(255, 0, 0, 0.15); }
        .btn-add-pdf {
            background: rgba(255, 142, 142, 0.2);
            color: #d14949;
        }
        .btn-add-pdf:hover { background: rgba(255, 142, 142, 0.4); }

        .btn-remove-row {
            background: #fee;
            color: #c00;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: 0.2s;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .btn-remove-row:hover { background: #fcc; }

        /* ── Buttons ── */
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
            padding: 16px 40px;
        }
        .btn-submit:hover {
            background: var(--yellow);
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 180, 41, 0.3);
        }

        /* ── Alert Toast ── */
        .alert-success {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
            padding: 16px 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(46, 125, 50, 0.15);
        }
        .alert-error {
            background-color: #fee;
            color: #c00;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 24px;
            border: 1px solid #fcc;
        }
        .alert-error ul {
            margin: 0;
            padding-left: 20px;
            font-weight: 600;
            font-family: 'Manrope', sans-serif;
        }

        /* ── Danger Zone ── */
        .danger-zone {
            margin-top: 40px;
            border-top: 1px dashed rgba(0,0,0,0.1);
            padding-top: 30px;
            text-align: center;
        }
        .btn-danger-text {
            background: transparent;
            border: none;
            color: #c00;
            font-weight: 800;
            font-size: 0.92rem;
            cursor: pointer;
            transition: 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-danger-text:hover { color: #f00; }
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
                <h1 style="font-size: 2.2rem; font-weight: 900; margin-bottom: 5px; color: var(--dark);">Edit Pelajaran</h1>
                <p style="color: var(--gray-text); font-weight: 600; font-size: 1.05rem;">Kursus: {{ $course->title }}</p>
            </div>
        </header>

        <div class="form-card" style="max-width: 900px; margin: 0 auto;">

            {{-- Success Alert --}}
            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Error Alert --}}
            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $content = json_decode($material->content, true) ?? [];

                // Normalize to new format
                $videos = $content['videos'] ?? [];
                $modules = $content['modules'] ?? [];

                // Backward compat: migrate old format
                if (empty($videos) && !empty($content['video_url'])) {
                    $videos = [['title' => 'Video Pembelajaran', 'url' => $content['video_url']]];
                }
                if (empty($modules)) {
                    $pdfPaths = $content['pdf_paths'] ?? [];
                    if (empty($pdfPaths) && !empty($content['pdf_path'])) {
                        $pdfPaths = [$content['pdf_path']];
                    }
                    foreach ($pdfPaths as $i => $path) {
                        $modules[] = ['title' => 'Modul ' . ($i + 1), 'path' => $path];
                    }
                }
            @endphp

            {{-- ══════════════════════════════════════════ --}}
            {{-- FORM: Title & Description                 --}}
            {{-- ══════════════════════════════════════════ --}}
            <form action="{{ route('instructor.materials.update', [$course->id, $material->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label">Judul Pelajaran *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $material->title) }}" required>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label class="form-label">Deskripsi Singkat (Opsional)</label>
                    <textarea name="description" class="form-control" style="min-height: 90px; resize: vertical;">{{ old('description', $content['description'] ?? '') }}</textarea>
                </div>

                {{-- ══════════════════════════════════════════ --}}
                {{-- EXISTING MATERIALS                        --}}
                {{-- ══════════════════════════════════════════ --}}
                <div class="section-divider">
                    <span class="section-badge">MATERI SAAT INI</span>
                    <p class="section-subtitle">Kelola video dan modul yang sudah ditambahkan</p>
                </div>

                {{-- ── Existing Videos ── --}}
                <div class="media-box">
                    <div class="media-box-header">
                        <i class="fa-brands fa-youtube" style="color: #FF0000; font-size: 1.2rem;"></i>
                        <h3>Video YouTube</h3>
                        <span style="background: rgba(255,0,0,0.08); color: #c00; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; margin-left: auto;">{{ count($videos) }} video</span>
                    </div>

                    @if(count($videos) > 0)
                        @foreach($videos as $i => $video)
                            <div class="existing-item">
                                <div class="item-icon video-icon">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                                <div class="item-info">
                                    <input type="text" name="old_video_titles[{{ $i }}]" value="{{ $video['title'] ?? 'Video ' . ($i + 1) }}" class="form-control form-control-sm" style="padding: 6px 12px; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; width: 100%; margin-bottom: 4px; background: #f9f9f9;">
                                    <div class="item-meta">{{ $video['url'] ?? '' }}</div>
                                </div>
                                <button type="button" class="btn-delete-item" title="Hapus video" onclick="if(confirm('Hapus video ini?')) { document.getElementById('delete-video-{{ $i }}').submit(); }">
                                    <i class="fa-solid fa-trash-can" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fa-solid fa-video-slash"></i>
                            Belum ada video yang ditambahkan
                        </div>
                    @endif
                </div>

                {{-- ── Existing Modules ── --}}
                <div class="media-box" style="background-color: rgba(255, 142, 142, 0.04);">
                    <div class="media-box-header">
                        <i class="fa-solid fa-file-pdf" style="color: var(--pink-card); font-size: 1.1rem;"></i>
                        <h3>Modul PDF</h3>
                        <span style="background: rgba(255,142,142,0.15); color: #d14949; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; margin-left: auto;">{{ count($modules) }} modul</span>
                    </div>

                    @if(count($modules) > 0)
                        @foreach($modules as $i => $mod)
                            <div class="existing-item">
                                <div class="item-icon pdf-icon">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div class="item-info">
                                    <input type="text" name="old_pdf_titles[{{ $i }}]" value="{{ $mod['title'] ?? 'Modul ' . ($i + 1) }}" class="form-control form-control-sm" style="padding: 6px 12px; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; width: 100%; margin-bottom: 4px; background: #f9f9f9;">
                                    <div class="item-meta">{{ basename($mod['path'] ?? '') }}</div>
                                </div>
                                <a href="{{ asset('storage/' . ($mod['path'] ?? '')) }}" target="_blank"
                                    style="width:36px;height:36px;border-radius:10px;background:rgba(240,180,41,0.12);color:var(--yellow);display:flex;align-items:center;justify-content:center;text-decoration:none;flex-shrink:0;transition:0.2s;"
                                    title="Lihat PDF">
                                    <i class="fa-solid fa-eye" style="font-size:0.8rem;"></i>
                                </a>
                                <button type="button" class="btn-delete-item" title="Hapus modul" onclick="if(confirm('Hapus modul PDF ini? File akan dihapus permanen.')) { document.getElementById('delete-module-{{ $i }}').submit(); }">
                                    <i class="fa-solid fa-trash-can" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fa-solid fa-file-circle-xmark"></i>
                            Belum ada modul PDF yang diunggah
                        </div>
                    @endif
                </div>

                {{-- ══════════════════════════════════════════ --}}
                {{-- ADD NEW MATERIALS                         --}}
                {{-- ══════════════════════════════════════════ --}}
                <div class="section-divider">
                    <span class="section-badge">TAMBAH MATERI BARU</span>
                    <p class="section-subtitle">Tambahkan video YouTube atau modul PDF baru</p>
                </div>

                {{-- ── Add New Videos ── --}}
                <div class="media-box">
                    <div class="media-box-header">
                        <i class="fa-brands fa-youtube" style="color: #FF0000; font-size: 1.2rem;"></i>
                        <h3>Tambah Video YouTube</h3>
                    </div>
                    <p class="media-box-desc">Masukkan judul kustom dan URL video YouTube.</p>

                    <div id="video-container">
                        <div class="add-row">
                            <div class="input-col">
                                <input type="text" name="video_titles[]" class="form-control form-control-sm" placeholder="Judul video (mis: Pengantar Materi)">
                                <input type="url" name="video_urls[]" class="form-control form-control-sm" placeholder="https://youtube.com/watch?v=...">
                            </div>
                            <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Hapus">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn-add-new btn-add-video" onclick="addVideoRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Video Lainnya
                    </button>
                </div>

                {{-- ── Add New PDFs ── --}}
                <div class="media-box" style="background-color: rgba(255, 142, 142, 0.04);">
                    <div class="media-box-header">
                        <i class="fa-solid fa-file-pdf" style="color: var(--pink-card); font-size: 1.1rem;"></i>
                        <h3>Tambah Modul PDF</h3>
                    </div>
                    <p class="media-box-desc">Unggah file PDF dengan judul kustom (Maks: 15MB per file).</p>

                    <div id="pdf-container">
                        <div class="file-upload-row">
                            <div class="input-col">
                                <input type="text" name="pdf_titles[]" class="form-control form-control-sm" placeholder="Judul modul (mis: BAB 1 - Pendahuluan)">
                                <div class="file-upload-wrapper">
                                    <input type="file" name="pdf_file[]" accept=".pdf">
                                </div>
                            </div>
                            <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Hapus">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn-add-new btn-add-pdf" onclick="addPdfRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Modul PDF Lainnya
                    </button>
                </div>

                </div>

                {{-- ── Submit ── --}}
                <div style="display: flex; gap: 15px; align-items: center; margin-top: 40px; justify-content: center;">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('instructor.materials.index', $course->id) }}" style="color: var(--gray-text); font-weight: 700; text-decoration: none; padding: 15px 20px; font-family: 'Plus Jakarta Sans', sans-serif;">Batal</a>
                </div>
            </form>

            {{-- Hidden forms for delete actions (MUST BE OUTSIDE MAIN FORM) --}}
            @if(count($videos) > 0)
                @foreach($videos as $i => $video)
                    <form id="delete-video-{{ $i }}" action="{{ route('instructor.materials.deleteVideo', [$course->id, $material->id, $i]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif

            @if(count($modules) > 0)
                @foreach($modules as $i => $mod)
                    <form id="delete-module-{{ $i }}" action="{{ route('instructor.materials.deleteModule', [$course->id, $material->id, $i]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif

            {{-- ── Danger Zone ── --}}
            <div class="danger-zone">
                <form action="{{ route('instructor.materials.destroy', [$course->id, $material->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-text"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh pelajaran ini secara permanen? Semua video dan modul di dalamnya akan ikut terhapus.')">
                        <i class="fa-solid fa-trash-can" style="margin-right: 5px;"></i> Hapus Seluruh Pelajaran Ini
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script>
        function addVideoRow() {
            const container = document.getElementById('video-container');
            const row = document.createElement('div');
            row.className = 'add-row';
            row.innerHTML = `
                <div class="input-col">
                    <input type="text" name="video_titles[]" class="form-control form-control-sm" placeholder="Judul video (mis: Pengantar Materi)">
                    <input type="url" name="video_urls[]" class="form-control form-control-sm" placeholder="https://youtube.com/watch?v=...">
                </div>
                <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Hapus">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;
            container.appendChild(row);
        }

        function addPdfRow() {
            const container = document.getElementById('pdf-container');
            const row = document.createElement('div');
            row.className = 'file-upload-row';
            row.innerHTML = `
                <div class="input-col">
                    <input type="text" name="pdf_titles[]" class="form-control form-control-sm" placeholder="Judul modul (mis: BAB 1 - Pendahuluan)">
                    <div class="file-upload-wrapper">
                        <input type="file" name="pdf_file[]" accept=".pdf">
                    </div>
                </div>
                <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Hapus">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;
            container.appendChild(row);
        }

        function removeRow(btn) {
            const row = btn.closest('.add-row, .file-upload-row');
            const container = row.parentElement;
            // Keep at least one row
            if (container.children.length > 1) {
                row.remove();
            } else {
                // Clear the inputs instead of removing
                row.querySelectorAll('input').forEach(input => {
                    if (input.type === 'file') {
                        input.value = '';
                    } else {
                        input.value = '';
                    }
                });
            }
        }
    </script>
</body>

</html>