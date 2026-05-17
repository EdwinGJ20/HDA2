<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario_Test extends Model
{
    use HasFactory;

    protected $table = 'usuario_test'; // Verifica si en tu DB tiene guion bajo
    
    // Configuramos para tabla pivote
    public $incrementing = false;
    protected $primaryKey = null; 
    public $timestamps = false;

    protected $fillable = [
        'ID_usuario',
        'ID_test'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario');
    }

    // Relación con Test
    public function test()
    {
        return $this->belongsTo(Test::class, 'ID_test');
    }
}