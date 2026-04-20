<?php

namespace App\Http\Controllers;

use App\Models\ConfigAvanzada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Rules\ValidarDni;
use App\Rules\ValidarTelefono;

/**
 * @class UserController
 *
 * @brief Controlador para la gestión de usuarios y empleados del sistema.
 *
 * Esta clase centraliza todas las operaciones administrativas relacionadas con los usuarios:
 * - Listado de empleados con filtros de estado (activo/baja).
 * - Altas, modificaciones y eliminaciones de personal.
 * - Gestión de estados de disponibilidad (dar de baja o reactivar).
 * - Gestión del perfil personal del usuario autenticado.
 *
 * Incluye validaciones estrictas para DNI, teléfonos y restricciones de seguridad para 
 * evitar que un administrador se elimine a sí mismo o a usuarios con tareas pendientes.
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @brief Muestra el listado de empleados paginado.
     *
     * Permite filtrar la lista por estado (activo/baja) y excluye al usuario 
     * actualmente autenticado de la lista para evitar autogestión accidental.
     *
     * @param Request $request Contiene el parámetro 'estado' para el filtrado.
     * @return \Illuminate\View\View Vista con la colección de empleados.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $estado = $request->get('estado', '');

        $query = User::excluyendo($user->id)->ordenadosPorNombre();

        if ($estado === 'activo') {
            $query->activos();
        } elseif ($estado === 'baja') {
            $query->deBaja();
        }

        $empleados = $query->paginate(5)->withQueryString();

        return view('empleados.index', compact('empleados'));
    }

    /**
     * @brief Muestra el formulario para registrar un nuevo empleado.
     *
     * @note Requiere privilegios de administrador.
     * @return \Illuminate\View\View Formulario de creación.
     */
    public function create()
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('empleados.create');
    }

    /**
     * @brief Almacena un nuevo usuario en la base de datos.
     *
     * Realiza validaciones de:
     * - **DNI**: Formato y unicidad.
     * - **Nombre**: Solo caracteres alfabéticos.
     * - **Seguridad**: Contraseña mínima de 8 caracteres y confirmación.
     *
     * @param Request $request Datos del nuevo empleado.
     * @return \Illuminate\Http\RedirectResponse Redirección al listado con éxito.
     */
    public function store(Request $request)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'dni' => ['required', 'string', 'unique:users', new ValidarDni],
            'name' => 'required|string|max:255|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\-]+$/',
            'email' => 'required|email|unique:users',
            'telefono' => ['required', 'max:20', new ValidarTelefono],
            'direccion' => 'required|string|regex:/[a-zA-Z]/|max:255',
            'fecha_alta' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
            'tipo' => 'required|string|in:administrador,operario',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Ya existe un usuario con ese DNI',
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'name.regex' => 'El nombre solo puede contener letras y espacios (sin números)',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'direccion.required' => 'La dirección es obligatoria',
            'direccion.regex' => 'La dirección debe contener al menos una letra (no puede ser solo números)',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres',
            'fecha_alta.required' => 'La fecha de alta es obligatoria',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La contraseña no coincide',
            'tipo.required' => 'El tipo de usuario es obligatorio',
            'tipo.in' => 'El tipo de usuario seleccionado no es válido',
        ]);

        User::create($validated);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @brief Muestra el formulario de edición para un empleado existente.
     *
     * @param User $empleado Modelo del empleado a editar.
     * @return \Illuminate\View\View Vista de edición.
     */
    public function edit(User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('empleados.edit', compact('empleado'));
    }

    /**
     * @brief Actualiza la información de un empleado.
     *
     * Permite actualizaciones parciales (solo los campos presentes en la solicitud).
     * Gestiona la unicidad del DNI y Email ignorando el registro actual.
     *
     * @param Request $request Datos actualizados.
     * @param User $empleado Instancia del usuario a modificar.
     * @return \Illuminate\Http\RedirectResponse Redirección al listado.
     */
    public function update(Request $request, User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'dni' => ['required', 'string', 'unique:users,dni,' . $empleado->id, new ValidarDni],
            'name' => 'nullable|string|max:255|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\-]+$/',
            'email' => 'nullable|email|unique:users,email,' . $empleado->id,
            'telefono' => ['nullable', 'string', 'max:20', new ValidarTelefono],
            'direccion' => 'nullable|string|regex:/[a-zA-Z]/|max:255',
            'fecha_alta' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
            'tipo' => 'nullable|string|in:administrador,operario',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Ya existe un usuario con ese DNI',
            'name.regex' => 'El nombre solo puede contener letras y espacios',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email.unique' => 'Este correo electrónico ya está en uso',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'direccion.regex' => 'La dirección debe contener al menos una letra',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
            'tipo.in' => 'El tipo de usuario seleccionado no es válido',
        ]);

        $data = [];

        // Solo actualizar los campos que se hayan enviado
        if ($request->filled('dni')) {
            $data['dni'] = $request->dni;
        }

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }

        if ($request->filled('telefono')) {
            $data['telefono'] = $request->telefono;
        }

        if ($request->filled('direccion')) {
            $data['direccion'] = $request->direccion;
        }

        if ($request->filled('fecha_alta')) {
            $data['fecha_alta'] = $request->fecha_alta;
        }

        if ($request->filled('tipo')) {
            $data['tipo'] = $request->tipo;
        }

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $empleado->update($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * @brief Muestra la confirmación para eliminar a un empleado.
     *
     * @param User $empleado Empleado a eliminar.
     * @return \Illuminate\View\View Vista de confirmación.
     */
    public function confirmDelete(User $empleado)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('empleados.confirmDelete', compact('empleado'));
    }

    /**
     * @brief Elimina un usuario de la base de datos.
     *
     * **Restricciones**:
     * - No permite que un usuario se elimine a sí mismo.
     * - No permite eliminar usuarios que tengan tareas asignadas en el sistema.
     *
     * @param User $empleado Empleado a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error.
     */
    public function destroy(User $empleado)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($empleado->id == $user->id) {
            return redirect()->route('empleados.index')
                ->with('error', 'No puedes eliminarte a ti mismo.');
        }

        if ($empleado->tareasAsignadas()->count() > 0) {
            return redirect()->route('empleados.index')
                ->with('error', 'No se puede eliminar este empleado porque tiene tareas asignadas. Asigna sus tareas a otro operario antes de eliminarlo.');
        }

        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }

    /**
     * @brief Muestra la confirmación para dar de baja a un empleado.
     *
     * @param User $empleado Empleado a dar de baja.
     * @return \Illuminate\View\View Vista de confirmación.
     */
    public function confirmBaja(User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('empleados.confirmBaja', compact('empleado'));
    }

    /**
     * @brief Gestiona el proceso de dar de baja (desactivar) a un empleado.
     * * A diferencia de `destroy`, este método mantiene el registro pero marca una `fecha_baja`,
     * lo cual impide que el usuario inicie sesión pero mantiene su histórico.
     *
     * @param User $empleado Empleado a desactivar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function baja(User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($empleado->id == $user->id) {
            return redirect()->route('empleados.index')
                ->with('error', 'No puedes darte de baja a ti mismo.');
        }

        if ($empleado->tareasAsignadas()->count() > 0) {
            return redirect()->route('empleados.index')
                ->with('error', 'No se puede bajar este empleado porque tiene tareas asignadas. Asigna sus tareas a otro operario antes de bajarlo.');
        }

        // No permitir dar de baja si ya está dado de baja
        if ($empleado->isBaja()) {
            return redirect()->route('empleados.index')
                ->with('error', 'Este empleado ya está dado de baja.');
        }

        $empleado->update([
            'fecha_baja' => now(),
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado dado de baja correctamente.');
    }

    /**
     * @brief Muestra la confirmación para dar de alta a un empleado.
     *
     * @param User $empleado Empleado a dar de alta.
     * @return \Illuminate\View\View Vista de confirmación.
     */
    public function confirmAlta(User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if (!$empleado->isBaja()) {
            return redirect()->route('empleados.index')
                ->with('error', 'Este empleado ya está activo.');
        }

        return view('empleados.confirmAlta', compact('empleado'));
    }

    /**
     * @brief Reactiva a un empleado que estaba dado de baja.
     *
     * Limpia el campo `fecha_baja` para permitir que el usuario acceda de nuevo al sistema.
     *
     * @param User $empleado Empleado a reactivar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function alta(User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $empleado->update([
            'fecha_baja' => null,
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado reactivado correctamente.');
    }

    /**
     * @brief Muestra la vista de edición de perfil para el usuario actual.
     *
     * @return \Illuminate\View\View Vista del perfil personal.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

    /**
     * @brief Procesa la actualización de los datos del propio usuario.
     *
     * Permite a cualquier usuario (admin u operario) cambiar sus datos de contacto y contraseña.
     * Al finalizar, redirige a su panel correspondiente según su rol.
     *
     * @param Request $request Datos del perfil.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'dni' => ['required', 'string', 'unique:users,dni,' . $user->id, new ValidarDni],
            'name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\-]+$/'],
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'telefono' => ['nullable', 'string', 'max:20', new ValidarTelefono],
            'direccion' => 'nullable|string|max:255|regex:/[a-zA-Z]/',
            'fecha_alta' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya está registrado en el sistema',
            'name.regex' => 'El nombre solo puede contener letras y espacios',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email.unique' => 'Este correo electrónico ya está en uso por otro usuario',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'direccion.regex' => 'La dirección debe contener al menos una letra',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
        ]);

        $data = [];

        // Solo actualizar los campos que se hayan enviado
        if ($request->filled('dni')) $data['dni'] = $request->dni;
        if ($request->filled('name')) $data['name'] = $request->name;
        if ($request->filled('email')) $data['email'] = $request->email;
        if ($request->filled('telefono')) $data['telefono'] = $request->telefono;
        if ($request->filled('direccion')) $data['direccion'] = $request->direccion;
        if ($request->filled('fecha_alta')) $data['fecha_alta'] = $request->fecha_alta;

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        if ($user->isAdmin()) {
            return redirect()->route('empleados.index')->with('success', 'Perfil actualizado correctamente.');
        } else {
            return redirect()->route('tareas.index')->with('success', 'Perfil actualizado correctamente.');
        }
    }
}
