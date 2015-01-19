<?php
/**
 * Plugin Name: Posse integration
 * Plugin URI:
 * Description: A brief description of the plugin.
 * Version: 1.0.0
 * Author: Piotr Grochowski <piogrek@gmail.com>
 * Author URI:
 * Text Domain:
 * Domain Path:
 * Network:
 * License:
 */

register_activation_hook( __FILE__, array( 'Posse', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Posse', 'plugin_deactivation' ) );

define( 'POSSE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( POSSE__PLUGIN_DIR . 'class.posse.php' );
//require_once( POSSE__PLUGIN_DIR . 'class.posse-widget.php' );

add_action( 'init', array( 'Posse', 'init' ) );
