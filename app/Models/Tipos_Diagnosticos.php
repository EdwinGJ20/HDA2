<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos_Diagnosticos extends Model
{
    use HasFactory;

    protected $table = 'tipos_diagnostico'; // Aquí quité la 's' porque en tu DB vi que es singular
    protected $primaryKey = 'ID_Diagnostico';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Nivel_Riesgo',
        'Sugerencia'
    ];
}