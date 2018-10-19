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
 * @since             0.1.0
 * @package           Sound Map
 *
 * @wordpress-plugin
 * Plugin Name:       Sound Map
 * Plugin URI:        https://github.com/codiceovvio/soundmap/
 * Description:       Custom post type with geolocation and js maps integration
 * Version:           0.5.0
 * Author:            Codice Ovvio
 * Author URI:        https://github.com/codiceovvio/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       soundmap
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 *
 * @var string the plugin version, e.g. '0.1.0'
 */
define( 'SOUNDMAP_VERSION', '0.5.0' );

/**
 * The plugin basename.
 *
 * @var string the plugin basename, e.g. soundmap/soundmap.php
 */
define( 'SOUNDMAP_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The plugin dir path.
 *
 * @var string $var The absolute path to the plugin folder.
 */
define( 'SOUNDMAP_PATH', plugin_dir_path( __FILE__ ) );
/**
 * The plugin dir url.
 *
 * @var string $var The URL to the plugin folder, with a trailing slash.
 */
define( 'SOUNDMAP_URL', plugins_url( '/', __FILE__ ) );

/**
 * Templates debug helper
 *
 * Setting this constant to true will load templates only from
 * the plugin folder, skipping those in themes and child-themes.
 *
 * @var bool
 */
define( 'SOUNDMAP_TEMPLATE_DEBUG', false );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-soundmap-activator.php
 */
function activate_soundmap() {
	require_once SOUNDMAP_PATH . 'includes/class-soundmap-activator.php';
	Soundmap_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-soundmap-deactivator.php
 */
function deactivate_soundmap() {
	require_once SOUNDMAP_PATH . 'includes/class-soundmap-deactivator.php';
	Soundmap_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_soundmap' );
register_deactivation_hook( __FILE__, 'deactivate_soundmap' );

/**
 * The core plugin class that is used to define internationalization,
 * common hooks, admin-specific hooks, and public-facing site hooks.
 */
require SOUNDMAP_PATH . 'includes/class-soundmap.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_soundmap() {

	$plugin = new Soundmap();
	$plugin->run();

	// Sound Map fully loaded.
	do_action( 'soundmap_loaded' );

}
run_soundmap();
