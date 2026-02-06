<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $empleados = User::all();
        return view('empleados.index', compact('empleados'));

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

        return view('empleados.create');
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
            'dni' => 'required|string|max:20|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_alta' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
            'tipo' => 'required|string|in:administrador,operario',
        ]);

        User::create($request->only(
            'dni',
            'name',
            'email',
            'telefono',
            'direccion',
            'fecha_alta',
            'password',
            'tipo',
        ));

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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('empleados.edit', compact('empleado'));
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
