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
        Schema::create('clientes', function (Blueprint $table) {
            $table->string('id_cliente')->primary();
            $table->string('Correo_Electronico')->nullable();
            $table->string('Password')->nullable();
            $table->string('nombre')->nullable();
            $table->string('celular')->nullable();
            $table->date('Fecha_Inicio')->nullable();
            $table->date('Fecha_Fin')->nullable();
            $table->string('Concepto')->nullable();
            $table->decimal('SaldoPagar', 10, 2)->default(0);
            $table->decimal('AbonoDeuda', 10, 2)->nullable()->default(0);
            $table->decimal('TotalPagar', 10, 2)->default(0);

            $table->decimal('ENERO', 10, 2)->nullable();
            $table->string('ENERO_CONCEPTO')->nullable();
            $table->decimal('FEBRERO', 10, 2)->nullable();
            $table->string('FEBRERO_CONCEPTO')->nullable();
            $table->decimal('MARZO', 10, 2)->nullable();
            $table->string('MARZO_CONCEPTO')->nullable();
            $table->decimal('ABRIL', 10, 2)->nullable();
            $table->string('ABRIL_CONCEPTO')->nullable();
            $table->decimal('MAYO', 10, 2)->nullable();
            $table->string('MAYO_CONCEPTO')->nullable();
            $table->decimal('JUNIO', 10, 2)->nullable();
            $table->string('JUNIO_CONCEPTO')->nullable();
            $table->decimal('JULIO', 10, 2)->nullable();
            $table->string('JULIO_CONCEPTO')->nullable();
            $table->decimal('AGOSTO', 10, 2)->nullable();
            $table->string('AGOSTO_CONCEPTO')->nullable();
            $table->decimal('SEPTIEMBRE', 10, 2)->nullable();
            $table->string('SEPTIEMBRE_CONCEPTO')->nullable();
            $table->decimal('OCTUBRE', 10, 2)->nullable();
            $table->string('OCTUBRE_CONCEPTO')->nullable();
            $table->decimal('NOVIEMBRE', 10, 2)->nullable();
            $table->string('NOVIEMBRE_CONCEPTO')->nullable();
            $table->decimal('DICIEMBRE', 10, 2)->nullable();
            $table->string('DICIEMBRE_CONCEPTO')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
