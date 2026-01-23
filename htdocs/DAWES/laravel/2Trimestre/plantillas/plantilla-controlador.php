<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    /**
     * Mostrar lista de artículos
     */
    public function index(Request $request)
    {
        $query = Articulo::with('categoria'); // carga relaciones si las tienes

        // Filtrado por búsqueda
        if ($request->filled('busqueda')) {
            $query->where('titulo', 'like', '%' . $request->busqueda . '%');
        }

        // Filtrado por categoría (opcional)
        // if ($request->filled('categoria_id')) {
        //     $query->where('categoria_id', $request->categoria_id);
        // }

        // Ordenación
        if ($request->filled('orden')) {
            [$campo, $direccion] = explode('|', $request->orden);
            $query->orderBy($campo, $direccion);
        } else {
            $query->orderByDesc('created_at');
        }

        $articulos = $query->paginate(10);

        // Si usas filtro por categoría, pasa también $categorias
        return view('articulos.index', compact('articulos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::all();
        $etiquetas = Etiqueta::all();

        return view('articulos.create', compact('categorias', 'etiquetas'));
    }

    /**
     * Guardar nuevo artículo
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'etiquetas' => 'array',
            'etiquetas.*' => 'exists:etiquetas,id',
            'portada' => 'nullable|image|max:2048',
            'activo' => 'boolean',
        ]);

        $data = $request->except('portada', 'etiquetas');
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('portada')) {
            $data['portada'] = $request->file('portada')->store('portadas', 'public');
        }

        $articulo = Articulo::create($data);

        // Sincronizar relación N:N
        if ($request->filled('etiquetas')) {
            $articulo->etiquetas()->sync($request->etiquetas);
        }

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo creado correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Articulo $articulo)
    {
        $categorias = Categoria::all();
        $etiquetas = Etiqueta::all();
        $etiquetasSeleccionadas = $articulo->etiquetas->pluck('id')->toArray();

        return view('articulos.edit', compact(
            'articulo',
            'categorias',
            'etiquetas',
            'etiquetasSeleccionadas'
        ));
    }

    /**
     * Actualizar artículo
     */
    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'etiquetas' => 'array',
            'etiquetas.*' => 'exists:etiquetas,id',
            'portada' => 'nullable|image|max:2048',
            'activo' => 'boolean',
        ]);

        $data = $request->except('portada', 'etiquetas');
        $data['activo'] = $request->boolean('activo');

        // Eliminar portada anterior si se sube una nueva
        if ($request->hasFile('portada')) {
            if ($articulo->portada) {
                Storage::disk('public')->delete($articulo->portada);
            }
            $data['portada'] = $request->file('portada')->store('portadas', 'public');
        }

        $articulo->update($data);

        // Sincronizar relación N:N
        $articulo->etiquetas()->sync($request->etiquetas ?? []);

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo actualizado correctamente');
    }

    /**
     * Eliminar artículo
     */
    public function destroy(Articulo $articulo)
    {
        if ($articulo->portada) {
            Storage::disk('public')->delete($articulo->portada);
        }

        $articulo->delete();

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo eliminado correctamente');
    }
}
