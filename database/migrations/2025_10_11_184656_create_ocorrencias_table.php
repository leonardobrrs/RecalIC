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
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('localizacao');
            $table->string('categoria');
            $table->string('patrimonio_id')->nullable();
            $table->text('descricao');
            $table->enum('status', ['Aberto', 'Em Análise', 'Resolvido', 'Fechado', 'Inválido']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocorrencias');
    }
};
