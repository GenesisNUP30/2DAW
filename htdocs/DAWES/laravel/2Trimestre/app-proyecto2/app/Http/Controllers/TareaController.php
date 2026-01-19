<?php

namespace App\Http\Controllers;

use App\Models\ConfigAvanzada;
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
        $user = Auth::user();
        $itemsPorPagina = ConfigAvanzada::actual()->items_por_pagina ?? 5 ;

        if ($user->isAdmin()) {
            $tareas = Tarea::with(['cliente', 'operario'])
            ->orderByDesc('fecha_creacion')
            ->paginate($itemsPorPagina);
        } else {
            $tareas = Tarea::with(['cliente', 'operario'])
            ->where('operario_id', $user->empleado_id)
            ->orderByDesc('fecha_creacion')
            ->paginate($itemsPorPagina);
        }

        return view('tareas.index', compact('tareas'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
