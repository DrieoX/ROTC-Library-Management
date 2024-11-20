<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing primary key
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); // Foreign key to the books table
            $table->string('isbn')->unique(); // ISBN for this particular copy
            $table->boolean('available')->default(true); // Availability of this copy (optional)
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('book_copies');
    }

};
