<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foro extends Model
{
    use HasFactory;

    protected $table = 'foros';
    protected $primaryKey = 'ID_foro';

    // Desactivamos timestamps automáticos (created_at / updated_at)
    public $timestamps = false; 

    protected $fillable = [
        'ID_usuario',
        'Titulo',
        'Contenido',
        'Fecha_Creacion' // Solo los campos reales de tu tabla
    ];

    // Relación corregida apuntando a ID_usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario', 'ID_usuario');
    }
}