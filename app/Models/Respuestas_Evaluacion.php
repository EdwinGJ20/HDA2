<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuestas_Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'respuestas_evaluacion';
    protected $primaryKey = 'ID_respuesta';
    
    public $timestamps = false;

    protected $fillable = [
        'ID_evaluacion',
        'ID_pregunta',
        'Respuesta',
        'Puntaje'
    ];
}