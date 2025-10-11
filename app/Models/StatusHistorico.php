<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistorico extends Model
{
    use HasFactory;

    // Define o nome da tabela manualmente pois o nome da classe é diferente da convenção
    protected $table = 'status_historico';

    protected $fillable = [
        'ocorrencia_id',
        'user_id',
        'status_anterior',
        'status_novo',
        'comentario',
    ];

    /**
     * Um registro de Histórico pertence a uma Ocorrência.
     */
    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    /**
     * Um registro de Histórico foi feito por um Usuário (admin).
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
