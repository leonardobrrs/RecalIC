<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'localizacao',
        'categoria',
        'patrimonio_id',
        'descricao',
        'status',
    ];

    // --- RELACIONAMENTOS ---

    /**
     * Uma Ocorrência pertence a um Usuário (o relator).
     */
    public function relator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Uma Ocorrência pode ter vários Anexos.
     */
    public function anexos()
    {
        return $this->hasMany(OcorrenciaAnexo::class);
    }

    /**
     * Uma Ocorrência pode ter vários registros de Histórico de Status.
     */
    public function historico()
    {
        return $this->hasMany(StatusHistorico::class);
    }

    /**
     * Uma Ocorrência pode ter uma Avaliação.
     */
    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class);
    }
}
