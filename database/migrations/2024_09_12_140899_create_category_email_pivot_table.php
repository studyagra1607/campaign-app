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
        Schema::create('category_email', function (Blueprint $table) {

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('email_id')->constrained('emails')->onDelete('cascade');
            // $table->timestamps(); // Optional: add created_at and updated_at columns
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_email');
    }
};
