<?php

namespace InnDigit\Components\Admin;

class Admin
{

    public function __construct()
    {
        add_action('admin_menu', [&$this, 'register_plugin_admin_area']);
        add_action('wp_enqueue_scripts', [&$this, 'enqueue_app_scripts']);
    }
    public function register_plugin_admin_area()
    {
        add_menu_page(
            __('InnDigit', 'inndigit'),
            __('InnDigit', 'inndigit'),
            'manage_options',
            '/inn-digit-alat/inc/Components/Admin/Index.php',
            $this->inndigit_page_contents(),
            'dashicons-media-spreadsheet',
            3
        );
    }

    public function enqueue_app_scripts()
    {
        
    }



    public function inndigit_page_contents()
    {
        //we need to have a callback
    }
}
