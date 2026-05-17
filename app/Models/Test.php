<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla en tu captura
    protected $table = 'test'; 
    
    // Tu llave primaria personalizada
    protected $primaryKey = 'ID_test';
    
    // Desactivamos timestamps si no tienes created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Clasificacion'
    ];
}