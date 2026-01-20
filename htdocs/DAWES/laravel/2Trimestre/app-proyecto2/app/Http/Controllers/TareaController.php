<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfigAvanzada;
use App\Models\Empleado;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    /**
     * Listado de tareas paginadas
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $itemsPorPagina = ConfigAvanzada::actual()->items_por_pagina ?? 5;

        if ($user->isAdmin()) {
            $tareas = Tarea::with(['cliente', 'operario'])
                ->orderByDesc('fecha_realizacion')
                ->paginate($itemsPorPagina);
        } else {
            $tareas = Tarea::with(['cliente', 'operario'])
                ->where('operario_id', $user->empleado_id)
                ->orderByDesc('fecha_realizacion')
                ->paginate($itemsPorPagina);
        }

        return view('tareas.index', compact('tareas'));
    }

    /**
     * Ver detalle de una tarea
     */
    public function show(Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isEmpleado() && $tarea->operario_id !== $user->empleado_id) {
            abort(403);
        }

        return view('tareas.show', compact('tarea'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();

        return view('tareas.create', compact('clientes', 'operarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'required|exists:empleados,id',
            'descripcion' => 'required|string',
            'correo' => 'required|email',
            'fecha_realizacion' => 'required|date|after:today',
        ]);

        Tarea::create($request->all());

        return redirect('/')->with('success', 'Tarea creada correctamente');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarea $tarea)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();

        return view('tareas.edit', compact('tarea', 'clientes', 'operarios'));
    }

    /**
     * Actualizar los datos de una tarea
     */
    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'required|exists:empleados,id',
            'correo' => 'required|email',
            'fecha_realizacion' => 'required|date|after:today',
        ]);

        $tarea->update($request->all());
        return redirect('/')->with('success', 'Tarea actualizada correctamente');
    }

    /**
     * Confirmar la eliminación de una tarea
     *
     * @param Tarea $tarea
     * @return void
     */


    public function confirmDelete(Tarea $tarea)
    {
        return view('tareas.confirmDelete', compact('tarea'));
    }


    /**
     * Eliminar una tarea
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();

        return redirect('/')->with('success', 'Tarea eliminada correctamente');
    }

    public function completeForm(Tarea $tarea)
    {
        $user = Auth::user();

        if ($tarea->operario_id !== $user->empleado_id) {
            abort(403);
        }

        return view('tareas.completeForm', compact('tarea'));
    }

    public function complete(Request $request, Tarea $tarea)
    {
        $user = Auth::user();

        if ($tarea->operario_id !== $user->empleado_id) {
            abort(403);
        }

        $request->validate([
            'anotaciones_posteriores' => 'nullable|string',
            'fichero_resumen'         => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('fichero_resumen')) {
            $ruta = $request->file('fichero_resumen')
                ->store('ficheros_tareas', 'private');

            $tarea->fichero_resumen = $ruta;
        }

        $tarea->estado = 'R';
        $tarea->anotaciones_posteriores = $request->anotaciones_posteriores;
        $tarea->save();

        return redirect('/')
            ->with('success', 'Tarea completada correctamente');
    }
}
