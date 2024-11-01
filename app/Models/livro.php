<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    /**
     * Os campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'data_publicacao',
        'categoria_id'
    ];

    /**
     * Definindo o relacionamento belongsTo com a Categoria.
     * Um livro pertence a uma categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Escopo para filtrar livros por título.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $titulo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltrarPorTitulo($query, $titulo)
    {
        if (!empty($titulo)) {
            return $query->where('titulo', 'LIKE', '%' . $titulo . '%');
        }
        return $query;
    }

    /**
     * Mutator para o campo data_publicacao, para formatar a data ao salvar no banco.
     *
     * @param string $value
     * @return void
     */
    public function setDataPublicacaoAttribute($value)
    {
        $this->attributes['data_publicacao'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Accessor para formatar a data_publicacao ao buscar do banco de dados.
     *
     * @return string
     */
    public function getDataPublicacaoAttribute()
    {
        return date('d/m/Y', strtotime($this->attributes['data_publicacao']));
    }
}
