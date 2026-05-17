<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foro extends Model
{
    use HasFactory;

    protected $table = 'foros';
    protected $primaryKey = 'ID_foro';

    protected $fillable = [
        'ID_usuario',
        'Titulo',
        'Contenido',
        'Categoria'
    ];

    // Relación para que funcione el Foro::with('usuario')
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario');
    }
}