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

            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreignId('email_id')->references('id')->on('emails')->onDelete('cascade');
            
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
