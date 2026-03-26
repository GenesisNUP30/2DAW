<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; border-bottom: 1px solid #ccc; }
        .datos { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA #{{ $factura->id }}</h1>
    </div>
    <div class="datos">
        <p><strong>Cliente:</strong> {{ $factura->cliente->nombre }}</p>
        <p><strong>CIF:</strong> {{ $factura->cliente->cif }}</p>
        <p><strong>Fecha:</strong> {{ $factura->created_at->format('d/m/Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $factura->concepto }}</td>
                <td>{{ number_format($factura->importe, 2) }} {{ $factura->cliente->moneda }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>