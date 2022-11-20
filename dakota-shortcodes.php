<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/arturgeek
 * @since             1.0.0
 * @package           Dakota_Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       Dakota Shortcodes
 * Plugin URI:        https://https://github.com/arturgeek
 * Description:       Plugin to enhance different elements within woocommerce, adding shortcodes to display  Cart Items Count,  Login / My Account via link
 * Version:           1.0.0
 * Author:            Andres Morales
 * Author URI:        https://https://github.com/arturgeek
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dakota-shortcodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DAKOTA_SHORTCODES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dakota-shortcodes-activator.php
 */
function activate_dakota_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dakota-shortcodes-activator.php';
	Dakota_Shortcodes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dakota-shortcodes-deactivator.php
 */
function deactivate_dakota_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dakota-shortcodes-deactivator.php';
	Dakota_Shortcodes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dakota_shortcodes' );
register_deactivation_hook( __FILE__, 'deactivate_dakota_shortcodes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dakota-shortcodes.php';

add_action( 'admin_menu', 'extra_post_info_menu' );  
function extra_post_info_menu(){    
	$page_title = 'Dakota Shortcodes';   
	$menu_title = 'Dakota Shortcodes';   
	$capability = 'manage_options';   
	$menu_slug  = 'dakota-shortcodes-info';   
	$function   = 'dakota_shortcodes_info_page';   
	$icon_url   = 'dashicons-media-code';   
	
	add_menu_page( 
		$page_title,
		$menu_title,
		$capability,
		$menu_slug,
		$function,
		$icon_url
	); 
}

function dakota_shortcodes_info_page(){
	require_once plugin_dir_path( __FILE__ )."dakota-shortcodes-info-page.php";
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dakota_shortcodes() {

	$plugin = new Dakota_Shortcodes();
	$plugin->run();

}
run_dakota_shortcodes();
