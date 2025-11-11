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

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
