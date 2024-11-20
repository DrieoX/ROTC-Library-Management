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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Foreign key to users table (students)
            $table->foreignId('book_copy_id')->constrained('book_copies')->onDelete('cascade'); // Foreign key to book_copies table
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending'); // Request status
            $table->timestamps();
        });          
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
