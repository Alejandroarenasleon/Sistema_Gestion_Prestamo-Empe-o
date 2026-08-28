<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Intereses - Trueque Cash</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { color: #d4a017; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Trueque Cash — Intereses Cobrados</h1>
    <p>{{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr><th>Fecha</th><th>Cliente</th><th>Tipo</th><th>Monto (Bs)</th></tr>
        </thead>
        <tbody>
            @foreach($pagos as $p)
            <tr>
                <td>{{ $p->fecha->format('d/m/Y H:i') }}</td>
                <td>{{ $p->prestamo->cliente->nombre_completo ?? '—' }}</td>
                <td>{{ $p->tipo }}</td>
                <td>{{ number_format($p->monto, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total: Bs {{ number_format($total, 2, ',', '.') }}</strong></p>
</body>
</html>
