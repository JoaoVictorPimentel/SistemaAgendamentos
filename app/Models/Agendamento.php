<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamentos';

    protected $primaryKey = 'id';
    protected $fillable = [
        'servico_id',
        'nome_cliente',
        'celular',
        'data_agendamento',
        'status',
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
