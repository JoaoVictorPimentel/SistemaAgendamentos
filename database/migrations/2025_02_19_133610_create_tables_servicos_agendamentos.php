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
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        DB::table('servicos')->insert([
            ['nome' => 'Corte Feminino - R$50,00'],
            ['nome' => 'Escova - R$40,00'],
            ['nome' => 'Coloração - R$120,00'],
            ['nome' => 'Hidratação - R$80,00'],
            ['nome' => 'Progressiva - R$200,00']
        ]);
        
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade');
            $table->string('nome_cliente');
            $table->string('celular');
            $table->dateTime('data_agendamento');
            $table->enum('status', ['pendente', 'confirmado', 'cancelado'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
        Schema::dropIfExists('servicos');
    }
};
