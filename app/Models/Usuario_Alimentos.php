<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario_Alimentos extends Model
{
    use HasFactory;

    protected $table = 'usuario_alimentos'; 
    
    public $incrementing = false;
    protected $primaryKey = null; 
    public $timestamps = false;

    protected $fillable = [
        'ID_usuario',
        'ID_Alimento'
    ];

    // Relación con Usuario corregida explícitamente
    public function usuario()
    {
        // 1. Modelo destino, 2. FK en esta tabla, 3. PK en la tabla destino
        return $this->belongsTo(Usuario::class, 'ID_usuario', 'ID_usuario');
    }

    // Relación con Alimentos corregida explícitamente
    public function alimento()
    {
        // 1. Modelo destino, 2. FK en esta tabla, 3. PK en la tabla destino
        return $this->belongsTo(Alimentos::class, 'ID_Alimento', 'ID_Alimento');
    }
}