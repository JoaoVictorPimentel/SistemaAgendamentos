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
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->enum('hora', [
                '08:00', '09:00', '10:00', '11:00', '12:00', 
                '13:00', '14:00', '15:00', '16:00', '17:00'
            ])->after('data_agendamento')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            
        });
    }
};
