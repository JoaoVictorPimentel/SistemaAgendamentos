<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'servicos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'valor'
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
