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

    public function relator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anexos()
    {
        return $this->hasMany(OcorrenciaAnexo::class);
    }

    public function historico()
    {
        return $this->hasMany(StatusHistorico::class);
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class);
    }
}
