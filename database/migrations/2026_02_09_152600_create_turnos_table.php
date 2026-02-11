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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->enum('tipo_documento', ['CC', 'TI', 'CE', 'PAS']);
            $table->string('numero_documento');
            $table->string('nombre_completo')->nullable();
            $table->foreignId('tipo_tramite_id')->constrained('tipos_tramite')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'llamado', 'en_atencion', 'atendido', 'cancelado'])->default('pendiente');
            $table->foreignId('caja_id')->nullable()->constrained('cajas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('hora_solicitud')->useCurrent();
            $table->timestamp('hora_llamado')->nullable();
            $table->timestamp('hora_inicio_atencion')->nullable();
            $table->timestamp('hora_fin_atencion')->nullable();
            $table->integer('tiempo_atencion')->nullable()->comment('Tiempo en segundos');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para mejorar rendimiento
            $table->index(['estado', 'created_at']);
            $table->index('numero_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
