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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->dateTime('schedule')->nullable();
            $table->enum('schedule_type', ['once', 'always', 'count'])->default('once')->nullable();
            $table->integer('schedule_count')->nullable();
            $table->dateTime('last_run')->nullable();
            $table->integer('run_count')->nullable();
            $table->integer('availables_emails')->nullable();
            $table->enum('progress_status', ['draft', 'running', 'schedule', 'complete', 'failed'])->default('draft')->nullable();
            $table->boolean('status')->default(1)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('template_id');
            $table->foreign('template_id')->on('templates')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
