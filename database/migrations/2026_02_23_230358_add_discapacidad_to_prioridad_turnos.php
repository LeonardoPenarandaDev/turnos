<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite no soporta ALTER COLUMN, se recrea la columna
        Schema::table('turnos', function (Blueprint $table) {
            $table->string('prioridad_new')->default('normal')->after('nombre_completo');
        });

        DB::statement("UPDATE turnos SET prioridad_new = prioridad");

        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn('prioridad');
        });

        Schema::table('turnos', function (Blueprint $table) {
            $table->renameColumn('prioridad_new', 'prioridad');
        });
    }

    public function down(): void
    {
        // No se requiere revertir, el campo string acepta todos los valores anteriores
    }
};
