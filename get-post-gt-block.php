<?php
/*
*  Plugin Name: Get Post Block
*  Description: This plugin creates gutenberg block which get post data based on selected post
 * Version: 1.0.0
 * Author: Gaurang Sondagar
 * Author URI: https://gaurangsondagar99.wordpress.com/
*/

define('GPGB_PLUGIN_URL_PATH', plugins_url('get-post-gt-block'));
define('GPGB_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

//Include plugin required file
include 'includes/get_block_register.php';