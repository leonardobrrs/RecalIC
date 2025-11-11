<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistorico extends Model
{
    use HasFactory;

    protected $table = 'status_historico';

    protected $fillable = [
        'ocorrencia_id',
        'user_id',
        'status_anterior',
        'status_novo',
        'comentario',
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
