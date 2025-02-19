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
        Schema::table('servicos', function (Blueprint $table) {
            $table->decimal('valor', 8, 2)->after('nome');
        });

        $servicos = [
            ['nome' => 'Corte Feminino', 'valor' => 50.00],
            ['nome' => 'Escova', 'valor' => 40.00],
            ['nome' => 'Coloração', 'valor' => 120.00],
            ['nome' => 'Hidratação', 'valor' => 80.00],
            ['nome' => 'Progressiva', 'valor' => 200.00],
        ];

        foreach ($servicos as $servico) {
            DB::table('servicos')
                ->where('nome', 'like', '%' . $servico['nome'] . '%')
                ->update([
                    'nome' => $servico['nome'],
                    'valor' => $servico['valor'],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicos', function (Blueprint $table) {
            //
        });
    }
};
