<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo',
        'descripcion',
        'folio',
        'user_id',
        'municipio',
        'poblacion',
        'fechaInicio',
        'horaInicio',
        'horaFin',
    ];
}
