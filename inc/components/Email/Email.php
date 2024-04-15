<?php

namespace InnDigit\Components\Email;

class Email
{
    public function __construct()
    {
        add_filter('wp_mail_content_type', [&$this, 'wp_set_content_type']);
    }
    public function generate_email($data)
    {
        $company = $data['company'];
        $subject = 'InnDigit Alat - Samoprocjena stepena digitalizacije i inovativnosti';
        $to = $data['email'];
        $attachment = PLUGIN_DIR . 'pdfs/' . $data['pdf'];
        $body = '';
        $body .= '<p> Poštovani/a ' . $company  . '</p>';
        $body .= '<br>';
        $body .= '<p>Hvala vam što ste koristili InnDigit alat. Ovo je odličan korak ka stvaranju boljeg razumijevanja digitalnih potreba i mogućnosti poslovanja Vaše organizacije.</p>';
        $body .= '<br>';
        $body .= 'InnDigit alat je analizirao podatke koje ste pružili i pripremio kratku procjenu digitalno-inovativnih kapaciteta i preporuke u svrhu unaprijeđenja digitalne strategije preduzeća.';
        $body .= '<br>';
        $body .= 'Ukoliko imate dodatna pitanja ili zahtjeve, slobodno nas kontaktirajte. Povratna informacija nam je korisna i doprinosi našem stalnom radu na podršci digitalne transformacije u poslovanju.';
        $body .= '<br />';
        $body .= '<br>';
        $body .= '<p>Srdačan pozdrav,</p>';
        $body .= '<div>Centar za digitalnu transformaciju</div>';
        $body .= '<div>Privredne komore Republike Srpske</div>';
        $body .= '<div>Adresa: Branka Ćopića 6</div>';
        $body .= '<div style="margin-left: 40pt">78000 Banja Luka </div>';
        $body .= '<div style="margin-left: 40pt">Republike Srpske, BiH </div>';
        $body .= '<div> Telefon: <a href="tel:+387 51 493 138">+387 51 493 138</a> </div>';
        $body .= '<div> e-mail: <div style="margin-left: 5pt; display: inline-block;"><a href="mailto:cdt.pkrs@komorars.ba">cdt.pkrs@komorars.ba</a></div></div>';
        $body .= '<div> web: <div style="margin-left: 15pt; display: inline-block;"><a href="www.komorars.ba">www.komorars.ba</a><a href="www.business-rs.ba">, www.business-rs.ba</a><a href="https://infobiz.komorars.ba/">, infobiz.komorars.ba</a></div></div>';
        $headers[] = 'From: InnDigit ALAT <cdt.pkrs@komorars.ba>';
        $sent = wp_mail($to, $subject, $body, $headers, $attachment);
        if (!$sent) {
            error_log('[Email] ups ' . $to);
            die('Ups...');
        }
        error_log('[Email] sent ' . $to);
    }


    public function wp_set_content_type()
    {
        return "text/html";
    }
}
