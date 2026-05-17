<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos_Resultados extends Model
{
    use HasFactory;

    protected $table = 'tipos_resultados'; // Asegúrate que así se llame la tabla en la DB
    protected $primaryKey = 'ID_T_Resultado';

    // Si no tienes las columnas created_at y updated_at, deja esto en false
    public $timestamps = false;

    protected $fillable = [
        'Tipo',
        'Descripcion',
        'Puntaje_Asig'
    ];
}