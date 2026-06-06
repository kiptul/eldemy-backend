<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('course_material_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
            $table->integer('duration')->default(0)->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['course_material_id']);
            $table->dropColumn(['course_material_id', 'duration']);
        });
    }
};