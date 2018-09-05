<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.1.0
 *
 * @package Soundmap/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Soundmap/admin
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Define a private array to hold Map initial settings.
	 *
	 * @since  0.1.1
	 * @access private
	 * @var    array $config Settings to define the initial map setup.
	 */
	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->load_options();

	}

	/**
	 * Load Sound Map options
	 *
	 * Load options from the database, and parse them with defaults,
	 * else define an initial set for map defaults.
	 *
	 * @since 0.1.1
	 */
	private function load_options() {

		$_config = [];

		// Load defaults.
		$defaults = [
			$this->plugin_name . '_settings_lat'  => '41.9097306',
			$this->plugin_name . '_settings_lng'  => '12.2558141',
			$this->plugin_name . '_settings_zoom' => '11',
		];

		// Get saved options from database.
		$_config = get_option( $this->plugin_name . '_map_settings' );
		// Parse options with defaults.
		$_config = wp_parse_args( $_config, $defaults );
		// Set parsed options to pass to js.
		$this->config = $_config;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 0.1.0
	 * @param string $hook Which admin page (query-string) hook.
	 */
	public function enqueue_styles( $hook ) {

		$current_screen = get_current_screen();

		if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && ( 'sound_marker' || 'place_marker' ) === $current_screen->id ) {

			wp_enqueue_style( $this->plugin_name . '-add', plugin_dir_url( __FILE__ ) . 'css/soundmap.add.css', array( 'leaflet-css' ), $this->version, 'all' );

		} elseif ( ( 'settings_page_soundmap_map_settings' === $hook ) && 'settings_page_soundmap_map_settings' === $current_screen->id ) {

			wp_enqueue_style( $this->plugin_name . '-config', plugin_dir_url( __FILE__ ) . 'css/soundmap.config.css', array( 'leaflet-css' ), $this->version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 0.1.0
	 * @param string $hook Which admin page (query-string) hook.
	 */
	public function enqueue_scripts( $hook ) {

		$current_screen = get_current_screen();

		if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && ( 'sound_marker' || 'place_marker' ) === $current_screen->id ) {

			wp_enqueue_script( $this->plugin_name . '-add', plugin_dir_url( __FILE__ ) . 'js/soundmap.add.js', array( 'jquery', 'leaflet-js' ), $this->version, false );

			$params                  = [];
			$params['settings_lat']  = round( $this->config['soundmap_settings_lat'], 7 );
			$params['settings_lng']  = round( $this->config['soundmap_settings_lng'], 7 );
			$params['settings_zoom'] = $this->config['soundmap_settings_zoom'];
			$params['locale']        = get_locale();
			$params['post_type']     = $current_screen->id;

			wp_localize_script( $this->plugin_name . '-add', 'Soundmap', $params );

		} elseif ( ( 'settings_page_soundmap_map_settings' === $hook ) && 'settings_page_soundmap_map_settings' === $current_screen->id ) {

			wp_enqueue_script( $this->plugin_name . '-config', plugin_dir_url( __FILE__ ) . 'js/soundmap.config.js', array( 'jquery', 'leaflet-js' ), $this->version, false );

			$params                  = [];
			$params['settings_lat']  = round( $this->config['soundmap_settings_lat'], 7 );
			$params['settings_lng']  = round( $this->config['soundmap_settings_lng'], 7 );
			$params['settings_zoom'] = $this->config['soundmap_settings_zoom'];
			$params['locale']        = get_locale();

			wp_localize_script( $this->plugin_name . '-config', 'Soundmap', $params );

		}

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 0.1.0
	 * @param array $links The plugin action links in the plugins
	 *                     list page (e.g. activate, delete, etc..).
	 */
	public function link_plugin_settings( $links ) {

		$links[] = sprintf( '<a class="' . $this->plugin_name . '_map_settings" href="%1$s">%2$s</a>',
			esc_url( admin_url( 'options-general.php?page=' . $this->plugin_name . '_map_settings' ) ),
			esc_html__( 'Settings', 'soundmap' )
		);

		return $links;

	}

}
