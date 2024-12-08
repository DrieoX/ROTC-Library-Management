<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('achievement_student', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Ensures proper relation with `students` table
            $table->foreignId('achievement_id')->constrained('achievements')->onDelete('cascade'); // Ensures proper relation with `achievements` table
            $table->boolean('notified')->default(false); // Tracks whether the student has been notified of the achievement
            $table->timestamps(); // Timestamps
        
            // Composite unique constraint to prevent duplicate records for the same achievement and student
            $table->unique(['student_id', 'achievement_id']);
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_student');
    }
};
