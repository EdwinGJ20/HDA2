<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario_Alimentos extends Model
{
    use HasFactory;

    protected $table = 'usuario_alimentos'; // Verifica si tiene guion bajo en tu DB
    
    // Al ser tabla pivote, Laravel no encontrará un "id" automático
    public $incrementing = false;
    protected $primaryKey = null; 
    
    public $timestamps = false;

    protected $fillable = [
        'ID_usuario',
        'ID_Alimento'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ID_usuario');
    }

    // Relación con Alimento
    public function alimento()
    {
        return $this->belongsTo(Alimentos::class, 'ID_Alimento');
    }
}