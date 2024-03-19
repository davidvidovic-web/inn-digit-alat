<?php

namespace InnDigit\Components\Pdf;

use Mpdf\Mpdf;


class ResultsPdf
{
    public function create_pdf($data)
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<h1>' . '$data' . '</h1>');
        $mpdf->Output(PLUGIN_DIR . 'pdfs/results.pdf', 'F');
        return;
    }
}
