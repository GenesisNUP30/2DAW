<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; color: #333; line-height: 1.6; }
        .container { padding: 20px; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
        .header h1 { margin: 0; color: #2c3e50; text-transform: uppercase; }
        .section { margin-bottom: 20px; }
        .datos-empresa { float: left; width: 50%; }
        .datos-cliente { float: right; width: 40%; text-align: right; }
        .clear { clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; padding: 12px; text-align: left; }
        td { border-bottom: 1px solid #dee2e6; padding: 12px; }
        .total { font-weight: bold; font-size: 18px; text-align: right; margin-top: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Factura de Servicio</h1>
            <p>Nº de Factura: <strong>{{ $factura->numero_factura }}</strong></p>
        </div>

        <div class="section">
            <div class="datos-empresa">
                <strong>SiempreColgando S.L.</strong><br>
                CIF: B-12345678<br>
                Calle Industria, 45, Huelva<br>
                contacto@siemprecolgando.es
            </div>
            <div class="datos-cliente">
                <strong>CLIENTE:</strong><br>
                {{ $factura->cliente_nombre }}<br>
                CIF: {{ $factura->cliente_cif }}<br>
                Fecha: {{ $factura->created_at->format('d/m/Y') }}
            </div>
            <div class="clear"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $factura->concepto }}</td>
                    <td style="text-align: right;">{{ number_format($factura->importe, 2, ',', '.') }} {{ $factura->moneda }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            Total Factura: {{ number_format($factura->importe, 2, ',', '.') }} {{ $factura->moneda }}
        </div>

        <div class="footer">
            SiempreColgando - Registro Mercantil de Huelva, Tomo 123, Libro 45, Sección 8, Hoja H-6789.
        </div>
    </div>
</body>
</html>