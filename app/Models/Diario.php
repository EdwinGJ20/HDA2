<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diario extends Model
{
    use HasFactory;

    protected $table = 'diarios';
    protected $primaryKey = 'ID_diario';

    protected $fillable = [
        'ID_usuario',
        'Titulo',
        'Entrada',
        'Estado_Animo'
    ];
}