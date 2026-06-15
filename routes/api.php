<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CourseManagementController;
use App\Http\Controllers\Api\CourseMaterialController;
use App\Http\Controllers\Api\QuizManagementController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\NotificationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);

// iPaymu Payment Gateway Callbacks (tanpa auth)
Route::post('/ipaymu/callback', [WebhookController::class, 'handle']);
Route::get('/ipaymu/return', [WebhookController::class, 'returnHandler']);
Route::get('/ipaymu/cancel', [WebhookController::class, 'cancelHandler']);

// Serve Avatar Directly (tanpa auth, untuk diakses tag img)
Route::get('/user/avatar/{filename}', [AuthController::class, 'getAvatar']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/nickname', [AuthController::class, 'updateNickname']);
    Route::post('/user/profile', [AuthController::class, 'updateProfile']);
    Route::delete('/user', [AuthController::class, 'deleteAccount']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    Route::post('/courses/{id}/buy', [PurchaseController::class, 'buy']);

    Route::post('/materials/{id}/complete', [ProgressController::class, 'markAsCompleted']);

    Route::get('/courses/{id}/progress', [ProgressController::class, 'checkProgress']);

    Route::get('/courses/{id}/quiz', [QuizController::class, 'show']);

    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submit']);

    Route::get('/materials/{id}/quiz', [QuizController::class, 'showByMaterial']);

    Route::get('/courses/{id}/certificate', [CertificateController::class, 'show']);

    Route::get('/my-courses', [HistoryController::class, 'myCourses']);
    Route::get('/history/last-learning', [HistoryController::class, 'lastLearning']);

    Route::get('/instructor/dashboard', [DashboardController::class, 'index']);

    Route::get('/instructor/courses', [CourseManagementController::class, 'index']);

    Route::post('/instructor/courses', [CourseManagementController::class, 'store']);

    Route::put('/instructor/courses/{id}', [CourseManagementController::class, 'update']);

    Route::delete('/instructor/courses/{id}', [CourseManagementController::class, 'destroy']);

    Route::get('/instructor/courses/{course_id}/materials', [CourseMaterialController::class, 'index']);

    Route::post('/instructor/courses/{course_id}/materials', [CourseMaterialController::class, 'store']);

    Route::put('/instructor/courses/{course_id}/materials/{material_id}', [CourseMaterialController::class, 'update']);

    Route::delete('/instructor/courses/{course_id}/materials/{material_id}', [CourseMaterialController::class, 'destroy']);

    Route::get('/instructor/courses/{course_id}/quiz', [QuizManagementController::class, 'show']);

    Route::post('/instructor/courses/{course_id}/quiz', [QuizManagementController::class, 'storeOrUpdate']);

    Route::delete('/instructor/courses/{course_id}/quiz', [QuizManagementController::class, 'destroy']);

    Route::get('/instructor/students', [\App\Http\Controllers\Api\InstructorStudentController::class, 'index']);
    
    Route::get('/instructor/students/{student_id}/courses/{course_id}/progress', [\App\Http\Controllers\Api\InstructorStudentController::class, 'showProgress']);

    Route::post('/checkout', [CheckoutController::class, 'process']);
    Route::post('/checkout/status', [CheckoutController::class, 'checkStatus']);

});

Route::get('/courses/{id}/certificate/download', [\App\Http\Controllers\Api\CertificateController::class, 'downloadPdf']);