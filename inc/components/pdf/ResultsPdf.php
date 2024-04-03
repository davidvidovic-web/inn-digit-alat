<?php

namespace InnDigit\Components\Pdf;


use InnDigit\Components\Pdf\Constants;
use Mpdf\Mpdf;


class ResultsPdf
{
    public function create_pdf($data)
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        $headerHTML = '<div style="display: block; margin: 20px">';
        $headerHTML .= '<img width="48mm" height="16mm" src="' . PLUGIN_URL . 'assets/pdf-images/eu.jpg">';
        $headerHTML .= '<img width="33mm" height="12mm" src="' . PLUGIN_URL . 'assets/pdf-images/giz.jpg">';
        $headerHTML .= '<img width="54mm" height="12mm" style="margin-left: 25mm" src="' . PLUGIN_URL . 'assets/pdf-images/privredna-komora.png">';
        $headerHTML .= '</div>';
        $mpdf->SetHTMLHeader(
            $headerHTML,
            'O'
        );
        $mpdf->SetHTMLHeader($headerHTML, 'E');
        $footerHTML = '<div style="display: block; margin: 20px; width: 170mm;text-align: center; border-top: 1px solid;">';
        $footerHTML .= '<img width="73mm" height="12mm" src="' . PLUGIN_URL . 'assets/pdf-images/masinski-fakultet.jpg">';
        $footerHTML .= '<img width="35mm" left="5mm" style="margin-left: 35px" height="15mm" src="' . PLUGIN_URL . 'assets/pdf-images/cdt2.jpg">';
        $footerHTML .= '</div>';
        $mpdf->SetHTMLFooter($footerHTML, 'O');
        $mpdf->SetHTMLFooter($footerHTML, 'E');
        $mpdf->AddPage();
        $html = '<div style="position: fixed; top: 40mm;">';
        $html .= '<h1 style="position: relative; display: block;font-family: calibri; font-size: 23px; font-weight: bold;">InnDigit Alat</h1>';
        $html .= '<h2 style="font-family: calibri; font-size: 20px; font-weight: bold;">Procjena stepena digitalizacije i inovativnosti</h2>';
        $html .= '</div>';
        $html .= '<div style="position: fixed; top: 120mm; width: 170mm;">';
        $html .= '<h1 style="font-family: calibri;font-size: 23px; font-weight: bold; text-align: center;">' . $data['general_result'] . ' nivo <br> digitalizacije i inovativnosti</h1>';
        $html .= '</div>';
        $html .= '<div style="position: absolute; top: 50mm;>';
        $html .= $mpdf->Image(PLUGIN_URL . 'assets/pdf-images/cdt.png', 20, 220, 90, 29, 'png', '', true, true);
        $html .= '</div>';
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        switch ($data['general_result']) {
            case 'Nizak':
                $file = PLUGIN_DIR . '/inc/Components/Pdf/templates/Nizak.pdf';
                $input = str_replace(chr(0), '', $file);
                $fileContent = file_get_contents($input);
                $level = $mpdf->setSourceFile($input);
                break;
            case 'Srednji':
                $file = PLUGIN_DIR . '/inc/Components/Pdf/templates/Srednji.pdf';
                $input = str_replace(chr(0), '', $file);
                $fileContent = file_get_contents($input);
                $level = $mpdf->setSourceFile($input);
                break;
            case 'Napredni':
                $file = PLUGIN_DIR . 'inc/Components/Pdf/templates/Napredni.pdf';
                $input = str_replace(chr(0), '', $file);
                $fileContent = file_get_contents($input);
                $level = $mpdf->setSourceFile($input);
                break;
        }

        $levelTemplate = $mpdf->importPage($level);
        $mpdf->useTemplate($levelTemplate);
        $mpdf->WriteHTML('');
        $mpdf->AddPage();

        $comments = Constants::$comments;
        $html = '<div style="position: fixed; top: 15mm;">';
        foreach ($data as $area => $data) {
            if ($area !== 'kontakt_oblast_k' || $area !== 'general_result' || $area === 'spec') {
                $area_comment = strtoupper(substr($area, -1));
                if (!empty($data['grade'])) {
                    $area_comment = $area_comment . $data['grade'];
                    $html .= '<p style="font-size: 11px; clear: both; padding-bottom: 7px;">' . $comments[$area_comment] . '</p>';
                }
            }

            if ($area === 'spec') {
                foreach ($data as $spec) {
                    $html .= '<p style="font-size: 11px; padding-bottom: 7px;">' . $spec . '</p>';
                }
            }

            if ($area === 'kontakt_oblast_k') {
                foreach ($data as $key => $dataItem) {
                    if ($key == 'Naziv privrednog društva:') {
                        $companyName = $dataItem[0];
                    } else if ($key == 'Kontakt e-mail adresa') {
                        $email = $dataItem[0];
                    }
                }
            }
        }


        $html .= '</div>';
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $contact_file = PLUGIN_DIR . '/inc/Components/Pdf/templates/Kontakt.pdf';
        $input = str_replace(chr(0), '', $contact_file);
        $contact = $mpdf->setSourceFile($input);
        $contactTemplate = $mpdf->importPage($contact);
        $mpdf->useTemplate($contactTemplate);
        $mpdf->WriteHTML('');
        $date = date('Y-m-d');
        $pdfName = $companyName . '-rezultati-' . $date . '.pdf';
        $mpdf->Output(PLUGIN_DIR . 'pdfs/' . $pdfName, 'F');

        $emailData = [
            'company' => $companyName,
            'email' => $email,
            'pdf' => $pdfName
        ];

        return $emailData;
    }


    public function normalizePath($path)
    {
        $parts = array(); // Array to build a new path from the good parts
        $path = str_replace('\\', '/', $path); // Replace backslashes with forwardslashes
        $path = preg_replace('/\/+/', '/', $path); // Combine multiple slashes into a single slash
        $segments = explode('/', $path); // Collect path segments
        $test = ''; // Initialize testing variable
        foreach ($segments as $segment) {
            if ($segment != '.') {
                $test = array_pop($parts);
                if (is_null($test))
                    $parts[] = $segment;
                else if ($segment == '..') {
                    if ($test == '..')
                        $parts[] = $test;

                    if ($test == '..' || $test == '')
                        $parts[] = $segment;
                } else {
                    $parts[] = $test;
                    $parts[] = $segment;
                }
            }
        }
        return implode('/', $parts);
    }
}
