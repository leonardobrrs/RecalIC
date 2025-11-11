<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcorrenciaAnexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocorrencia_id',
        'file_path',
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }
}
