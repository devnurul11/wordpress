<?php
/*
Plugin Name: Advanced Downloads Manager
Plugin URI: amaderit.com/advanced-downloads-manager
Description: Manage advanced downloads with custom post type.
Version: 1.0.0
Author: Nurul Islam
Author URI: devnurul.me
Text Domain: adm
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load the custom post type file
require_once plugin_dir_path( __FILE__ ) . 'adm-post-type.php';

