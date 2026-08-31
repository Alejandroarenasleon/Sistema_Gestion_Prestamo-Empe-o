<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recibo #{{ $pago->id_pago }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 16px; text-align: center; }
        .row { margin: 6px 0; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <h1>TRUEQUE CASH — Recibo de Pago</h1>

    <div class="row"><span class="label">Recibo N°:</span> {{ $pago->id_pago }}</div>
    <div class="row"><span class="label">Fecha:</span> {{ $pago->fecha->format('d/m/Y H:i') }}</div>
    <div class="row"><span class="label">Cliente:</span> {{ $cliente->nombre_completo }}</div>
    <div class="row"><span class="label">CI:</span> {{ $cliente->ci }}</div>
    <div class="row"><span class="label">Préstamo N°:</span> {{ $prestamo->id_prestamo }}</div>
    <div class="row"><span class="label">Concepto:</span> {{ $pago->tipo }}</div>
    <div class="row"><span class="label">Monto pagado:</span> Bs. {{ number_format($pago->monto, 2, '.', ',') }}</div>
    <div class="row"><span class="label">Saldo capital:</span> Bs. {{ number_format($pago->saldo_capital_resultante, 2, '.', ',') }}</div>
    <div class="row"><span class="label">Saldo total pendiente:</span> Bs. {{ number_format($saldoPendiente, 2, '.', ',') }}</div>
    <div class="row"><span class="label">Operador:</span> {{ $pago->usuario->nombre_completo }}</div>
</body>
</html>
