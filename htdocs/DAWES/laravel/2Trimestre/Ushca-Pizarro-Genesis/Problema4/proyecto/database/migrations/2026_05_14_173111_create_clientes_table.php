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
            $table->id(); // bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('cif', 20)->unique();
            $table->string('nombre', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();
            $table->string('cuenta_corriente', 50)->nullable();
            $table->string('pais', 50)->nullable();
            $table->string('moneda', 10)->nullable();
            $table->decimal('importe_cuota_mensual', 10, 2)->nullable();
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_baja')->nullable();
            // Si no usas timestamps en tu SQL original, no los pongas aquí
            // $table->timestamps(); 
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
