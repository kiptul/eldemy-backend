<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use App\Models\Quiz;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $instructor = User::create([
            'name' => 'Budi Instruktur',
            'email' => 'budi@marketplace.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
        ]);

        User::create([
            'name' => 'Siswa Rajin',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        $this->call([
            RealisticCourseSeeder::class,
        ]);

        Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Mastering Angular & Ionic',
            'description' => 'Pelajari cara membuat aplikasi mobile lintas platform dengan mudah.',
            'base_price' => 150000,
        ]);

        Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Backend API dengan Laravel',
            'description' => 'Kursus lengkap membangun API yang handal dan aman.',
            'base_price' => 200000,
        ]);

        $course1 = Course::where('title', 'Mastering Angular & Ionic')->first();

        if ($course1) {
            Quiz::create([
                'course_id' => $course1->id,
                'title' => 'Evaluasi Akhir Angular & Ionic',
                'questions' => [
                    [
                        'question' => 'Apa kegunaan utama dari framework Ionic?',
                        'options' => [
                            'Membuat backend API',
                            'Membuat aplikasi mobile lintas platform',
                            'Mengelola database',
                            'Membuat desain UI murni'
                        ],
                        'correct_answer' => 'Membuat aplikasi mobile lintas platform'
                    ],
                    [
                        'question' => 'Perintah apa yang digunakan untuk menjalankan server lokal di Angular/Ionic?',
                        'options' => ['ionic build', 'ionic serve', 'ng deploy', 'php artisan serve'],
                        'correct_answer' => 'ionic serve'
                    ]
                ]
            ]);
        }
    }
}