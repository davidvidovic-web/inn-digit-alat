<?php

namespace InnDigit;


use InnDigit\Components\Acf\Fields;
use InnDigit\Components\Admin\Admin;
use InnDigit\Components\Quiz\QuizForm;
use InnDigit\Components\Quiz\ProcessData;
use InnDigit\Components\Pdf\ResultsPdf;
use InnDigit\Components\Email\Email;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class Plugin
{

    //don't modify this otherwise the quiz data won't be sent
    public function __construct()
    {
        add_action('wp_ajax_get_quiz_data', [&$this, 'get_quiz_data']);
        add_action('wp_ajax_nopriv_get_quiz_data', [&$this, 'get_quiz_data']);
        add_action('wp_ajax_get_quiz_data_db', [&$this, 'get_quiz_data_db']);
        add_action('wp_ajax_nopriv_get_quiz_data_db', [&$this, 'get_quiz_data_db']);
        add_action('wp_ajax_remove_quiz_item', [&$this, 'remove_quiz_item']);
        add_action('wp_ajax_nopriv_remove_quiz_item', [&$this, 'remove_quiz_item']);
        add_action('wp_ajax_create_excel', [&$this, 'create_excel']);
        add_action('wp_ajax_nopriv_create_excel', [&$this, 'create_excel']);
    }

    //run the plugin
    public function run()
    {
        add_action('wp_enqueue_scripts', [&$this, 'enqueue_assets']);
        add_action('loop_start', [&$this, 'construct_quiz_form']);
        add_shortcode('inn_digit_shortcode', [&$this, 'inn_digit_shortcode_fn']);
        register_activation_hook(PLUGIN_DIR . '/InnDigit.php', [&$this, 'create_plugin_table']);
        $fields = new Fields();
        $fields->register();
        if (is_admin()) {
            $admin = new Admin();
        }
    }

    public function create_plugin_table()
    {
        global $wpdb;
        $db_table_name = $wpdb->prefix . 'inndigit';  // table name

        //Check to see if the table exists already, if not, then create it
        if ($wpdb->get_var("show tables like '$db_table_name'") != $db_table_name) {
            $sql = "CREATE TABLE $db_table_name (
                        id bigint(20) NOT NULL auto_increment,
                        finansije_q text NOT NULL,
                        finansije_a text NOT NULL,
                        marketing_q text NOT NULL,
                        marketing_a text NOT NULL,
                        ljudski_resursi_q text NOT NULL,
                        ljudski_resursi_a text NOT NULL,
                        proces_q text NOT NULL,
                        proces_a text NOT NULL,
                        strategija_q text NOT NULL,
                        strategija_a text NOT NULL,
                        ocjena text NOT NULL,
                        naziv_privrednog_drustva varchar(50) NOT NULL,
                        email varchar(50) NOT NULL,
                        datum datetime NOT NULL,
                        UNIQUE KEY id (id)
                );";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }



    public function enqueue_assets()
    {
        wp_enqueue_style(
            'inndigit-style', // Handle
            PLUGIN_URL . 'assets/css/style.css', // File path
            array(), // Dependencies
            '1.0', // Version number
            'all' // Media
        );

        wp_register_script(
            'inndigit-script', // Handle
            PLUGIN_URL . 'assets/js/main.js', // File path
            array('jquery'), // Dependencies
            '1.0', // Version number
            true // In footer
        );
        wp_localize_script(
            'inndigit-script',
            'inndigit_ajax_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
            )
        );
        wp_enqueue_script('inndigit-script');
    }

    public function construct_quiz_form()
    {
        $field_group_key = 'group_65da583e60df8';
        $fields = acf_get_fields($field_group_key);
        $quiz = new QuizForm();
        $render = $quiz->data($fields);
        return $render;
    }

    public function inn_digit_shortcode_fn($atts)
    {
        $content = $this->construct_quiz_form();
        return $content;
    }

    public function get_quiz_data()
    {
        // Check if the data is set in the request
        if (!isset($_POST['data'])) {
            wp_send_json_error('Data not provided');
            return;
        }

        // Get the data from the request
        $data = $_POST['data'];

        $processData = new ProcessData($data);
        $data = $processData->sort($data);

        $pdf = new ResultsPdf();
        $email_data = $pdf->create_pdf($data);
        $this->email_pdf($email_data);
        wp_send_json_success($data);
    }

    public function email_pdf($email_data)
    {
        $mail = new Email;
        $mail->generate_email($email_data);
    }


    public function get_quiz_data_db()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'inndigit';
        $sql = "SELECT `ocjena`, `naziv_privrednog_drustva`, `email`, `datum` FROM $table";
        $results = $wpdb->get_results($sql);



        foreach ($results as $result) {
            $result->ocjena = str_replace('"', '', $result->ocjena);
        }

        wp_send_json_success($results);

        return $results;
    }

    public function remove_quiz_item()
    {
        global $wpdb;
        $id = $_POST['data'];
        $table = $wpdb->prefix . 'inndigit';
        wp_send_json_success($id);
        return $wpdb->delete(
            $table,
            ['id' => $id],
            ['%d'],
        );
    }

    public function create_excel()
    {
        global $wpdb;
        $id = $_POST['data'];
        $table = $wpdb->prefix . 'inndigit';
        $sql = "SELECT * FROM $table WHERE `id` = $id ORDER BY datum DESC";
        $results = $wpdb->get_results($sql);

        //json_decode first
        foreach ($results as $result) {
            $result->strategija_q = json_decode($result->strategija_q);
            $result->strategija_a = json_decode($result->strategija_a);
            $result->proces_q = json_decode($result->proces_q);
            $result->proces_a = json_decode($result->proces_a);
            $result->ljudski_resursi_q = json_decode($result->ljudski_resursi_q);
            $result->ljudski_resursi_a = json_decode($result->ljudski_resursi_a);
            $result->marketing_q = json_decode($result->marketing_q);
            $result->marketing_a = json_decode($result->marketing_a);
            $result->finansije_q = json_decode($result->finansije_q);
            $result->finansije_a = json_decode($result->finansije_a);
        }
        $results = $results[0];
        $filename = $results->naziv_privrednog_drustva . '-' . $results->datum;
        // if (!file_exists(PLUGIN_DIR . 'xlsx/' . $filename . '.xlsx')) {
        //     touch(PLUGIN_DIR . 'xlsx/' . $filename . '.xlsx');
        // }


        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(300, 'pt');
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $activeWorksheet->getStyle('A1:V1')->applyFromArray($styleArray);


        $activeWorksheet->setCellValue('A1', 'Naziv privrednog drustva');
        $activeWorksheet->setCellValue('A2', $results->naziv_privrednog_drustva);
        $activeWorksheet->setCellValue('B1', 'Email');
        $activeWorksheet->setCellValue('B2', $results->email);
        $activeWorksheet->setCellValue('C1', 'Nivo digitalizacije');
        $activeWorksheet->setCellValue('C2', $results->ocjena);
        $activeWorksheet->setCellValue('D1', 'Datum');
        $activeWorksheet->setCellValue('D2', $results->datum);
        $activeWorksheet->setCellValue('E1', $results->strategija_q[0]);
        foreach ($results->strategija_a[0] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('E' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('F1', $results->strategija_q[1]);
        foreach ($results->strategija_a[1] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('F' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('G1', $results->strategija_q[2]);
        foreach ($results->strategija_a[2] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('G' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('H1', $results->strategija_q[3]);
        foreach ($results->strategija_a[3] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('H' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('I1', $results->strategija_q[4]);
        foreach ($results->strategija_a[4] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('I' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('J1', $results->strategija_q[5]);
        foreach ($results->strategija_a[5] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('J' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('K1', $results->proces_q[0]);
        foreach ($results->proces_a[0] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('K' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('L1', $results->proces_q[1]);
        foreach ($results->proces_a[1] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('L' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('M1', $results->proces_q[2]);
        foreach ($results->proces_a[2] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('M' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('N1', $results->proces_q[3]);
        foreach ($results->proces_a[3] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('N' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('O1', $results->proces_q[4]);
        foreach ($results->proces_a[4] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('O' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('P1', $results->proces_q[5]);
        foreach ($results->proces_a[5] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('P' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('Q1', $results->proces_q[6]);
        foreach ($results->proces_a[6] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('Q' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('R1', $results->ljudski_resursi_q[0]);
        foreach ($results->ljudski_resursi_a[0] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('R' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('S1', $results->marketing_q[0]);
        foreach ($results->marketing_a[0] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('S' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('T1', $results->marketing_q[1]);
        foreach ($results->marketing_a[1] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('T' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('U1', $results->finansije_q[0]);
        foreach ($results->finansije_a[0] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('U' . $cellKey, $res);
        }
        $activeWorksheet->setCellValue('V1', $results->finansije_q[1]);
        foreach ($results->finansije_a[1] as $key => $res) {
            $cellKey = $key + 2;
            $activeWorksheet->setCellValue('V' . $cellKey, $res);
        }





        /* Here there will be some code where you create $spreadsheet */

        // redirect output to client browser
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="InnDigit-ALAT-' . $filename . '.xlsx' . '"');
        header('Cache-Control: max-age=0');

        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer = new Xlsx($spreadsheet);
        // $writer->save('php://output');
        $writer->save(PLUGIN_DIR . 'xlsx/InnDigit-ALAT-' . $filename . '.xlsx');
        wp_send_json_success($results);
    }
}
