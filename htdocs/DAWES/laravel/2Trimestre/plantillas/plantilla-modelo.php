<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'categoria_id',
        'autor_id',
        'portada',
        'activo',
    ];

    // RELACIONES

    // 1:N inversa → pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // 1:1 → tiene un detalle adicional
    public function detalle()
    {
        return $this->hasOne(DetalleArticulo::class);
    }

    // N:N → tiene muchas etiquetas
    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'articulo_etiqueta');
    }

    // 1:N → tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    // Accesor personalizado
    public function getResumenAttribute()
    {
        return substr($this->contenido, 0, 100) . '...';
    }
}