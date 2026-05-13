<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Rules\ValidarCif;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Servicio REST SiempreColgando S.L.",
    version: "1.0.0",
    description: "API para la gestión de clientes y facturación internacional.",
    contact: new OA\Contact(email: "admin@siemprecolgando.es")
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Servidor Local de Desarrollo"
)]
class ClienteController extends Controller
{
    /**
     * Centralizamos los mensajes para no repetirlos
     */
    private function getMensajes()
    {
        return [
            'cif.required' => 'El CIF es obligatorio',
            'cif.unique' => 'Ya existe un cliente con ese CIF',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El correo electrónico no es válido',
            'correo.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'cuenta_corriente.required' => 'La cuenta corriente es obligatoria',
            'cuenta_corriente.max' => 'La cuenta corriente no puede tener más de 100 caracteres',
            'pais.required' => 'El país es obligatorio',
            'pais.exists' => 'El país seleccionado no es válido',
            'fecha_alta.required' => 'La fecha de alta es obligatoria',
            'fecha_alta.date' => 'La fecha de alta debe ser una fecha válida',
            'importe_cuota_mensual.required' => 'El importe de la cuota es obligatorio',
            'importe_cuota_mensual.numeric' => 'El importe de la cuota debe ser numérico',
            'importe_cuota_mensual.min' => 'El importe de la cuota debe ser mayor o igual a 1',
        ];
    }

    #[OA\Get(
        path: "/api/clientes",
        summary: "Obtener lista de clientes con sus países",
        tags: ["Clientes"],
        responses: [
            new OA\Response(response: 200, description: "Lista de clientes obtenida correctamente")
        ]
    )]
    public function index()
    {
        $clientes = Cliente::with('paisRelacion')->get();
        return response()->json($clientes, Response::HTTP_OK); // 200 OK
    }

    #[OA\Post(
        path: "/api/clientes",
        summary: "Crear un nuevo cliente",
        tags: ["Clientes"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nombre", "cif", "correo", "pais", "importe_cuota_mensual", "fecha_alta"],
                properties: [
                    new OA\Property(property: "nombre", type: "string", example: "Juan Perez"),
                    new OA\Property(property: "cif", type: "string", example: "Z6742090J"),
                    new OA\Property(property: "correo", type: "string", example: "juan@gmail.com"),
                    new OA\Property(property: "telefono", type: "string", example: "600000000"),
                    new OA\Property(property: "cuenta_corriente", type: "string", example: "ES211234..."),
                    new OA\Property(property: "pais", type: "string", example: "ES"),
                    new OA\Property(property: "fecha_alta", type: "string", format: "date", example: "2024-03-20"),
                    new OA\Property(property: "importe_cuota_mensual", type: "number", format: "float", example: 150.50),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Cliente creado"),
            new OA\Response(response: 422, description: "Error de validación")
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cif' => ['required', 'string', 'unique:clientes,cif', new ValidarCif],
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:100',
            'cuenta_corriente' => 'required|string|max:100',
            'pais' => 'required|string|exists:paises,iso2',
            'fecha_alta' => 'required|date',
            'importe_cuota_mensual' => 'required|numeric|min:1',
        ], $this->getMensajes());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY); // 422 Unprocessable Entity
        }

        $data = $validator->validated();
        $data['cif'] = strtoupper(trim($data['cif']));

        // Lógica de moneda automática
        $pais = Pais::where('iso2', $data['pais'])->first();
        $data['moneda'] = $pais->iso_moneda ?? '--';

        $cliente = Cliente::create($data);
        return response()->json($cliente, Response::HTTP_CREATED); // 201 Created
    }

    #[OA\Get(
        path: "/api/clientes/{id}",
        summary: "Obtener detalle de un cliente",
        tags: ["Clientes"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del cliente",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Detalle del cliente"),
            new OA\Response(response: 404, description: "Cliente no encontrado")
        ]
    )]
    public function show(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        return response()->json($cliente, Response::HTTP_OK); // 200 OK
    }

    #[OA\Put(
        path: "/api/clientes/{id}",
        summary: "Actualizar un cliente",
        tags: ["Clientes"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nombre", type: "string"),
                    new OA\Property(property: "cif", type: "string"),
                    new OA\Property(property: "correo", type: "string"),
                    new OA\Property(property: "telefono", type: "string"),
                    new OA\Property(property: "cuenta_corriente", type: "string"),
                    new OA\Property(property: "pais", type: "string"),
                    new OA\Property(property: "importe_cuota_mensual", type: "number"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Cliente actualizado"),
            new OA\Response(response: 404, description: "Cliente no encontrado"),
            new OA\Response(response: 422, description: "Error de validación")
        ]
    )]
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        $validator = Validator::make($request->all(), [
            'cif' => ['required', 'string', 'unique:clientes,cif,' . $cliente->id, new ValidarCif],
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:clientes,correo,' . $cliente->id,
            'cuenta_corriente' => 'required|string|max:100',
            'pais' => 'required|string|exists:paises,iso2',
            'importe_cuota_mensual' => 'required|numeric|min:1',
        ], $this->getMensajes());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY); // 422 Unprocessable Entity
        }

        $data = $validator->validated();
        $data['cif'] = strtoupper(trim($data['cif']));

        // Recalcular moneda si el país cambió
        if (isset($data['pais'])) {
            $pais = Pais::where('iso2', $data['pais'])->first();
            $data['moneda'] = $pais->iso_moneda ?? '--';
        }

        $cliente->update($data);

        return response()->json($cliente, Response::HTTP_OK); // 200 OK
    }

    #[OA\Delete(
        path: "/api/clientes/{id}",
        summary: "Eliminar un cliente",
        tags: ["Clientes"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Cliente eliminado"),
            new OA\Response(response: 404, description: "Cliente no encontrado")
        ]
    )]
    public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $cliente->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204 No Content
    }
}
