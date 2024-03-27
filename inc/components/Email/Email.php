<?php

namespace InnDigit\Components\Email;

class Email
{
    public function generate_email($data)
    {
        $company = $data['company'];
        $subject = 'InnDigit Alat evaluacija za ' . $company;
        $to = $data['email'];
        $attachment = PLUGIN_DIR . 'pdfs/' . $data['pdf'];
        $body = 'Test email';
        $headers = '';
        $sent = wp_mail($to, $subject, $body, $headers, $attachment);
        if (!$sent) {
            error_log('[Email] ups ' . $to);
            die('Ups...');
        }
        error_log('[Email] sent ' . $to);
    }
}
