<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preguntas extends Model
{
    use HasFactory;

    protected $table = 'preguntas'; // Verifica si en tu DB es singular o plural
    protected $primaryKey = 'ID_pregunta';
    
    public $timestamps = false;

    protected $fillable = [
        'Pregunta',
        'Clasificacion',
        'ID_test'
    ];

    // Relación con el Test (opcional)
    public function test()
    {
        return $this->belongsTo(Test::class, 'ID_test');
    }
}