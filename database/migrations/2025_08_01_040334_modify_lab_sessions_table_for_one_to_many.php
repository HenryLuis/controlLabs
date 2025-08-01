<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('lab_sessions', function (Blueprint $table) {
            // El nombre de la restricción es tabla_columna_foreign
            $table->dropForeign('lab_sessions_student_id_foreign'); // ¡OJO AQUÍ!
            $table->dropColumn(['student_id', 'pc_number', 'student_signature']);
        });
    }
    public function down(): void {
        Schema::table('lab_sessions', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->after('teacher_id');
            $table->string('pc_number')->after('student_id');
            $table->longText('student_signature')->nullable()->after('pc_number');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
