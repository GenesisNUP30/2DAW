<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['operario_id']);
            $table->renameColumn('operario_id', 'user_id');
        });
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->renameColumn('user_id', 'operario_id');
        });
    }
};

