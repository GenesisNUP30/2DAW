<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ConfigAvanzada;
use App\Models\Cuota;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @class HomeController
 *
 * @brief Controlador principal para el panel de control (dashboard) de la aplicación.
 *
 * Este controlador se encarga de centralizar y mostrar la información estadística
 * y operativa más relevante para el usuario tras iniciar sesión:
 * - Resumen cuantitativo de entidades activas (clientes, operarios).
 * - Estado de las tareas y cuotas (pendientes, próximas).
 * - Gestión de visualización de incidencias recientes sin asignar.
 *
 * Sirve como la página de inicio predeterminada para los usuarios autenticados.
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @brief Crea una nueva instancia del controlador.
     *
     * Inicializa el controlador aplicando el middleware de autenticación,
     * garantizando que todos los métodos dentro de esta clase solo sean
     * accesibles para usuarios que hayan iniciado sesión correctamente.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @brief Muestra el panel de control principal (Dashboard).
     *
     * Este método recopila diversos indicadores de rendimiento y listados críticos:
     * - **Estadísticas**: Conteo de clientes activos, operarios activos, tareas y cuotas pendientes.
     * - **Tareas Próximas**: Recupera las 5 tareas pendientes más cercanas en fecha.
     * - **Finanzas**: Calcula el importe total pendiente y recupera las 3 cuotas más recientes.
     * - **Incidencias**: Filtra incidencias que aún no han sido asignadas a ningún operario.
     *
     * Utiliza los modelos {@see Cliente}, {@see User}, {@see Tarea} y {@see Cuota} 
     * mediante scopes definidos en ellos para simplificar las consultas.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * Devuelve la vista 'home' con el array de datos compactados.
     */
    public function index()
    {
        // Conteo de registros activos y pendientes
        $clientesActivos = Cliente::activos()->count();
        $operariosActivos = User::operarios()->activos()->count();
        $tareasPendientes = Tarea::pendientes()->count();
        $cuotasPendientes = Cuota::pendientes()->count();

        // Obtención de tareas próximas a vencer
        $tareasProximas = Tarea::pendientes()
            ->conRelaciones()
            ->tareasProximas()
            ->limit(5)
            ->get();

        // Resumen financiero de cuotas
        $importePendiente = Cuota::pendientes()->sum('importe');
        $cuotas = Cuota::pendientes()
            ->conRelaciones()
            ->ordenadasPorFecha()
            ->limit(3)
            ->get();

        // Filtrado de incidencias críticas sin asignar
        $incidenciasRecientes = Tarea::conRelaciones()
            ->incidenciasSinAsignar()
            ->limit(3)
            ->get();

        return view('home', compact(
            'clientesActivos',
            'operariosActivos',
            'tareasPendientes',
            'cuotasPendientes',
            'tareasProximas',
            'cuotas',
            'importePendiente',
            'incidenciasRecientes'
        ));
    }
}