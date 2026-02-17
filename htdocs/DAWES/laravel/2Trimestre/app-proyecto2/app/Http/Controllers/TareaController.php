<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfigAvanzada;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 * 
 */
class TareaController extends Controller
{
    /**
     * Listado de tareas paginadas
     */
    public function index()
    {

        $user = Auth::user();
        $itemsPorPagina = ConfigAvanzada::actual()->items_por_pagina ?? 5;

        if ($user->isAdmin()) {
            $tareas = Tarea::conRelaciones()
                ->ordenadasPorFecha()
                ->paginate($itemsPorPagina);
        } else {
            $tareas = Tarea::conRelaciones()
                ->paraOperario($user->id)
                ->ordenadasPorFecha()
                ->paginate($itemsPorPagina);
        }

        return view('tareas.index', compact('tareas'));
    }

    /**
     * Ver detalle de una tarea
     */
    public function show(Tarea $tarea)
    {

        $user = Auth::user();

        if ($user->isOperario() && $tarea->operario_id !== $user->id) {
            abort(403);
        }

        return view('tareas.show', compact('tarea'));
    }

    public function downloadFile(Tarea $tarea)
    {

        $user = Auth::user();

        if ($user->isOperario() && $tarea->operario_id !== $user->id) {
            abort(403);
        }

        if (!$tarea->fichero_resumen || !Storage::disk('private')->exists($tarea->fichero_resumen)) {
            abort(404);
        }


        return Storage::disk('private')->download($tarea->fichero_resumen);
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

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = User::where('tipo', 'operario')->orderBy('name')->get();

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

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'required|exists:users,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    // Eliminar todos los caracteres no numéricos
                    $soloDigitos = preg_replace('/[^0-9]/', '', $value);

                    // Verificar que el número resultante tenga entre 9 y 15 dígitos
                    if (strlen($soloDigitos) < 9) {
                        $fail('El teléfono debe tener al menos 9 dígitos.');
                    }

                    if (strlen($soloDigitos) > 15) {
                        $fail('El teléfono no puede tener más de 15 dígitos.');
                    }

                    // Verificar formato (solo caracteres permitidos)
                    if (!preg_match('/^[\+()0-9\s\-.]+$/', $value)) {
                        $fail('El teléfono contiene caracteres no permitidos. Solo se permiten números, +, (), -, . y espacios.');
                    }
                }
            ],
            'descripcion' => 'required|string|min:10',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => 'required|in:' . implode(',', array_keys($this->provincias())),
            'fecha_realizacion' => 'required|date|after_or_equal:today',
            'estado' => 'required|in:B,P,R,C',
            'anotaciones_anteriores' => 'nullable|string',
        ], [
            'cliente_id.required' => 'Debes seleccionar un cliente',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'operario_id.required' => 'Debes seleccionar un operario',
            'operario_id.exists' => 'El operario seleccionado no existe',
            'persona_contacto.required' => 'La persona de contacto es obligatoria',
            'persona_contacto.max' => 'La persona de contacto no puede tener más de 100 caracteres',
            'telefono_contacto.required' => 'El teléfono de contacto es obligatorio',
            'telefono_contacto.regex' => 'El teléfono debe tener entre 9 y 20 digitos',
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
            'descripcion.string' => 'La descripción debe ser un texto válido',
            'correo_contacto.required' => 'El correo electrónico es obligatorio',
            'correo_contacto.email' => 'El correo electrónico no es válido',
            'correo_contacto.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'direccion.max' => 'La dirección no puede tener más de 200 caracteres',
            'poblacion.max' => 'La población no puede tener más de 100 caracteres',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'codigo_postal.regex' => 'El código postal debe tener exactamente 5 dígitos.',
            'provincia.required' => 'La provincia es obligatoria',
            'provincia.in' => 'La provincia seleccionada no es válida',
            'fecha_realizacion.required' => 'La fecha de realización es obligatoria',
            'fecha_realizacion.after_or_equal' => 'La fecha de realización debe ser posterior o igual a la fecha actual',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
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

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = User::where('tipo', 'operario')->orderBy('name')->get();

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

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'required|exists:users,id',
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    // Eliminar todos los caracteres no numéricos
                    $soloDigitos = preg_replace('/[^0-9]/', '', $value);

                    // Verificar que el número resultante tenga entre 9 y 15 dígitos
                    if (strlen($soloDigitos) < 9) {
                        $fail('El teléfono debe tener al menos 9 dígitos.');
                    }

                    if (strlen($soloDigitos) > 15) {
                        $fail('El teléfono no puede tener más de 15 dígitos.');
                    }

                    // Verificar formato (solo caracteres permitidos)
                    if (!preg_match('/^[\+()0-9\s\-.]+$/', $value)) {
                        $fail('El teléfono contiene caracteres no permitidos. Solo se permiten números, +, (), -, . y espacios.');
                    }
                }
            ],
            'descripcion' => 'required|string|min:10',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => 'required|in:' . implode(',', array_keys($this->provincias())),
            'fecha_realizacion' => 'required|date|after_or_equal:today',
            'estado' => 'required|in:B,P,R,C',
            'anotaciones_anteriores' => 'nullable|string',
        ], [
            'cliente_id.required' => 'Debes seleccionar un cliente',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'operario_id.required' => 'Debes seleccionar un operario',
            'operario_id.exists' => 'El operario seleccionado no existe',
            'persona_contacto.required' => 'La persona de contacto es obligatoria',
            'persona_contacto.max' => 'La persona de contacto no puede tener más de 100 caracteres',
            'telefono_contacto.required' => 'El teléfono de contacto es obligatorio',
            'telefono_contacto.regex' => 'El teléfono debe tener entre 9 y 20 digitos',
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
            'descripcion.string' => 'La descripción debe ser un texto válido',
            'correo_contacto.required' => 'El correo electrónico es obligatorio',
            'correo_contacto.email' => 'El correo electrónico no es válido',
            'correo_contacto.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'direccion.max' => 'La dirección no puede tener más de 200 caracteres',
            'poblacion.max' => 'La población no puede tener más de 100 caracteres',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'codigo_postal.regex' => 'El código postal debe tener exactamente 5 dígitos.',
            'provincia.required' => 'La provincia es obligatoria',
            'provincia.in' => 'La provincia seleccionada no es válida',
            'fecha_realizacion.required' => 'La fecha de realización es obligatoria',
            'fecha_realizacion.date' => 'La fecha de realización debe tener un formato válido',
            'fecha_realizacion.after_or_equal' => 'La fecha de realización debe ser posterior o igual a la fecha actual',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
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

        // Verificar que el usuario es el operario asignado
        if ($tarea->operario_id !== $user->id) {
            abort(403, 'No tienes permiso para completar esta tarea.');
        }

        return view('tareas.completeForm', compact('tarea'));
    }

    public function complete(Request $request, Tarea $tarea)
    {
        $user = Auth::user();

        if ($tarea->operario_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:R,C,B,P',
            'anotaciones_posteriores' => 'nullable|string|min:5',
            'fecha_realizacion' => 'nullable|date|after_or_equal:today',
            'fichero_resumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt,png,jpg,jpeg|max:5120',
        ], [
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
            'anotaciones_posteriores.min' => 'Las anotaciones posteriores deben tener al menos 5 caracteres',
            'fecha_realizacion.date' => 'La fecha de realización debe tener un formato válido',
            'fecha_realizacion.after_or_equal' => 'La fecha de realización debe ser posterior o igual a la fecha actual',
            'fichero_resumen.file' => 'El fichero debe ser un archivo válido',
            'fichero_resumen.mimes' => 'El fichero debe ser un archivo de tipo: pdf, doc, docx, xls, xlsx o txt',
            'fichero_resumen.max' => 'El fichero no puede superar los 5MB de tamaño',
        ]);

        $estadoTarea = $request->estado;
        $fechaRealizacion = $request->fecha_realizacion;

        // Si el estado es "Realizada", validar la fecha y fichero
        if ($estadoTarea === 'R') {
            if (empty($fechaRealizacion)) {
                return back()
                    ->withErrors(['fecha_realizacion' => 'La fecha de realización es obligatoria cuando se marca como Realizada'])
                    ->withInput();
            }

            if (!$request->hasFile('fichero_resumen')) {
                return back()
                    ->withErrors(['fichero_resumen' => 'El fichero resumen es obligatorio cuando se marca como Realizada'])
                    ->withInput();
            }
        }


        // Si hay un fichero, guardarlo
        if ($request->hasFile('fichero_resumen')) {
            // Crear carpeta si no existe
            Storage::disk('private')->makeDirectory('ficheros_tareas');

            //Generar un nombre único para el fichero
            $nombreOriginal = $request->file('fichero_resumen')->getClientOriginalName();
            $nombreIdentificativo = $tarea->id . '_' . $nombreOriginal;

            $ruta = $request->file('fichero_resumen')
                ->storeAs('ficheros_tareas', $nombreIdentificativo, 'private');

            $tarea->fichero_resumen = $ruta;
        }

        // Actualizar la tarea
        $tarea->estado = $request->estado;
        $tarea->anotaciones_posteriores = $request->anotaciones_posteriores ?? null;

        // Solo actualizar la fecha de realización si el estado es "Realizada"
        if ($estadoTarea === 'R') {
            $tarea->fecha_realizacion = $fechaRealizacion;
        }

        $tarea->save();

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea completada correctamente');
    }
}
