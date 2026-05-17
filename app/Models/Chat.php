<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $primaryKey = 'ID_chat';

    protected $fillable = [
        'ID_emisor',
        'ID_receptor',
        'Mensaje',
        'Leido'
    ];
}

