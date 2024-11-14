<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitar extends Model
{
    use HasFactory;
    protected $fillable = [
        'hora_inicial',
        // 'hora_final',
        'data_inicial',
        'data_final',
        'veiculo_id',
    ];
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }
}
