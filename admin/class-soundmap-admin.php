<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.1.0
 *
 * @package    Sound Map
 * @subpackage Soundmap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sound Map
 * @subpackage Soundmap/admin
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $soundmap    The ID of this plugin.
	 */
	private $soundmap;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Define a private array to hold Map initial settings.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      array    $config    Settings to define the initial map setup.
	 */
	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param    string    $soundmap   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap   = $soundmap;
		$this->version    = $version;
		$this->load_options();

	}

	/**
	 * Load Sound Map options
	 *
	 * Load options from the database, and parse them with defaults else define an initial set for map defaults.
	 *
	 * @since    0.1.1
	 */
	private function load_options() {

		$_config = [];

		// Load defaults;
		$defaults = [
			$this->soundmap . '_settings_lat' => '41.9097306',
			$this->soundmap . '_settings_lng' => '12.2558141',
			$this->soundmap . '_settings_zoom' => '11'
		];

		// Get saved options from database
		$_config      = get_option( $this->soundmap . '_map_settings' );
		// Parse options with defaults
		$_config      = wp_parse_args( $_config, $defaults );
		// Set parsed options to pass to js
		$this->config = $_config;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles( $hook ) {

		$current_screen = get_current_screen();

		if ( ( $hook == 'post.php' || $hook == 'post-new.php' ) && $current_screen->id == 'sound_marker' ) {

			wp_enqueue_style( $this->soundmap, plugin_dir_url( __FILE__ ) . 'css/soundmap.add.css', array( 'leaflet-css' ), $this->version, 'all' );

		} elseif ( ( $hook == 'settings_page_soundmap_map_settings' ) && $current_screen->id == 'settings_page_soundmap_map_settings' ) {

			wp_enqueue_style( $this->soundmap, plugin_dir_url( __FILE__ ) . 'css/soundmap.config.css', array( 'leaflet-css' ), $this->version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts( $hook ) {

		$current_screen = get_current_screen();

		if ( ( $hook == 'post.php' || $hook == 'post-new.php' ) && $current_screen->id == 'sound_marker' ) {

			wp_enqueue_script( $this->soundmap . '-add', plugin_dir_url( __FILE__ ) . 'js/soundmap.add.js', array( 'jquery', 'leaflet-js' ), $this->version, false );

			$params                  = [];
			$params['settings_lat']  = round( $this->config['soundmap_settings_lat'], 7 );
			$params['settings_lng']  = round( $this->config['soundmap_settings_lng'], 7 );
			$params['settings_zoom'] = $this->config['soundmap_settings_zoom'];
			$params['locale']        = get_locale();

			wp_localize_script( $this->soundmap . '-add', 'Soundmap', $params );

		} elseif ( ( $hook == 'settings_page_soundmap_map_settings' ) && $current_screen->id == 'settings_page_soundmap_map_settings' ) {

			wp_enqueue_script( $this->soundmap . '-config', plugin_dir_url( __FILE__ ) . 'js/soundmap.config.js', array( 'jquery', 'leaflet-js' ), $this->version, false );

			$params                  = [];
			$params['settings_lat']  = round( $this->config['soundmap_settings_lat'], 7 );
			$params['settings_lng']  = round( $this->config['soundmap_settings_lng'], 7 );
			$params['settings_zoom'] = $this->config['soundmap_settings_zoom'];
			$params['locale']        = get_locale();

			wp_localize_script( $this->soundmap . '-config', 'Soundmap', $params );

		}

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.1.0
	 */
	public function link_plugin_settings( $links ) {

		$links[] = sprintf( '<a class="' . $this->soundmap . '_map_settings" href="%1$s">%2$s</a>',
			esc_url( admin_url( 'options-general.php?page=' . $this->soundmap . '_map_settings' ) ),
			esc_html__( 'Settings', 'soundmap' )
		);

		return $links;

	}

}
