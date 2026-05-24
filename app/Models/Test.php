<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $table = 'test'; 
    protected $primaryKey = 'ID_test';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Clasificacion'
    ];

    /**
     * Obtener las preguntas asociadas a este Test.
     */
    public function preguntas(): HasMany
    {
        // El primer parámetro es el modelo relacionado.
        // El segundo es la llave foránea en la tabla 'preguntas'.
        // El tercero es la llave local en la tabla 'test'.
        return $this->hasMany(Preguntas::class, 'ID_test', 'ID_test');
    }
}