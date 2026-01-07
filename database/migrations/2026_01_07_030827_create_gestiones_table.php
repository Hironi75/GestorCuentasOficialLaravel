<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gestiones', function (Blueprint $table) {
            $table->id();
            $table->year('anio')->unique();
            $table->string('nombre')->nullable(); // Ej: "Gestión 2026"
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
        
        // Insertar gestión actual por defecto
        DB::table('gestiones')->insert([
            'anio' => date('Y'),
            'nombre' => 'Gestión ' . date('Y'),
            'activa' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestiones');
    }
};
