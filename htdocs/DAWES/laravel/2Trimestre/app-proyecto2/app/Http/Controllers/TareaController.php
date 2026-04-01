<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfigAvanzada;
use App\Models\Tarea;
use App\Models\User;
use App\Rules\ValidarTelefono;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 * @class TareaController
 *
 * @brief Controlador encargado de la gestión integral de las tareas e incidencias.
 *
 * Este controlador administra el ciclo de vida completo de una tarea:
 * - Listado filtrado según el rol del usuario (Administrador u Operario).
 * - Creación, edición y eliminación de tareas por parte de administradores.
 * - Proceso de finalización de tareas por operarios, incluyendo la subida de archivos justificantes.
 * - Recepción de incidencias externas enviadas por clientes no autenticados.
 *
 * La seguridad se gestiona mediante comprobaciones de rol y middleware, asegurando que 
 * los operarios solo accedan a sus tareas asignadas.
 *
 * @package App\Http\Controllers
 */
class TareaController extends Controller
{
    /**
     * @brief Lista las tareas con paginación y filtros.
     *
     * Recupera las tareas de la base de datos aplicando los siguientes criterios:
     * - **Filtro de Rol**: Los administradores ven todas las tareas; los operarios solo las asignadas a ellos.
     * - **Filtro de Estado**: Permite filtrar por estados específicos (Pendiente, Realizada, etc.).
     * - **Orden**: Las tareas aparecen ordenadas cronológicamente.
     *
     * @param Request $request Contiene los parámetros de filtrado (estado).
     * @return \Illuminate\View\View Vista con el listado de tareas paginado.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $estado = $request->get('estado'); // null si no hay filtro

        $query = Tarea::conRelaciones()->ordenadasPorFecha();

        if ($user->isAdmin()) {
            if ($estado) {
                $query->where('estado', $estado);
            }
        } else {
            $query->paraOperario($user->id);
            if ($estado) {
                $query->where('estado', $estado);
            }
        }

        $tareas = $query->paginate(6)->withQueryString();

        return view('tareas.index', compact('tareas'));
    }

    /**
     * @brief Muestra el detalle exhaustivo de una tarea específica.
     *
     * Verifica que el usuario tenga permisos para visualizar la tarea antes de cargar la vista.
     *
     * @param Tarea $tarea Instancia de la tarea a consultar.
     * @return \Illuminate\View\View Detalle de la tarea.
     */
    public function show(Tarea $tarea)
    {

        $user = Auth::user();

        if ($user->isOperario() && $tarea->operario_id !== $user->id) {
            abort(403);
        }

        return view('tareas.show', compact('tarea'));
    }

    /**
     * @brief Gestiona la descarga segura de archivos adjuntos a la tarea.
     *
     * El método comprueba que el archivo exista en el disco privado y que el 
     * usuario tenga permisos (ser administrador o el operario asignado).
     *
     * @param Tarea $tarea Tarea de la cual se desea descargar el resumen.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Descarga del fichero.
     */
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

    /**
     * @brief Proporciona el listado de provincias españolas y sus códigos.
     *
     * Método auxiliar utilizado para validar códigos postales y rellenar selectores en formularios.
     *
     * @return array Listado asociativo ['código' => 'nombre de provincia'].
     */
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
     * @brief Muestra el formulario para crear una nueva tarea.
     *
     * Carga los datos necesarios para el formulario de alta (clientes y operarios disponibles).
     *
     * @note Solo accesible para administradores.
     * @return \Illuminate\View\View Formulario de creación.
     */
    public function create()
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::ordenadosPorNombre()->get();
        $operarios = User::operarios()->ordenadosPorNombre()->get();

        return view('tareas.create', [
            'clientes' => $clientes,
            'operarios' => $operarios,
            'provincias' => $this->provincias(),
        ]);
    }


    /**
     * @brief Procesa el almacenamiento de una nueva tarea.
     *
     * Realiza una validación exhaustiva de los datos:
     * - Coherencia entre Código Postal y provincia.
     * - Validación de formatos de teléfono y correo.
     * - Restricción de fechas según el estado de la tarea.
     *
     * @param Request $request Datos enviados desde el formulario.
     * @return \Illuminate\Http\RedirectResponse Redirección al listado con mensaje de éxito o error.
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
            'telefono_contacto' => ['required', 'string', 'max:20', new ValidarTelefono],
            'descripcion' => 'required|string|min:10',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => 'required|in:' . implode(',', array_keys($this->provincias())),
            'estado' => 'required|in:B,P,R,C',
            'fecha_realizacion' => [
                // Es obligatorio solo si el estado es 'Realizada' (R)
                'required_if:estado,R,P,B',
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $estado = $request->estado;
                    $hoy = now()->startOfDay();
                    $fechaInput = \Carbon\Carbon::parse($value)->startOfDay();

                    // Si es Pendiente (P) o Esperando (B), la fecha DEBE ser hoy o futura
                    if (in_array($estado, ['P', 'B'])) {
                        if ($fechaInput->lt($hoy)) {
                            $fail('Para tareas pendientes o a la espera de aprobación, la fecha no puede ser anterior a hoy.');
                        }
                    }
                },
            ],
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
            'fecha_realizacion.required_if' => 'Debes indicar una fecha de realización para este estado',
            'fecha_realizacion.date' => 'La fecha de realización no es válida.',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
        ]);

        if (substr($request->codigo_postal, 0, 2) !== $request->provincia) {
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

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente');
    }



    /**
     * @brief Muestra el formulario de edición de una tarea.
     *
     * @param Tarea $tarea Tarea a editar.
     * @return \Illuminate\View\View Formulario de edición con datos precargados.
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
     * @brief Actualiza los datos de una tarea existente.
     *
     * Implementa lógica de limpieza de datos (ej. eliminar fecha si se cancela) 
     * y validaciones de coherencia geográfica similares a {@see store}.
     *
     * @param Request $request Datos actualizados.
     * @param Tarea $tarea Instancia del modelo a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirección tras la actualización.
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
            'telefono_contacto' => ['required', 'string', 'max:20', new ValidarTelefono],
            'descripcion' => 'required|string|min:10',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'poblacion' => 'nullable|string|max:100',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => 'required|in:' . implode(',', array_keys($this->provincias())),
            'estado' => 'required|in:B,P,R,C',
            'fecha_realizacion' => [
                // Es obligatorio solo si el estado es 'Realizada' (R)
                'required_if:estado,R,P,B',
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $estado = $request->estado;
                    $hoy = now()->startOfDay();
                    $fechaInput = $value ? \Carbon\Carbon::parse($value)->startOfDay() : null;

                    // Si es Pendiente (P) o Esperando (B), la fecha DEBE ser hoy o futura
                    if (in_array($estado, ['P', 'B'])) {
                        if ($fechaInput->lt($hoy)) {
                            $fail('Para tareas pendientes o a la espera de aprobación, la fecha no puede ser anterior a hoy.');
                        }
                    }
                },
            ],
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
            'fecha_realizacion.required_if' => 'Debes indicar una fecha para este estado.',
            'fecha_realizacion.date' => 'La fecha de realización no es válida.',
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

        $data = $request->only([
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
        ]);

        if ($request->estado === 'C') {
            $data['fecha_realizacion'] = null;
        }

        // Actualizamos el modelo con el array modificado
        $tarea->update($data);

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada correctamente');
    }

    /**
     * @brief Muestra la confirmación de borrado de una tarea.
     *
     * @param Tarea $tarea Tarea que se pretende eliminar.
     * @return \Illuminate\View\View Vista de confirmación.
     */
    public function confirmDelete(Tarea $tarea)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('tareas.confirmDelete', compact('tarea'));
    }

    /**
     * @brief Elimina permanentemente una tarea del sistema.
     *
     * @param Tarea $tarea Tarea a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección al inicio con éxito.
     */
    public function destroy(Tarea $tarea)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $tarea->delete();

        return redirect('/')->with('success', 'Tarea eliminada correctamente');
    }

    /**
     * @brief Muestra el formulario para que un operario complete su tarea.
     *
     * @param Tarea $tarea Tarea asignada al operario.
     * @return \Illuminate\View\View Formulario de finalización.
     */
    public function completeForm(Tarea $tarea)
    {
        $user = Auth::user();

        // Verificar que el usuario es el operario asignado
        if ($tarea->operario_id !== $user->id) {
            abort(403, 'No tienes permiso para completar esta tarea.');
        }

        return view('tareas.completeForm', compact('tarea'));
    }

    /**
     * @brief Procesa la finalización de una tarea por parte del operario.
     *
     * Gestiona la actualización del estado final y la **subida del archivo justificante**:
     * - El archivo es obligatorio si el estado es 'Realizada'.
     * - El archivo se almacena en el disco privado con un nombre único identificativo.
     *
     * @param Request $request Datos del cierre y archivo adjunto.
     * @param Tarea $tarea Tarea que se completa.
     * @return \Illuminate\Http\RedirectResponse Redirección al listado.
     */
    public function complete(Request $request, Tarea $tarea)
    {
        $user = Auth::user();

        if ($tarea->operario_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:R,C',
            'anotaciones_posteriores' => 'nullable|string|min:5',
            'fecha_realizacion' => [
                // Es obligatorio solo si el estado es 'Realizada' (R)
                'required_if:estado,R',
                'nullable',
                'date',
            ],
            'fichero_resumen' => [
                'required_if:estado,R', // Obligatorio si es Realizada
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,txt,png,jpg,jpeg',
                'max:5120'
            ],
        ], [
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
            'anotaciones_posteriores.min' => 'Las anotaciones posteriores deben tener al menos 5 caracteres',
            'fecha_realizacion.required_if' => 'Si la tarea está realizada, debes indicar cuándo se hizo.',
            'fichero_resumen.required_if' => 'Debe adjuntar un fichero justificante si la tarea está realizada.',
            'fichero_resumen.file' => 'El fichero debe ser un archivo válido',
            'fichero_resumen.mimes' => 'El fichero debe ser un archivo de tipo: pdf, doc, docx, xls, xlsx o txt',
            'fichero_resumen.max' => 'El fichero no puede superar los 5MB de tamaño',
        ]);

        // Limpieza lógica: si es cancelada, nos aseguramos de que la fecha sea null
        if ($request->estado === 'C') {
            $data['fecha_realizacion'] = null;
        } else {
            $tarea->fecha_realizacion = $request->fecha_realizacion;
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

        $tarea->save();

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea completada correctamente');
    }

    /**
     * @brief Muestra el formulario de incidencias público para clientes.
     *
     * Permite que clientes externos soliciten servicios sin estar autenticados.
     *
     * @return \Illuminate\View\View Formulario de incidencia externa.
     */
    public function createFromCliente()
    {
        // No verificamos la autenticacion porque es para clientes no logueados
        return view('incidencias.create', [
            'provincias' => $this->provincias(),
        ]);
    }

    /**
     * @brief Procesa y registra una incidencia enviada por un cliente.
     *
     * Realiza un proceso de verificación previo:
     * - Comprueba que el **CIF** exista en el sistema.
     * - Comprueba que el **Teléfono** proporcionado coincida con el del cliente registrado.
     * - Crea la tarea marcada como pendiente y sin operario asignado para su posterior revisión.
     *
     * @param Request $request Datos de contacto e incidencia.
     * @return \Illuminate\Http\RedirectResponse Redirección al login con aviso de recepción.
     */
    public function storeFromCliente(Request $request)
    {
        $clientePorCif = Cliente::where('cif', $request->cif)->first();

        // Comprobar si existe algun cliente con ese CIF
        if (!$clientePorCif) {
            return back()
                ->withErrors(['cif' => 'El CIF introducido no figura en nuestros registros.'])
                ->withInput();
        }

        // Si el CIF existe, comprobar si el teléfono coincide con ese cliente
        if ($clientePorCif->telefono !== $request->telefono_cliente) {
            return back()
                ->withErrors(['telefono_cliente' => 'El teléfono no coincide con el registrado para este CIF.'])
                ->withInput();
        }

        // Si pasa ambas, ya tenemos nuestro cliente
        $cliente = $clientePorCif;

        // Validación de los campos del formulario
        $request->validate([
            'persona_contacto' => 'required|string|max:100',
            'telefono_contacto' => ['required', 'string', 'max:20', new ValidarTelefono],
            'descripcion' => 'required|string|min:1',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'required|string|max:100',
            'poblacion' => 'required|string|max:100',
            'codigo_postal' => 'required|regex:/^\d{5}$/',
            'provincia' => 'required|in:' . implode(',', array_keys($this->provincias())),
            'estado' => 'required|in:B,P,R,C',
            'fecha_realizacion' => 'required|date|after_or_equal:today',
            'anotaciones_anteriores' => 'nullable|string',
        ], [
            'persona_contacto.required' => 'La persona de contacto es obligatoria',
            'persona_contacto.max' => 'La persona de contacto no puede tener más de 100 caracteres',
            'telefono_contacto.required' => 'El teléfono de contacto es obligatorio',
            'telefono_contacto.max' => 'El teléfono de contacto no puede tener más de 20 caracteres',
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 1 caracter',
            'descripcion.string' => 'La descripción debe ser un texto válido',
            'correo_contacto.required' => 'El correo electrónico es obligatorio',
            'correo_contacto.email' => 'El correo electrónico no es válido',
            'correo_contacto.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'direccion.required' => 'La dirección es obligatoria',
            'direccion.max' => 'La dirección no puede tener más de 100 caracteres',
            'poblacion.required' => 'La población es obligatoria',
            'poblacion.max' => 'La población no puede tener más de 100 caracteres',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'codigo_postal.regex' => 'El código postal debe tener exactamente 5 dígitos.',
            'provincia.required' => 'La provincia es obligatoria',
            'fecha_realizacion.required' => 'La fecha de realización es obligatoria',
            'fecha_realizacion.after_or_equal' => 'La fecha de realización debe ser posterior o igual a la fecha actual',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
        ]);

        // Validar que la provincia corresponda con el código postal
        if (substr($request->codigo_postal, 0, 2) !== $request->provincia) {
            return back()->withErrors(['provincia' => 'La provincia no corresponde con el CP'])->withInput();
        }

        // Asignar automáticamente el cliente_id encontrado y dejar operario_id como null
        Tarea::create([
            'cliente_id'        => $cliente->id,
            'operario_id'       => null,
            'persona_contacto'  => $request->persona_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'descripcion'       => $request->descripcion,
            'correo_contacto'   => $request->correo_contacto,
            'direccion'         => $request->direccion,
            'poblacion'         => $request->poblacion,
            'codigo_postal'     => $request->codigo_postal,
            'provincia'         => $request->provincia,
            'estado'            => $request->estado ?? 'P',
            'fecha_realizacion' => $request->fecha_realizacion,
            'anotaciones_anteriores' => $request->anotaciones_anteriores,
        ]);

        return redirect()->route('login')->with('success', 'Incidencia registrada. Un administrador la revisará pronto.');
    }
}
