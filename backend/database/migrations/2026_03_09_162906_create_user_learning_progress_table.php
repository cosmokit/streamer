<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_learning_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('learning_step_id')->constrained('learning_steps')->onDelete('cascade');
            $table->enum('status', ['new', 'in_progress', 'awaiting_confirmation', 'completed'])->default('new');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'learning_step_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_learning_progress');
    }
};
