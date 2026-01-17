<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Verifica si un índice existe en una tabla
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Run the migrations.
     * Agrega índices FULLTEXT para mejorar búsquedas de texto
     * Solo funciona en MySQL/MariaDB con tablas InnoDB (MySQL 5.6+)
     */
    public function up(): void
    {
        // Índice FULLTEXT para id_cliente
        if (!$this->indexExists('clientes', 'idx_fulltext_id_cliente')) {
            DB::statement('ALTER TABLE clientes ADD FULLTEXT idx_fulltext_id_cliente (id_cliente)');
        }

        // Índice FULLTEXT para nombre
        if (!$this->indexExists('clientes', 'idx_fulltext_nombre')) {
            DB::statement('ALTER TABLE clientes ADD FULLTEXT idx_fulltext_nombre (nombre)');
        }

        // Índice FULLTEXT para Correo_Electronico
        if (!$this->indexExists('clientes', 'idx_fulltext_correo')) {
            DB::statement('ALTER TABLE clientes ADD FULLTEXT idx_fulltext_correo (Correo_Electronico)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('clientes', 'idx_fulltext_id_cliente')) {
            Schema::table('clientes', fn (Blueprint $table) => $table->dropIndex('idx_fulltext_id_cliente'));
        }

        if ($this->indexExists('clientes', 'idx_fulltext_nombre')) {
            Schema::table('clientes', fn (Blueprint $table) => $table->dropIndex('idx_fulltext_nombre'));
        }

        if ($this->indexExists('clientes', 'idx_fulltext_correo')) {
            Schema::table('clientes', fn (Blueprint $table) => $table->dropIndex('idx_fulltext_correo'));
        }
    }
};

