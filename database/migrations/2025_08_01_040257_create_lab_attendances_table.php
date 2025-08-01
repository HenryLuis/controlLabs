<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('lab_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_session_id')->constrained('lab_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('pc_number');
            $table->longText('student_signature')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lab_attendances');
    }
};
