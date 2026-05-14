<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; }
        .box { border: 1px solid #e9ecef; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; }
        .details { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
        .footer { font-size: 12px; color: #6c757d; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <div class="header">
            <h2 style="color: #0d6efd; margin: 0;">Aviso de Nueva Cuota</h2>
        </div>
        
        <p>Estimado/a <strong>{{ $cuota->cliente->nombre }}</strong>.</p>
        <p>Le informamos que se ha registrado una nueva cuota en su cuenta:</p>

        <div class="details">
            <strong>Concepto:</strong> {{ $cuota->concepto }}<br>
            <strong>Importe:</strong> {{ number_format($cuota->importe, 2) }} €<br>
            <strong>Fecha Emisión:</strong> {{ $cuota->fecha_emision->format('d/m/Y') }}
        </div>

        <p>Este correo es meramente informativo. No es necesario realizar ninguna acción si tiene sus pagos domiciliados.</p>
        
        <p>Atentamente,<br>El equipo de {{ config('app.name') }}</p>

        <div class="footer">
            Este es un mensaje automático, por favor no responda a este correo.
        </div>
    </div>
</body>
</html>