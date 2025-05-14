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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_category_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('archive_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->boolean('notify_me')->default(true);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
