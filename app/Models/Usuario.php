<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario'; 
    protected $primaryKey = 'ID_usuario';
    
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Correo_Electronico',
        'Password', // <--- Agregado para el cifrado
        'Fecha_Registro',
        'Rol',
        'Edad',
        'Localidad'
    ];

    /**
     * Los atributos que deben ocultarse para los arreglos (JSON).
     * Esto evita que el hash de la contraseña se vea en las respuestas de la API.
     */
    protected $hidden = [
        'Password',
    ];
}