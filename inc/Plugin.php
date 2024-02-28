<?php

namespace InnDigit;

use InnDigit\Components\Quiz\QuizForm;
use InnDigit\Components\Quiz\ProcessData;
use InnDigit\Components\Acf\RegisterFields;

class Plugin
{

    //don't modify this otherwise the quiz data won't be sent
    public function __construct()
    {
        add_action('wp_ajax_get_quiz_data', array($this, 'get_quiz_data'));
        add_action('wp_ajax_nopriv_get_quiz_data', array($this, 'get_quiz_data'));
    }

    //run the plugin
    public function run()
    {
        add_action('wp_enqueue_scripts', [&$this, 'enqueue_assets']);
        add_action('loop_start', [&$this, 'construct_quiz_form']);
        add_shortcode('inn_digit_shortcode', [&$this, 'inn_digit_shortcode_fn']);
        RegisterFields::register();
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
        


        wp_send_json_success($data);
    }
}
