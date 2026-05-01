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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuota_id');

            $table->string('numero_factura');
            $table->string('cliente_nombre');
            $table->string('cliente_cif');

            $table->string('concepto');
            $table->decimal('importe', 10, 2);

            $table->string('moneda', 10)->default('EUR');

            $table->boolean('enviada')->default(false);

            $table->string('ruta_pdf')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
