<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega índices para mejorar el rendimiento de las consultas
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Índice compuesto para filtrar por gestión y ordenar por fecha
            $table->index(['gestion_id', 'Fecha_Fin'], 'idx_clientes_gestion_fecha');

            // Índices individuales para búsquedas
            $table->index('nombre', 'idx_clientes_nombre');
            $table->index('Correo_Electronico', 'idx_clientes_correo');
            $table->index('Fecha_Fin', 'idx_clientes_fecha_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex('idx_clientes_gestion_fecha');
            $table->dropIndex('idx_clientes_nombre');
            $table->dropIndex('idx_clientes_correo');
            $table->dropIndex('idx_clientes_fecha_fin');
        });
    }
};

