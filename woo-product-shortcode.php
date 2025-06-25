<?php
/**
 * Plugin Name: Woo Shortcode Generator
 * Description: Generate a shortcode to display selected products
 * Version:     1.0
 * Author:      Nabil Ahmad
 * Author URI: https://nabilahmad.com/
 * License: GPL
 */

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/* All constants should be defined in this file. */
if ( ! defined( 'woo_PREFIX' ) ) {
	define( 'woo_PREFIX', 'woo' );
}
if ( ! defined( 'woo_PLUGIN_DIR' ) ) {
	define( 'woo_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'woo_PLUGIN_BASENAME' ) ) {
	define( 'woo_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'woo_PLUGIN_URL' ) ) {
	define( 'woo_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* Auto-load all the necessary classes. */
if( ! function_exists( 'woo_class_auto_loader' ) ) {
	
	function woo_class_auto_loader( $class ) {
		
		$includes = woo_PLUGIN_DIR . 'includes/' . $class . '.php';
		
		if( is_file( $includes ) && ! class_exists( $class ) ) {
			include_once( $includes );
			return;
		}
		
	}
}
spl_autoload_register('woo_class_auto_loader');

/* Initialize all modules now. */

new woo_Comman();
new woo_Admin();
new woo_Frontend();

include(woo_PLUGIN_DIR . 'includes/woo_Widget.php');

add_action( 'wp_enqueue_scripts',  'woo_insta_scritps'  );
function woo_insta_scritps () {
		wp_enqueue_style('gmwqp-style', woo_PLUGIN_URL . '/css/style.css', array(), '1.0.0', 'all');
	}
?>