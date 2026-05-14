<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Mail\CuotaCreadaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cuota;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @class CuotaController
 * * @brief Controlador para la gestión financiera de cuotas y remesas.
 * * Esta clase se encarga de:
 * - Listar cuotas diferenciando entre mensuales (ordinarias) y excepcionales.
 * - Generar remesas automáticas para todos los clientes activos a principio de mes.
 * - Gestionar el ciclo de vida de los pagos (emisión, pago, edición).
 * - Administrar la papelera de cuotas mediante el uso de Soft Deletes.
 * * @note Todas las operaciones financieras están restringidas exclusivamente a usuarios Administradores.
 * * @package App\Http\Controllers
 */
class CuotaController extends Controller
{
    /**
     * @brief Muestra el listado de cuotas organizado por tipo.
     * * Implementa una paginación doble en la misma vista:
     * - **Cuotas Mensuales**: Generadas por el sistema.
     * - **Cuotas Excepcionales**: Creadas manualmente por servicios extra.
     * * @return \Illuminate\View\View Vista con las colecciones de cuotas paginadas independientemente.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $cuotasMensuales = Cuota::mensuales()
            ->conRelaciones()
            ->ordenadasPorFecha()
            ->paginate(3, ['*'], 'mensuales');

        $cuotasExcepcionales = Cuota::excepcionales()
            ->conRelaciones()
            ->ordenadasPorFecha()
            ->paginate(3, ['*'], 'excepcionales');

        return view('cuotas.index', compact('cuotasMensuales', 'cuotasExcepcionales'));
    }

    /**
     * @brief Muestra el formulario para crear una cuota excepcional.
     * * @return \Illuminate\View\View Formulario con la lista de clientes disponibles.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::ordenadosPorNombre()->get();

        return view('cuotas.create', compact('clientes'));
    }

    /**
     * @brief Almacena una nueva cuota manual en el sistema.
     * * Valida que el importe sea positivo y que la fecha de pago (si existe) no sea
     * anterior a la fecha de emisión. Por defecto, estas cuotas se marcan como 'excepcional'.
     * * @param Request $request Datos de la cuota (cliente, concepto, importe, etc.).
     * @return \Illuminate\Http\RedirectResponse Redirección al índice con éxito.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:50',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:1',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:255',
        ], [
            'cliente_id.required' => 'El cliente es obligatorio',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.max' => 'El concepto no puede tener más de 50 caracteres',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser numérico',
            'importe.min' => 'El importe debe ser mayor o igual a 1',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha valida',
            'fecha_pago.after_or_equal' => 'La fecha de pago debe ser igual o posterior a la fecha de emisión',
            'notas.max' => 'Las notas no pueden superar los 255 caracteres',
        ]);

        $validated['tipo'] = 'excepcional';
        $cuota = Cuota::create($validated);

        // --- ENVIAR CORREO INFORMATIVO ---
        try {
            if ($cuota->cliente && $cuota->cliente->correo) {
                Mail::to($cuota->cliente->correo)->send(new CuotaCreadaMail($cuota));
            }
        } catch (\Exception $e) {
            Log::error("Error enviando correo de cuota excepcional: " . $e->getMessage());
        }

        return redirect()->route('cuotas.index')->with('success', 'Cuota creada correctamente y correo de notificación enviado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @brief Genera automáticamente la remesa mensual para todos los clientes activos.
     * * **Lógica de negocio**:
     * - Itera sobre todos los clientes activos.
     * - Comprueba si ya existe una cuota mensual para el mes y año actuales para evitar duplicados.
     * - Crea la cuota usando el `importe_cuota_mensual` definido en la ficha del cliente.
     * * @return \Illuminate\Http\RedirectResponse Notificación con el número de cuotas creadas.
     */
    public function generarRemesa()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::activos()->get();

        $mes = now()->month;
        $anio = now()->year;

        $cuotasCreadas = 0;
        foreach ($clientes as $cliente) {
            $existe = Cuota::where('cliente_id', $cliente->id)
                ->whereMonth('fecha_emision', $mes)
                ->whereYear('fecha_emision', $anio)
                ->exists();

            if (!$existe) {
                $cuota = Cuota::create([
                    'cliente_id' => $cliente->id,
                    'concepto' => "Cuota mes de " . \Carbon\Carbon::create($anio, $mes, 1)->format('d/m/Y'),
                    'fecha_emision' => \Carbon\Carbon::create($anio, $mes, 1),
                    'importe' => $cliente->importe_cuota_mensual,
                    'fecha_pago' => null,
                    'tipo' => 'mensual',
                    'notas' => "Cuota generada automáticamente",
                ]);
                $cuotasCreadas++;

                // --- ENVIAR CORREO INFORMATIVO ---
                try {
                    if ($cliente->correo) {
                        Mail::to($cliente->correo)->send(new CuotaCreadaMail($cuota));
                    }
                } catch (\Exception $e) {
                    Log::error("Error enviando correo en remesa para cliente {$cliente->id}: " . $e->getMessage());
                }
            }
        }
        return redirect()->route('cuotas.index')->with('success', "Remesa mensual generada:  $cuotasCreadas cuotas mensuales");
    }

    /**
     * @brief Muestra el formulario para editar una cuota existente.
     * * @param Cuota $cuota Instancia de la cuota a editar.
     * @return \Illuminate\View\View
     */
    public function edit(Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::ordenadosPorNombre()->get();

        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

    /**
     * @brief Actualiza la información de una cuota.
     * * Permite marcar una cuota como pagada asignándole una `fecha_pago`.
     * * @param Request $request
     * @param Cuota $cuota
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) abort(403);

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:50',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:1',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:255',
        ], [
            'cliente.required' => 'El cliente es obligatorio',
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.max' => 'El concepto no puede tener más de 50 caracteres',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser un número válido',
            'importe.min' => 'El importe debe ser mayor o igual a 0',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha valida',
            'fecha_pago.after_or_equal' => 'La fecha de pago debe ser igual o posterior a la fecha de emisión',
            'notas.max' => 'Las notas no pueden superar los 255 caracteres',
        ]);

        // DETECTAR SI ES EL MOMENTO DEL PAGO
        $seAcabaDePagar = ($cuota->fecha_pago === null && $request->fecha_pago !== null);

        // Actualizamos los datos
        $cuota->fill($validated);

        // LÓGICA DE CONVERSIÓN DE MONEDA (PROBLEMA 4.1)
        if ($seAcabaDePagar) {
            // Obtenemos la moneda del cliente a través de la relación
            $monedaLocal = strtolower($cuota->cliente->moneda ?? 'eur');

            if ($monedaLocal !== 'eur') {
                try {
                    // Consumo de API externa con HttpClient
                    $url = "https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/{$monedaLocal}.json";
                    $response = Http::get($url);

                    if ($response->successful()) {
                        $exchangeData = $response->json();
                        $ratio = $exchangeData[$monedaLocal]['eur'];

                        // Guardamos el importe convertido
                        $cuota->importe_euros = $cuota->importe * $ratio;
                    }
                } catch (\Exception $e) {
                    Log::error("Error en conversión de moneda: " . $e->getMessage());
                }
            } else {
                // Si es EUR, el valor es idéntico
                $cuota->importe_euros = $cuota->importe;
            }
        }

        $cuota->save();

        // ACTUALIZAR FACTURA SI EXISTE 
        if ($cuota->factura) {
            $cuota->factura->update(['importe_euros' => $cuota->importe_euros]);
        }

        return redirect()->route('cuotas.index')->with('success', 'Cuota actualizada correctamente.');
    }

    /**
     * @brief Muestra la vista de confirmación para eliminar una cuota.
     * * @param Cuota $cuota
     * @return \Illuminate\View\View
     */
    public function confirmDelete(Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('cuotas.confirmDelete', compact('cuota'));
    }

    /**
     * @brief Elimina (Soft Delete) una cuota del sistema.
     * * **Nota importante**: Si la cuota ya tiene una factura asociada, se permite el borrado
     * lógico (Soft Delete) pero se advierte que la factura permanecerá en el sistema para
     * mantener la integridad contable.
     * * @param Cuota $cuota
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($cuota->factura()->exists()) {
            $cuota->delete(); // Se borra lógicamente
            return redirect()->route('cuotas.index')
                ->with('info', 'La cuota se ha marcado como eliminada, pero la factura asociada permanece en el sistema.');
        }

        $cuota->delete();

        return redirect()->route('cuotas.index')->with('success', 'Cuota eliminada correctamente.');
    }

    /**
     * @brief Muestra la papelera de reciclaje de cuotas.
     * * Recupera únicamente los registros que han sido eliminados mediante Soft Deletes
     * (`deleted_at` no es nulo).
     * * @return \Illuminate\View\View Vista con la lista de cuotas eliminadas.
     */
    public function papelera()
    {

        if (!auth()->user()->isAdmin()) abort(403);


        // onlyTrashed() filtra solo las que tienen deleted_at NOT NULL
        $cuotasEliminadas = Cuota::onlyTrashed()
            ->conRelaciones()
            ->orderByDesc('deleted_at')
            ->paginate(3);

        return view('cuotas.papelera', compact('cuotasEliminadas'));
    }

    /**
     * @brief Restaura una cuota previamente eliminada de la papelera.
     * * @param int|string $id ID de la cuota a restaurar.
     * @return \Illuminate\Http\RedirectResponse Redirección a la papelera.
     */
    public function restore($id)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        // Buscamos en la papelera específicamente
        $cuota = Cuota::onlyTrashed()->findOrFail($id);
        $cuota->restore();

        return redirect()->route('cuotas.papelera')
            ->with('success', 'Cuota restaurada correctamente.');
    }
}
