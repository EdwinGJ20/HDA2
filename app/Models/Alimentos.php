<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimentos extends Model
{
    use HasFactory;

    protected $table = 'alimentos'; 
    protected $primaryKey = 'ID_Alimento';
    
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Clasificacion',
        'Beneficio',
        'Frecuencia',
        'Cantidad_recomendada'
    ];
}