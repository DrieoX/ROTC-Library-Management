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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title')->unique(); // Make title unique to prevent duplicate achievements
            $table->text('description')->nullable(); // Nullable description
            $table->enum('type', ['first', 'milestone', 'special'])->default('milestone'); // Optional: categorize achievements
            $table->timestamps(); // Timestamps
        });        
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
