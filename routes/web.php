<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\InstructorDashboardController;
use App\Http\Controllers\Web\CourseManagementController;
use App\Http\Controllers\Web\CourseMaterialController;
use App\Http\Controllers\Web\QuizManagementController;
use App\Http\Controllers\Web\ForgotPasswordController;

use App\Http\Controllers\Web\LandingPageController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/checkout/{courseId}', [LandingPageController::class, 'checkout'])->name('web.checkout');

// Halaman Publik (tanpa login) — untuk verifikasi iPaymu
Route::get('/terms', function () {
    return view('public.terms');
})->name('terms');

Route::get('/refund-policy', function () {
    return view('public.refund-policy');
})->name('refund-policy');

Route::get('/faq', function () {
    return view('public.faq');
})->name('faq');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');

Route::middleware(['auth', \App\Http\Middleware\IsInstructor::class])->group(function () {

    Route::prefix('instructor')->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('instructor.dashboard');
        Route::post('/set-username', [AuthController::class, 'setUsername'])->name('set.username');

        Route::get('/courses', [CourseManagementController::class, 'index'])->name('instructor.courses.index');
        Route::get('/courses/create', [CourseManagementController::class, 'create'])->name('instructor.courses.create');
        Route::post('/courses', [CourseManagementController::class, 'store'])->name('instructor.courses.store');
        Route::get('/courses/{id}/edit', [CourseManagementController::class, 'edit'])->name('instructor.courses.edit');
        Route::put('/courses/{id}', [CourseManagementController::class, 'update'])->name('instructor.courses.update');
        Route::delete('/courses/{id}', [CourseManagementController::class, 'destroy'])->name('instructor.courses.destroy');

        Route::get('/courses/{course_id}/materials', [CourseMaterialController::class, 'index'])->name('instructor.materials.index');
        Route::get('/courses/{course_id}/materials/create', [CourseMaterialController::class, 'create'])->name('instructor.materials.create');
        Route::post('/courses/{course_id}/materials', [CourseMaterialController::class, 'store'])->name('instructor.materials.store');
        Route::get('/courses/{course_id}/materials/{material_id}/edit', [CourseMaterialController::class, 'edit'])->name('instructor.materials.edit');
        Route::put('/courses/{course_id}/materials/{material_id}', [CourseMaterialController::class, 'update'])->name('instructor.materials.update');
        Route::delete('/courses/{course_id}/materials/{material_id}', [CourseMaterialController::class, 'destroy'])->name('instructor.materials.destroy');
        Route::delete('/courses/{course_id}/materials/{material_id}/module/{index}', [CourseMaterialController::class, 'deleteModule'])->name('instructor.materials.deleteModule');
        Route::delete('/courses/{course_id}/materials/{material_id}/video/{index}', [CourseMaterialController::class, 'deleteVideo'])->name('instructor.materials.deleteVideo');

        Route::get('/courses/{course_id}/materials/{material_id}/quiz', [QuizManagementController::class, 'index'])->name('instructor.quizzes.index');
        Route::post('/courses/{course_id}/materials/{material_id}/quiz', [QuizManagementController::class, 'storeOrUpdate'])->name('instructor.quizzes.store');
        Route::delete('/courses/{course_id}/materials/{material_id}/quiz', [QuizManagementController::class, 'destroy'])->name('instructor.quizzes.destroy');

        Route::get('/students', [\App\Http\Controllers\Web\InstructorStudentController::class, 'index'])->name('instructor.students.index');
    });

});

