<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluacion';
    protected $primaryKey = 'ID_evaluacion';
    
    public $timestamps = false;

    protected $fillable = [
        'ID_usuario',
        'ID_test',
        'Fecha',
        'Puntaje_Total',
        'Nivel_Riesgo',
        'ID_Diagnostico'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario');
    }

    // Relación con Diagnóstico
    public function diagnostico()
    {
        return $this->belongsTo(Tipos_Diagnosticos::class, 'ID_Diagnostico');
    }
}