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
        Schema::create('users', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name', 255);
            $blueprint->string('email', 255)->unique();
            $blueprint->string('google_id', 255)->nullable()->unique();
            $blueprint->string('twitter_id', 255)->nullable()->unique();
            $blueprint->string('provider', 50)->nullable();
            $blueprint->string('avatar', 255)->nullable();
            $blueprint->timestamp('email_verified_at')->nullable();
            $blueprint->string('password', 255)->nullable();
            $blueprint->rememberToken();
            $blueprint->timestamps(); 
            $blueprint->timestamp('last_login')->nullable();
            $blueprint->string('status', 50)->default('Active');
            $blueprint->string('dni', 255)->nullable()->unique();
            $blueprint->string('telefono', 255)->nullable();
            $blueprint->string('direccion', 255)->nullable();
            $blueprint->date('fecha_alta')->nullable();
            $blueprint->date('fecha_baja')->nullable();
            $blueprint->enum('tipo', ['administrador', 'operario'])->default('operario');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
