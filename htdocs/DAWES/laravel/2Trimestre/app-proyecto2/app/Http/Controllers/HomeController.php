<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clientesActivos = Cliente::activos()->count();
        $operariosActivos = User::operarios()->activos()->count();
        $tareasPendientes = Tarea::pendientes()->count();
        $cuotasPendientes = Cuota::pendientes()->count();

        $tareasProximas = Tarea::pendientes()
            ->conRelaciones()
            ->tareasProximas()
            ->limit(5)
            ->get();

        $importePendiente = Cuota::pendientes()->sum('importe');
        $cuotas = Cuota::pendientes()
            ->conRelaciones()
            ->ordenadasPorFecha()
            ->limit(5)
            ->get();

        return view('home', compact(
            'clientesActivos',
            'operariosActivos',
            'tareasPendientes',
            'cuotasPendientes',
            'tareasProximas',
            'cuotas',
            'importePendiente'
        ));
    }
}
