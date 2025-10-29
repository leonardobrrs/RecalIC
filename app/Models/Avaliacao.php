<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';
    protected $fillable = [
        'ocorrencia_id',
        'user_id',
        'nota',
        'comentario',
    ];

    /**
     * Uma Avaliação pertence a uma Ocorrência.
     */
    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    /**
     * Uma Avaliação foi feita por um Usuário.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
