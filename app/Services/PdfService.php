<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\Pago;
use App\Models\Parametro;
use App\Models\Prestamo;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generarContrato(Prestamo $prestamo): Contrato
    {
        $prestamo->load(['cliente', 'prendas.fotos', 'usuarioRegistro']);

        $pdf = Pdf::loadView('pdf.contrato', [
            'prestamo' => $prestamo,
            'cliente' => $prestamo->cliente,
            'prendas' => $prestamo->prendas,
            'diasGracia' => Parametro::getValor('DIAS_GRACIA', 15),
        ]);

        $nombreArchivo = 'contratos/contrato_'.$prestamo->id_prestamo.'_'.now()->format('YmdHis').'.pdf';
        Storage::disk('public')->put($nombreArchivo, $pdf->output());

        return Contrato::updateOrCreate(
            ['id_prestamo' => $prestamo->id_prestamo],
            [
                'pdf_url' => $nombreArchivo,
                'fecha_generacion' => now(),
            ]
        );
    }

    public function generarRecibo(Pago $pago, string $canal = 'PDF'): Recibo
    {
        $pago->load(['prestamo.cliente', 'usuario']);

        $pdf = Pdf::loadView('pdf.recibo', [
            'pago' => $pago,
            'prestamo' => $pago->prestamo,
            'cliente' => $pago->prestamo->cliente,
            'saldoPendiente' => $pago->prestamo->saldoTotal(),
        ]);

        $nombreArchivo = 'recibos/recibo_'.$pago->id_pago.'_'.now()->format('YmdHis').'.pdf';
        Storage::disk('public')->put($nombreArchivo, $pdf->output());

        return Recibo::updateOrCreate(
            ['id_pago' => $pago->id_pago],
            [
                'canal' => $canal,
                'pdf_url' => $nombreArchivo,
                'fecha_generacion' => now(),
            ]
        );
    }
}
