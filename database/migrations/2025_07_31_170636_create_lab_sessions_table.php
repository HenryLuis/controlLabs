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
        Schema::create('lab_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            $table->string('pc_number');
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('observations')->nullable();

            // Buena Práctica: Usar LONGTEXT para firmas en base64.
            // Puede que excedan el límite de un TEXT normal.
            $table->longText('student_signature')->nullable();

            $table->foreignId('internal_control_reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('internal_control_reviewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_sessions');
    }
};
