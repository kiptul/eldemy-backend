<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Hash;

class RealisticCourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::firstOrCreate(
            ['email' => 'budi.uiux@eldemy.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
            ]
        );
        $course1 = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Mastering Tactile Visual Hierarchies',
            'description' => 'Pelajari rahasia membuat desain UI yang terasa nyata dan taktil. Kursus ini membahas psikologi warna, aturan "Tanpa Garis", hingga menyusun kurasi elemen visual layaknya editor majalah profesional.',
            'category' => 'UI/UX Design',
            'thumbnail' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=1000&auto=format&fit=crop', // Foto desain estetik dari Unsplash
            'base_price' => 20000,
        ]);

        $curriculum1 = [
            ['title' => '01. The Curator\'s Mindset', 'type' => 'video', 'duration' => '12:00', 'content' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => '02. Color & Emotional Resonance', 'type' => 'video', 'duration' => '15:45', 'content' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => '03. Mastering Tactile Visual Hierarchies', 'type' => 'video', 'duration' => '18:30', 'content' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => '04. The "No-Line" Rule Practice', 'type' => 'module', 'duration' => '22:15', 'content' => '<p>Aturan tanpa garis mengajarkan kita untuk memisahkan elemen UI menggunakan <strong>Whitespace</strong> (ruang kosong) dan <strong>Tipografi</strong>, bukan garis pembatas yang kaku.</p>'],
            [
                'title' => '05. Final Editorial Project',
                'type' => 'quiz',
                'duration' => '45:00',
                'content' => json_encode([
                    "questions" => [
                        ["question" => "Apa fungsi utama dari whitespace dalam desain taktil?", "options" => ["Memberi ruang bernapas", "Membuat desain terlihat kosong", "Menghemat tinta", "Tidak ada fungsi"], "answer" => "Memberi ruang bernapas"]
                    ]
                ])
            ],
        ];

        foreach ($curriculum1 as $index => $item) {
            CourseMaterial::create([
                'course_id' => $course1->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'duration' => $item['duration'],
                'content' => $item['content'],
                'order' => $index + 1,
            ]);
        }


        $course2 = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Fullstack Laravel & Ionic Framework',
            'description' => 'Mulai perjalanan Anda menjadi Software Engineer. Bangun aplikasi E-Learning lintas platform dengan Backend Laravel dan Frontend Ionic Mobile dari nol hingga siap rilis (Production).',
            'category' => 'Programming',
            'thumbnail' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=1000&auto=format&fit=crop', // Foto coding dari Unsplash
            'base_price' => 25000,
        ]);

        $curriculum2 = [
            ['title' => '01. Pengenalan Arsitektur API', 'type' => 'video', 'duration' => '10:00', 'content' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => '02. Setup Database & Migrations', 'type' => 'video', 'duration' => '25:00', 'content' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => '03. Modul Kumpulan Perintah Artisan', 'type' => 'module', 'duration' => '15:00', 'content' => '<ul><li><code>php artisan migrate</code>: Menjalankan migrasi</li><li><code>php artisan serve</code>: Menjalankan server lokal</li></ul>'],
            [
                'title' => '04. Kuis Pemahaman Routing',
                'type' => 'quiz',
                'duration' => '20:00',
                'content' => json_encode([
                    "questions" => [
                        ["question" => "Method HTTP yang digunakan untuk mengirim data rahasia/form adalah?", "options" => ["GET", "POST", "PUT", "DELETE"], "answer" => "POST"]
                    ]
                ])
            ],
        ];

        foreach ($curriculum2 as $index => $item) {
            CourseMaterial::create([
                'course_id' => $course2->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'duration' => $item['duration'],
                'content' => $item['content'],
                'order' => $index + 1,
            ]);
        }
    }
}