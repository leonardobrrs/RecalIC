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
        Schema::table('ocorrencias', function (Blueprint $table) {
            // 1. Remove a restrição "onDelete('cascade')" antiga
            $table->dropForeign(['user_id']);

            // 2. Altera a coluna user_id para permitir valores nulos
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // 3. Adiciona a nova restrição "onDelete('set null')"
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // Define user_id como NULL se o utilizador for apagado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            // Reverte as alterações
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change(); // Remove o nullable
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Volta para 'cascade'
        });
    }
};
