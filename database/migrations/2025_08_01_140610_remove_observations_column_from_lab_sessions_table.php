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
        Schema::table('lab_sessions', function (Blueprint $table) {
            // Verificamos si la columna existe antes de intentar borrarla
            if (Schema::hasColumn('lab_sessions', 'observations')) {
                $table->dropColumn('observations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_sessions', function (Blueprint $table) {
            $table->text('observations')->nullable()->after('end_time');
        });
    }
};
