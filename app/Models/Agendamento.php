<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamentos';

    protected $casts = [
        'data_agendamento' => 'date'
    ];
    protected $primaryKey = 'id';
    protected $fillable = [
        'servico_id',
        'user_id',
        'nome_cliente',
        'celular',
        'data_agendamento',
        'hora',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
