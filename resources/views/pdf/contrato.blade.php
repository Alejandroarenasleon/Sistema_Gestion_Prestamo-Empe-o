<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Contrato de Empeño #{{ $prestamo->id_prestamo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 16px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
        .section { margin-top: 16px; }
    </style>
</head>
<body>
    <h1>TRUEQUE CASH — Contrato de Empeño</h1>

    <div class="section">
        <strong>Cliente:</strong> {{ $cliente->nombre_completo }}<br>
        <strong>CI:</strong> {{ $cliente->ci }}<br>
        <strong>Celular:</strong> {{ $cliente->celular }}<br>
        <strong>Dirección:</strong> {{ $cliente->direccion ?? 'N/D' }}
    </div>

    <div class="section">
        <strong>Préstamo #{{ $prestamo->id_prestamo }}</strong><br>
        <strong>Capital:</strong> Bs. {{ number_format($prestamo->monto_capital, 2, '.', ',') }}<br>
        <strong>Tasa mensual:</strong> {{ number_format($prestamo->tasa_interes_mensual, 2) }}%<br>
        <strong>Emisión:</strong> {{ $prestamo->fecha_emision->format('d/m/Y') }}<br>
        <strong>Vencimiento:</strong> {{ $prestamo->fecha_vencimiento->format('d/m/Y') }}<br>
        <strong>Periodo de gracia:</strong> {{ $diasGracia }} días
    </div>

    <div class="section">
        <strong>Prendas en garantía</strong>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Avalúo (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prendas as $prenda)
                    <tr>
                        <td>{{ $prenda->categoria }}</td>
                        <td>{{ $prenda->descripcion }}</td>
                        <td>{{ number_format($prenda->avaluo, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <p>El cliente declara haber recibido el monto indicado y acepta las condiciones de interés, vencimiento y política de gracia establecidas por Trueque Cash.</p>
    </div>
</body>
</html>
