<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    protected $primaryKey = 'ID_Actividad';
    
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Tipo',
        'Descripcion',
        'Clasificacion',
        'Frecuencia',
        'Beneficio',
        'ID_usuario'
    ];

    // Relación con el Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario');
    }
}