<?php

namespace InnDigit;


use InnDigit\Components\Acf\Fields;
use InnDigit\Components\Admin\Admin;
use InnDigit\Components\Quiz\QuizForm;
use InnDigit\Components\Quiz\ProcessData;
use InnDigit\Components\Pdf\ResultsPdf;
use InnDigit\Components\Email\Email;



class Plugin
{

    //don't modify this otherwise the quiz data won't be sent
    public function __construct()
    {
        add_action('wp_ajax_get_quiz_data', [&$this, 'get_quiz_data']);
        add_action('wp_ajax_nopriv_get_quiz_data', [&$this, 'get_quiz_data']);
        add_action('wp_ajax_get_quiz_data_db', [&$this, 'get_quiz_data_db']);
        add_action('wp_ajax_nopriv_get_quiz_data_db', [&$this, 'get_quiz_data_db']);
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
        // $charset_collate = $wpdb->get_charset_collate();X

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
        $write_to_db = $processData->write_to_db($data);
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

        $sql = "SELECT * FROM $table";
        $results = $wpdb->get_results($sql);



        foreach ($results as $result) {
            $result->finansije_q = json_decode($result->finansije_q);
            $result->finansije_a = json_decode($result->finansije_a);
            $result->ljudski_resursi_q = json_decode($result->ljudski_resursi_q);
            $result->ljudski_resursi_a = json_decode($result->ljudski_resursi_a);
            $result->marketing_q = json_decode($result->marketing_q);
            $result->marketing_a = json_decode($result->marketing_a);
            $result->proces_q = json_decode($result->proces_q);
            $result->proces_a = json_decode($result->proces_a);
            $result->strategija_q = json_decode($result->strategija_q);
            $result->strategija_a = json_decode($result->strategija_a);
        }

        wp_send_json_success($results);

        return $results;
    }
}
