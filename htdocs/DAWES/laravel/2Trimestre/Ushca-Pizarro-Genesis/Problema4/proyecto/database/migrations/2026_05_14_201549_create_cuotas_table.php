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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('concepto')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->decimal('importe', 10, 2)->nullable();
            $table->decimal('importe_euros', 10, 2)->nullable();
            $table->boolean('pagada')->default(0);
            $table->date('fecha_pago')->nullable();
            $table->enum('tipo', ['mensual', 'excepcional']); 
            $table->text('notas')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
