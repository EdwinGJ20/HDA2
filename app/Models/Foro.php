<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foro extends Model
{
    use HasFactory;

    protected $table = 'foros';
    protected $primaryKey = 'ID_foro';

    // 🌟 IMPORTANTE: Si no usas "created_at" y "updated_at" nativos de Laravel, desactívalos
    public $timestamps = false; 

    protected $fillable = [
        'ID_usuario',
        'Titulo',
        'Contenido',
        'Categoria',
        'Fecha_Creacion' // 🌟 Agregamos la fecha aquí para permitir su inserción masiva
    ];

    // Relación para que funcione el Foro::with('usuario')
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario', 'ID_usuario'); // Añadimos llaves explícitas
    }
}