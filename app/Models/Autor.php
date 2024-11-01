<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    // Definir a tabela associada (opcional, pois o Laravel infere o nome da tabela como o plural do nome do model)
    protected $table = 'autores';

    // Definir os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'nome',
        'biografia',
        'data_nascimento',
    ];

    // Definir as casts (opcional) para garantir que `data_nascimento` seja tratada como uma data
    protected $casts = [
        'data_nascimento' => 'date',
    ];
}
