<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfigAvanzada;
use App\Models\Empleado;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function downloadFile(Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isEmpleado() && $tarea->operario_id !== $user->empleado_id) {
            abort(403);
        }

        if (!$tarea->fichero_resumen || !Storage::disk('private')->exists($tarea->fichero_resumen)) {
            abort(404);
        }

        $nombreArchivo = $tarea->id . '_' . basename($tarea->fichero_resumen);
        return Storage::disk('private')->download($tarea->fichero_resumen, $nombreArchivo);
    }

    private function provincias(): array
    {
        return [
            '01' => 'Álava',
            '02' => 'Albacete',
            '03' => 'Alicante',
            '04' => 'Almería',
            '05' => 'Ávila',
            '06' => 'Badajoz',
            '07' => 'Islas Baleares',
            '08' => 'Barcelona',
            '09' => 'Burgos',
            '10' => 'Cáceres',
            '11' => 'Cádiz',
            '12' => 'Castellón',
            '13' => 'Ciudad Real',
            '14' => 'Córdoba',
            '15' => 'A Coruña',
            '16' => 'Cuenca',
            '17' => 'Girona',
            '18' => 'Granada',
            '19' => 'Guadalajara',
            '20' => 'Guipúzcoa',
            '21' => 'Huelva',
            '22' => 'Huesca',
            '23' => 'Jaén',
            '24' => 'León',
            '25' => 'Lleida',
            '26' => 'La Rioja',
            '27' => 'Lugo',
            '28' => 'Madrid',
            '29' => 'Málaga',
            '30' => 'Murcia',
            '31' => 'Navarra',
            '32' => 'Ourense',
            '33' => 'Asturias',
            '34' => 'Palencia',
            '35' => 'Las Palmas',
            '36' => 'Pontevedra',
            '37' => 'Salamanca',
            '38' => 'Santa Cruz de Tenerife',
            '39' => 'Cantabria',
            '40' => 'Segovia',
            '41' => 'Sevilla',
            '42' => 'Soria',
            '43' => 'Tarragona',
            '44' => 'Teruel',
            '45' => 'Toledo',
            '46' => 'Valencia',
            '47' => 'Valladolid',
            '48' => 'Vizcaya',
            '49' => 'Zamora',
            '50' => 'Zaragoza',
            '51' => 'Ceuta',
            '52' => 'Melilla',
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();

        return view('tareas.create', [
            'clientes' => $clientes,
            'operarios' => $operarios,
            'provincias' => $this->provincias(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'required|exists:empleados,id',
            'persona_contacto' => 'required|string|max:255',
            'telefono_contacto' => ['required', 'regex:/^[+()0-9\s\-.]+$/'],
            'descripcion' => 'required|string',
            'correo_contacto' => 'required|email',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => ['required', 'regex:/^\d{2}$/'],
            'fecha_realizacion' => 'required|date|after:today',
        ]);

        $cp = $request->codigo_postal;
        $provincia = $request->provincia;

        if (substr($cp, 0, 2) !== $provincia) {
            return back()
                ->withErrors([
                    'provincia' => 'La provincia seleccionada no corresponde con el código postal',
                ])
                ->withInput();
        }

        Tarea::create($request->only(
            'cliente_id',
            'operario_id',
            'persona_contacto',
            'telefono_contacto',
            'descripcion',
            'correo_contacto',
            'direccion',
            'poblacion',
            'codigo_postal',
            'provincia',
            'fecha_realizacion',
            'estado',
            'anotaciones_anteriores',
        ));

        return redirect('/')->with('success', 'Tarea creada correctamente');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();

        return view('tareas.edit', [
            'tarea' => $tarea,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'provincias' => $this->provincias(),
        ]);
    }

    /**
     * Actualizar los datos de una tarea
     */
    public function update(Request $request, Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'required|exists:empleados,id',
            'persona_contacto' => 'required|string|max:255',
            'telefono_contacto' => ['required', 'regex:/^[+()0-9\s\-.]+$/'],
            'descripcion' => 'required|string',
            'correo_contacto' => 'required|email',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => ['required', 'regex:/^\d{2}$/'],
            'fecha_realizacion' => 'required|date|after:today',
        ]);

        $cp = $request->codigo_postal;
        $provincia = $request->provincia;

        if (substr($cp, 0, 2) !== $provincia) {
            return back()
                ->withErrors([
                    'provincia' => 'La provincia seleccionada no corresponde con el código postal',
                ])
                ->withInput();
        }

        $tarea->update($request->only(
            'cliente_id',
            'operario_id',
            'persona_contacto',
            'telefono_contacto',
            'descripcion',
            'correo_contacto',
            'direccion',
            'poblacion',
            'codigo_postal',
            'provincia',
            'estado',
            'fecha_realizacion',
            'anotaciones_anteriores',
        ));
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
