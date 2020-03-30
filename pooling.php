<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://indie.systems/pooling-plugin
 * @since             1.0.0
 * @package           PLG
 *
 * @wordpress-plugin
 * Plugin Name:       Pooling
 * Plugin URI:        https://indie.systems/pooling-plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Chris Dimas
 * Author URI:        https://indie.systems
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pooling
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// email or sms
define('POOLING_VERIFICATION_METHOD','email');
define('POOLING_RADIUS',1000);
define('POOLING_HASHTAG','#covid19help');
define('POOLING_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) . 'includes/emails');
define('POOLING_ACCEPT_OFFER_WINDOW', 3600);
define('POOLING_SEND_OFFER_WINDOW', 86400);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pooling-activator.php
 */
function activate_pooling() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pooling-activator.php';
	PLG_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pooling-deactivator.php
 */
function deactivate_pooling() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pooling-deactivator.php';
	PLG_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pooling' );
register_deactivation_hook( __FILE__, 'deactivate_pooling' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pooling.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pooling() {

	$plugin = new PLG();
	$plugin->run();

}
run_pooling();
