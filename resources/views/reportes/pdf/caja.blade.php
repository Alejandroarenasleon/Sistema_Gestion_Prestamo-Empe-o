<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Caja - Trueque Cash</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #d4a017; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Trueque Cash — Cierre de Caja</h1>
    <p>Periodo: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Esperado (Bs)</th>
                <th>Físico (Bs)</th>
                <th>Diferencia</th>
                <th>Confirmado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cierres as $c)
            <tr>
                <td>{{ $c->fecha->format('d/m/Y') }}</td>
                <td>{{ number_format($c->efectivo_esperado, 2, ',', '.') }}</td>
                <td>{{ $c->efectivo_fisico !== null ? number_format($c->efectivo_fisico, 2, ',', '.') : '—' }}</td>
                <td>{{ $c->diferencia !== null ? number_format($c->diferencia, 2, ',', '.') : '—' }}</td>
                <td>{{ $c->confirmado ? 'Sí' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
