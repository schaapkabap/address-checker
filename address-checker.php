<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://webroots.nl
 * @since             1.0.0
 * @package           adress-checker
 *
 * @wordpress-plugin
 * Plugin Name:       Address-Checker
 * Plugin URI:        http://webroots.nl
 * Description:       This is the description the plugin. Its checks the adresses
 * Version:           1.0.0
 * Author:            Webroots
 * Author URI:        http://example.com; http://webroots.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       address-checker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activateAddressChecker(){
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-address-checker-activator.php';
    AddressCheckerActivator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivateAddressChecker() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-address-checker-deactivator.php';
    AddressCheckerDeactivator::deactivate();
}
register_activation_hook( __FILE__, 'activateAddressChecker' );
register_deactivation_hook( __FILE__, 'deactivateAddressChecker' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-address-checker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_AdressChecker() {
    $plugin = new AddressChecker();
    $plugin->run();
}

if (function_exists('run_AdressChecker')){
    run_AdressChecker();
}


?>
