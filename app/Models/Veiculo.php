<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;
    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'ano',
        'cor',
        'chassi',
        'capacidade',
        'km_atual',
        'observacao',
        'funcionamento',
        'qr_code',
    ];
    public function solicitacoes()
    {
        return $this->hasMany(Solicitar::class);
    }

}
