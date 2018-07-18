<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/codiceovvio
 * @since             1.0.0
 * @package           Soundmap
 *
 * @wordpress-plugin
 * Plugin Name:       SoundMap
 * Plugin URI:        https://github.com/codiceovvio/soundmap/
 * Description:       Custom post type with geolocation and js maps integration
 * Version:           1.0.0
 * Author:            Codice Ovvio
 * Author URI:        https://github.com/codiceovvio/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       soundmap
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
define( 'SOUNDMAP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-soundmap-activator.php
 */
function activate_soundmap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-soundmap-activator.php';
	Soundmap_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-soundmap-deactivator.php
 */
function deactivate_soundmap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-soundmap-deactivator.php';
	Soundmap_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_soundmap' );
register_deactivation_hook( __FILE__, 'deactivate_soundmap' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-soundmap.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_soundmap() {

	$plugin = new Soundmap();
	$plugin->run();

}
run_soundmap();
