<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kursus Utama - Eldemy Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/curriculum.css') }}">

    <style>
        .categories-row-banner {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #111;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .pill-banner {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            border-radius: 20px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            opacity: 0.7;
            white-space: nowrap;
        }

        .pill-banner:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .pill-banner.active {
            border-color: #111;
            opacity: 1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Warna Kategori Default */
        .cat-prog {
            background-color: #e0f7fa;
            color: #00838f;
        }

        .cat-des {
            background-color: #fce4ec;
            color: #c2185b;
        }

        .cat-biz {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .cat-custom {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .cat-input-custom {
            display: none;
            background: rgba(255, 255, 255, 0.8);
            border: 2px dashed #111;
            padding: 8px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 700;
            outline: none;
        }

        .cat-input-custom:focus {
            background: #fff;
            border-style: solid;
        }

        /* Upload File Styling */
        .file-upload-wrapper {
            background: #fff;
            padding: 10px;
            border-radius: 15px;
            border: 2px dashed rgba(17, 17, 17, 0.2);
            text-align: center;
            margin-bottom: 15px;
        }

        .file-upload-wrapper input[type="file"] {
            width: 100%;
            font-size: 0.8rem;
            color: #555;
            cursor: pointer;
        }

        .file-upload-wrapper input[type="file"]::file-selector-button {
            background: #111;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            margin-right: 15px;
            transition: 0.2s;
        }

        .file-upload-wrapper input[type="file"]::file-selector-button:hover {
            background: #333;
        }

        /* Super Bagus Dropdown */
        .custom-dropdown-wrapper {
            position: relative;
            width: 100%;
            max-width: 320px;
            margin-bottom: 20px;
        }

        .custom-dropdown-wrapper label {
            font-size: 0.75rem; 
            color: #777; 
            text-transform: uppercase; 
            display: block; 
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .custom-dropdown-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            width: 100%;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 16px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-dropdown-wrapper select:hover,
        .custom-dropdown-wrapper select:focus {
            background: #ffffff;
            border-color: #111;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            outline: none;
            transform: translateY(-2px);
        }

        .custom-dropdown-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            bottom: 16px;
            right: 20px;
            color: #111;
            pointer-events: none;
            transition: transform 0.3s ease;
            font-size: 1.1rem;
        }

        .custom-dropdown-wrapper:focus-within::after {
            transform: rotate(180deg);
        }
    </style>
</head>

<body>

    @include('instructor.partials.sidebar')

    <main class="main-content">

        <a href="{{ route('instructor.courses.index') }}"
            style="color: #888; text-decoration: none; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kursus
        </a>

        @if(session('success'))
            <div
                style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 15px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fee; color: #c00; padding: 15px; border-radius: 15px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li style="font-size: 0.9rem; font-weight: 600;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('instructor.courses.update', $course->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="course-banner" style="box-shadow: 0 15px 35px rgba(250, 194, 51, 0.2); margin-bottom: 30px;">
                <span class="banner-badge" style="margin-bottom: 25px;">MODE DRAFTING</span>

                <div
                    style="display: flex; justify-content: space-between; align-items: flex-end; gap: 40px; flex-wrap: wrap;">

                    <div style="flex-grow: 1; min-width: 300px;">
                        <input type="text" name="title" class="title-edit" value="{{ $course->title }}" required
                            style="width: 100%; border-bottom-color: rgba(0,0,0,0.1);">
                        <input type="text" name="description" class="desc-edit" value="{{ $course->description }}"
                            placeholder="Tuliskan deskripsi singkat di sini..."
                            style="width: 100%; max-width: 100%; border-bottom-color: rgba(0,0,0,0.1); margin-bottom: 10px;">

                        <div class="custom-dropdown-wrapper">
                            <label>Tingkat Keahlian:</label>
                            <select name="skill_level" required>
                                <option value="SD" {{ $course->skill_level == 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                <option value="SMP" {{ $course->skill_level == 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                <option value="SMA/SMK" {{ $course->skill_level == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK (Sekolah Menengah Atas/Kejuruan)</option>
                                <option value="UMUM" {{ $course->skill_level == 'UMUM' ? 'selected' : '' }}>Umum (Untuk Semua Kalangan)</option>
                            </select>
                        </div>

                        <div class="categories-row-banner">
                            <span style="font-size: 0.75rem; color: #777; text-transform: uppercase;">Kategori:</span>

                            <input type="hidden" name="category" id="selectedCategory"
                                value="{{ $course->category ?? 'Pemrograman' }}">

                            @php
                                $defaultCats = ['Pemrograman', 'Desain', 'Bisnis'];
                                $currentCat = $course->category ?? 'Pemrograman';
                                $isCustom = !in_array($currentCat, $defaultCats);
                            @endphp

                            <div class="pill-banner cat-prog {{ $currentCat == 'Pemrograman' ? 'active' : '' }}"
                                onclick="selectCat(this, 'Pemrograman')">Pemrograman</div>
                            <div class="pill-banner cat-des {{ $currentCat == 'Desain' ? 'active' : '' }}"
                                onclick="selectCat(this, 'Desain')">Desain</div>
                            <div class="pill-banner cat-biz {{ $currentCat == 'Bisnis' ? 'active' : '' }}"
                                onclick="selectCat(this, 'Bisnis')">Bisnis</div>

                            @if($isCustom && !empty($currentCat))
                                <div class="pill-banner cat-custom active" onclick="selectCat(this, '{{ $currentCat }}')">
                                    {{ $currentCat }}
                                </div>
                            @endif

                            <div class="pill-banner" style="background: rgba(0,0,0,0.05);" onclick="showCustomInput()"
                                id="btnCustomCat">+ Kategori Baru</div>
                            <input type="text" id="customCatInput" class="cat-input-custom"
                                placeholder="Ketik kategori..." oninput="syncCustomCat(this)">
                        </div>
                    </div>

                    <button type="submit" class="btn-save-banner"
                        style="padding: 18px 35px; font-size: 1.1rem; border-radius: 20px; white-space: nowrap; box-shadow: 0 10px 25px rgba(0,0,0,0.2); transform: translateY(-5px);">
                        <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

            <div class="curriculum-layout">

                <div class="curriculum-list">
                    <h3 style="display: flex; justify-content: space-between; align-items: center;">Kurikulum
                        Pembelajaran</h3>

                    @foreach($course->materials as $index => $material)
                        <div class="lesson-item type-{{ $material->type }}">
                            <div class="lesson-icon"><i
                                    class="fa-solid {{ $material->type == 'video' ? 'fa-play' : 'fa-file-lines' }}"></i>
                            </div>
                            <div class="lesson-details">
                                <h4>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}. {{ $material->title }}</h4>
                                <p>{{ ucfirst($material->type) }}</p>
                            </div>
                            <div class="lesson-actions">
                                <a href="{{ route('instructor.quizzes.index', [$course->id, $material->id]) }}"
                                    class="btn-quiz"
                                    style="background: rgba(0,0,0,0.05); color: #111; text-decoration: none;"><i
                                        class="fa-solid fa-clipboard-question"></i> Kelola Kuis</a>
                                <a href="{{ route('instructor.materials.edit', [$course->id, $material->id]) }}"
                                    class="btn-quiz"
                                    style="background: rgba(0,0,0,0.05); color: #111; text-decoration: none;"><i
                                        class="fa-solid fa-pen"></i> Edit Materi</a>
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ route('instructor.materials.create', ['course_id' => $course->id, 'type' => 'video']) }}"
                        class="btn-add-lesson" style="display: block; text-align: center; text-decoration: none;"><i
                            class="fa-solid fa-plus"></i> Tambah Pelajaran Baru</a>
                </div>

                <div>
                    <div class="thumbnail-panel">
                        <h4>Thumbnail & Harga</h4>
                        <p style="font-size: 0.8rem; color: #777; margin-bottom: 20px;">Atur visual dan harga kursus
                            Anda.</p>

                        <div class="thumbnail-preview" id="thumbnailPreview">
                            @if(!empty($course->thumbnail))
                                <img src="{{ $course->thumbnail }}" alt="Thumbnail">
                            @else
                                <i class="fa-regular fa-image"></i>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-size: 0.75rem;">Upload Gambar Cover (Opsional)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="thumbnail" id="thumbnailInput"
                                    accept="image/jpeg, image/png, image/webp" onchange="previewUpload(event)">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.75rem;">Set Harga Kursus (Rp) *</label>
                            <div class="input-group-price">
                                <span class="prefix">Rp</span>
                                <input type="number" name="base_price" value="{{ $course->base_price }}" min="0"
                                    required placeholder="Contoh: 150000">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </main>

    <script>
        const hiddenCategoryInput = document.getElementById('selectedCategory');
        const customInput = document.getElementById('customCatInput');
        const btnCustom = document.getElementById('btnCustomCat');
        const pills = document.querySelectorAll('.pill-banner:not(#btnCustomCat)');
        const skillLevelSelect = document.querySelector('select[name="skill_level"]');
        const categoriesContainer = document.querySelector('.categories-row-banner');

        function updateCategoryVisibility() {
            if (skillLevelSelect.value === 'UMUM') {
                categoriesContainer.style.display = 'flex';
            } else {
                categoriesContainer.style.display = 'none';
                hiddenCategoryInput.value = '';
            }
        }

        skillLevelSelect.addEventListener('change', updateCategoryVisibility);
        // Initialize on load
        updateCategoryVisibility();

        function selectCat(element, value) {
            pills.forEach(p => p.classList.remove('active'));
            element.classList.add('active');

            customInput.style.display = 'none';
            btnCustom.style.display = 'block';

            hiddenCategoryInput.value = value;
        }

        function showCustomInput() {
            pills.forEach(p => p.classList.remove('active'));

            btnCustom.style.display = 'none';
            customInput.style.display = 'block';
            customInput.focus();

            hiddenCategoryInput.value = '';
            customInput.value = '';
        }

        function syncCustomCat(input) {
            hiddenCategoryInput.value = input.value;
        }

        function previewUpload(event) {
            const preview = document.getElementById('thumbnailPreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Thumbnail Preview">`;
                    preview.style.border = 'none';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>

</html>