<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'producto',
        'descripcion',
        'categoria_id',
        'precio',
        'cantidad',
        'estado',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function scopeFiltrar(Builder $query, array $filtros): Builder
    {
        return $query
            ->when($filtros['buscar'] ?? null, function ($q, $buscar) {
                $q->where('producto', 'ILIKE', "%{$buscar}%");
            })
            ->when($filtros['categoria_id'] ?? null, function ($q, $categoriaId) {
                $q->where('categoria_id', $categoriaId);
            })
            ->when(isset($filtros['precio_min']) && $filtros['precio_min'] !== '', function ($q) use ($filtros) {
                $q->where('precio', '>=', $filtros['precio_min']);
            })
            ->when(isset($filtros['precio_max']) && $filtros['precio_max'] !== '', function ($q) use ($filtros) {
                $q->where('precio', '<=', $filtros['precio_max']);
            })
            ->when($filtros['estado'] ?? null, function ($q, $estado) {
                $q->where('estado', $estado);
            });
    }
}