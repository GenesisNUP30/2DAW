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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('operario_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('persona_contacto');
            $table->string('telefono_contacto');
            $table->text('descripcion');
            $table->string('correo_contacto');

            $table->string('direccion')->nullable();
            $table->string('poblacion')->nullable();
            $table->char('codigo_postal', 5)->nullable();
            $table->char('provincia', 2)->nullable();

            $table->enum('estado', ['B', 'P', 'R', 'C'])->nullable();

            $table->dateTime('fecha_creacion')->nullable();
            $table->date('fecha_realizacion')->nullable();

            $table->text('anotaciones_anteriores')->nullable();
            $table->text('anotaciones_posteriores')->nullable();

            $table->string('fichero_resumen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
