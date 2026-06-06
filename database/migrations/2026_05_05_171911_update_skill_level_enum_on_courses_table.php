<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, alter the enum to include the new value 'SMA/SMK'
        DB::statement("ALTER TABLE courses MODIFY COLUMN skill_level ENUM('SD', 'SMP', 'SMA', 'SMA/SMK', 'UMUM') DEFAULT 'UMUM'");
        
        // Update existing 'SMA' to 'SMA/SMK'
        DB::table('courses')->where('skill_level', 'SMA')->update(['skill_level' => 'SMA/SMK']);
        
        // Finally, remove the old 'SMA' from the enum definition
        DB::statement("ALTER TABLE courses MODIFY COLUMN skill_level ENUM('SD', 'SMP', 'SMA/SMK', 'UMUM') DEFAULT 'UMUM'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum to include 'SMA'
        DB::statement("ALTER TABLE courses MODIFY COLUMN skill_level ENUM('SD', 'SMP', 'SMA', 'SMA/SMK', 'UMUM') DEFAULT 'UMUM'");
        
        // Revert data back to 'SMA'
        DB::table('courses')->where('skill_level', 'SMA/SMK')->update(['skill_level' => 'SMA']);
        
        // Revert the enum definition entirely
        DB::statement("ALTER TABLE courses MODIFY COLUMN skill_level ENUM('SD', 'SMP', 'SMA', 'UMUM') DEFAULT 'UMUM'");
    }
};
