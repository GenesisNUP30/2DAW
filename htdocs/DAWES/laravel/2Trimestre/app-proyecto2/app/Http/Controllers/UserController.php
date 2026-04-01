<?php

namespace App\Http\Controllers;

use App\Models\ConfigAvanzada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Rules\ValidarDni;
use App\Rules\ValidarTelefono;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
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
     * Confirmar la eliminación de un empleado
     *
     * @param User $empleado
     * @return void
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
     * Remove the specified resource from storage.
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

    public function confirmBaja(User $empleado)
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('empleados.confirmBaja', compact('empleado'));
    }

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
     * Dar de alta (reactivar) a un empleado
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

    public function profile()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

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
