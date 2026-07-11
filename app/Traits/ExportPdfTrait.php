<?php

namespace App\Traits;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

trait ExportPdfTrait
{
    public function exportPdf($data, string $view, string $fileName = 'document.pdf')
    {
        try {
            $html = View::make($view, $data)->render();
            
            $mpdf = new Mpdf([
                'default_font' => 'dejavusans',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            $mpdf->WriteHTML($html);

            return response($mpdf->Output($fileName, 'D'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=\"$fileName\"");
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'PDF generation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
