<?php
/**
 * Plugin Name: AT Rest Filter
 * Description: A plugin to filter Posts
 * Version: 1.0.0
 * Author: Na-Gora
 */


define('AT_REST_FILTER_DIR', __DIR__);
define('AT_REST_FILTER_URL', plugin_dir_url(__FILE__));

// Include autoloader
require_once AT_REST_FILTER_DIR . '/vendor/autoload.php';



new \Supernova\AtRestFilter\Shortcodes\FilterForm();