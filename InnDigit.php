<?php
/*
Plugin Name: InnDigitAlat
Description: InnDigitAlat plugin za quiz
Version: 1.01
Author: Lanaco
Author URI: https://lanaco.com
License: GPL2
Text Domain: InnDigit
*/

require_once __DIR__ . '/vendor/autoload.php'; // Autoload MPDF
include_once "inc/Autoload.php";    // Autoload classes
use InnDigit\Autoload;
use InnDigit\Plugin;

// Define plugin directory
define("PLUGIN_DIR", plugin_dir_path(__FILE__));
define("PLUGIN_URL", plugin_dir_url(__FILE__));

// Autoload classes
spl_autoload_register(function ($className) {
    // Make sure the class included is in this plugins namespace
    if (substr($className, 0, strlen("InnDigit\\")) === "InnDigit\\") {
        // Replace \ with / which works as directory separator for further namespaces
        $classNameShort = str_replace("\\", "/", substr($className, strlen("InnDigit\\")));
        include_once PLUGIN_DIR . "inc/$classNameShort.php";
    }
});

$autoload = new Autoload(); // Autoload classes

// Run the plugin
$plugin = new Plugin();
$plugin->run();
