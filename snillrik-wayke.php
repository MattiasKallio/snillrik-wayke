<?php
/*
Plugin Name: Wayke for Wordpress
Plugin URI: http://www.snillrik.se/
Description: This is a plugin to implement the Wayke API into WordPress
Version: 1.3
Author: Mattias Kallio
Author URI: http://www.snillrik.se
License: GPL2
 */

DEFINE("SNWK_PLUGIN_URL", plugin_dir_url(__FILE__));
DEFINE("SNWK_DIR", plugin_dir_path(__FILE__));

require_once SNWK_DIR . 'settings.php';
require_once SNWK_DIR . 'classes/SNWK_API.php';
require_once SNWK_DIR . 'classes/SNWK_Shortcodes.php';
require_once SNWK_DIR . 'classes/SNWK_DesignElements.php';

/**
 * ToD0
 */
// Google fonts script to test

//add custom css?

function snillrik_faq_admin_addCSScripts($page){
    wp_enqueue_script 	( 'snillrik-waykeadmin-script', SNWK_PLUGIN_URL . 'js/snillrik-wayke-admin.js', array ('jquery' ));
	wp_localize_script  ('snillrik-waykescript', 'snillrik_js', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
 add_action('admin_enqueue_scripts','snillrik_faq_admin_addCSScripts');

function snillrik_faq_addCSScripts(){
    wp_enqueue_style    ('snillrik-waykeswiper', SNWK_PLUGIN_URL . 'css/swiper.min.css');
	wp_enqueue_style 	( 'snillrik-waykefront', SNWK_PLUGIN_URL . 'css/snillrik-wayke-front.css' );
    wp_enqueue_script 	( 'snillrik-waykescript', SNWK_PLUGIN_URL . 'js/snillrik-wayke.js', array ('jquery' ));
    wp_enqueue_script 	( 'snillrik-waykeslider-swiper', SNWK_PLUGIN_URL . 'js/swiper.min.js', array ('jquery' ));
    wp_register_script('snillrik-waykeslider-main-script', SNWK_PLUGIN_URL . 'js/snillrik-waykeswiper.js', array('jquery'),'1.2.3', true);
    
    wp_localize_script('snillrik-waykescript', 'snillrik_js', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}

add_action('wp_enqueue_scripts','snillrik_faq_addCSScripts');

add_filter('query_vars', 'krkw_query_vars', 10, 1);
function krkw_query_vars($vars)
{
    $vars[] = 'bilid';
    return $vars;
}