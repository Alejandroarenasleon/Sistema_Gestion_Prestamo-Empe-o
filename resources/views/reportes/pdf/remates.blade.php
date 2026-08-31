<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Remates - Trueque Cash</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { color: #d4a017; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Trueque Cash — Remates</h1>
    <p>{{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr><th>Fecha</th><th>Prenda</th><th>Categoría</th><th>Cliente</th><th>Venta</th><th>Resultado</th></tr>
        </thead>
        <tbody>
            @foreach($remates as $r)
            <tr>
                <td>{{ $r->fecha_venta->format('d/m/Y') }}</td>
                <td>{{ $r->prenda->descripcion ?? '—' }}</td>
                <td>{{ $r->categoria ?? '—' }}</td>
                <td>{{ $r->prenda->prestamo->cliente->nombre_completo ?? '—' }}</td>
                <td>Bs {{ number_format($r->precio_venta, 2, ',', '.') }}</td>
                <td>Bs {{ number_format($r->resultado, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total ventas: Bs {{ number_format($totalVentas, 2, ',', '.') }} | Resultado neto: Bs {{ number_format($totalResultado, 2, ',', '.') }}</p>

    @if($perdidasPorCategoria->isNotEmpty())
    <h3>Pérdidas por Categoría</h3>
    <table>
        <thead>
            <tr><th>Categoría</th><th>Pérdida</th></tr>
        </thead>
        <tbody>
            @foreach($perdidasPorCategoria as $categoria => $perdida)
            <tr>
                <td>{{ $categoria ?? 'Sin categoría' }}</td>
                <td>Bs {{ number_format($perdida, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
