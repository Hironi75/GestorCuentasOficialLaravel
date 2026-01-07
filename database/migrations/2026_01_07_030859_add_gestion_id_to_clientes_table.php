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
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('gestion_id')->nullable()->after('id_cliente');
            $table->foreign('gestion_id')->references('id')->on('gestiones')->onDelete('cascade');
        });
        
        // Asignar gestión actual a clientes existentes
        $gestionActual = DB::table('gestiones')->where('activa', true)->first();
        if ($gestionActual) {
            DB::table('clientes')->whereNull('gestion_id')->update(['gestion_id' => $gestionActual->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['gestion_id']);
            $table->dropColumn('gestion_id');
        });
    }
};
