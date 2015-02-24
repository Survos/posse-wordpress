<?php
/**
 * Plugin Name: Posse integration
 * Plugin URI:
 * Description: Allows easy integration with Posse project
 * Version: 1.0.0
 * Author: Piotr Grochowski <piogrek@gmail.com>
 * Author URI:
 * Text Domain:
 * Domain Path:
 * Network:
 * License:
 */

register_activation_hook(__FILE__, ['Posse', 'plugin_activation']);
register_deactivation_hook(__FILE__, ['Posse', 'plugin_deactivation']);

define('POSSE__PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once(POSSE__PLUGIN_DIR.'class.posse.php');

//require_once( POSSE__PLUGIN_DIR . 'class.posse-widget.php' );

add_action('init', ['Posse', 'init']);


add_action('personal_options_update', 'update_extra_profile_fields');

function update_extra_profile_fields($user_id)
{
    if (current_user_can('edit_user', $user_id)) {
        var_dump($_POST['pass1']);
    }
}