<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.1.0
 *
 * @package Soundmap/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package Soundmap/public
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Public {

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
	 * @since  0.5.0
	 * @access private
	 * @var    array $config Settings to define the initial map setup.
	 */
	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.0
	 * @param string $plugin_name The name of the plugin.
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
	 * @since 0.5.0
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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 0.5.0
	 */
	public function register_styles() {

		// Leaflet fullscreen plugin.
		wp_register_style( 'leaflet-fullscreen', SOUNDMAP_URL . 'includes/vendor/leaflet-fullscreen/Control.FullScreen.css', array( 'leaflet' ), $this->version, false );
		// Leaflet markercluster plugin.
		wp_register_style( 'leaflet-markercluster', SOUNDMAP_URL . 'includes/vendor/leaflet-markercluster/dist/MarkerCluster.css', array( 'leaflet' ), $this->version, false );


		// Sound Map markercluster custom styles.
		wp_register_style( 'soundmap-markercluster', plugin_dir_url( __FILE__ ) . 'css/soundmap-markercluster.css', array( 'leaflet', 'leaflet-markercluster', 'leaflet-fullscreen' ), $this->version, 'all' );
		// Sound Map popup custom styles.
		wp_register_style( 'soundmap-popup', plugin_dir_url( __FILE__ ) . 'css/soundmap-popup.css', array( 'leaflet', 'leaflet-markercluster', 'leaflet-fullscreen' ), $this->version, 'all' );
		// Sound Map archives map styles.
		wp_register_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/soundmap-public.css', array( 'leaflet', 'leaflet-markercluster', 'leaflet-fullscreen' ), $this->version, 'all' );
		// Sound Map singular map styles.
		wp_register_style( $this->plugin_name . '-single', plugin_dir_url( __FILE__ ) . 'css/soundmap-single.css', array( 'leaflet', 'leaflet-fullscreen' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 0.5.0
	 */
	public function register_scripts() {

		// Leaflet fullscreen plugin.
		wp_register_script( 'leaflet-fullscreen', SOUNDMAP_URL . 'includes/vendor/leaflet-fullscreen/Control.FullScreen.js', array( 'jquery', 'leaflet' ), $this->version, false );

		// Leaflet markercluster plugin.
		wp_register_script( 'leaflet-markercluster', SOUNDMAP_URL . 'includes/vendor/leaflet-markercluster/dist/leaflet.markercluster.js', array( 'jquery', 'leaflet' ), $this->version, false );

		// Leaflet ajax plugin.
		wp_register_script( 'leaflet-ajax', SOUNDMAP_URL . 'includes/vendor/leaflet-ajax/dist/leaflet.ajax.js', array( 'jquery', 'leaflet', 'leaflet-markercluster' ), $this->version, false );

		// Leaflet featuregroup subgroup plugin.
		wp_register_script( 'leaflet-featuregroup-subgroup', SOUNDMAP_URL . 'includes/vendor/leaflet-featuregroup-subgroup/leaflet.featuregroup.subgroup.js', array( 'jquery', 'leaflet', 'leaflet-markercluster', 'leaflet-ajax' ), $this->version, false );


		// Register the archive map script.
		wp_register_script( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'js/soundmap-public.js', array( 'jquery', 'leaflet', 'leaflet-markercluster', 'leaflet-ajax', 'leaflet-featuregroup-subgroup' ), $this->version, false );

		// Register the single map script.
		wp_register_script( $this->plugin_name . '-single', plugin_dir_url( __FILE__ ) . 'js/soundmap-single.js', array( 'jquery', 'leaflet', 'leaflet-ajax' ), $this->version, false );

	}

	/**
	 * Enqueue CSS in the appropriate context.
	 *
	 * @since 0.5.0
	 */
	public function enqueue_styles() {

		if ( is_soundmap_archive() || is_soundmap_taxonomy()  ) {

			wp_enqueue_style( 'leaflet-markercluster' );
			wp_enqueue_style( 'leaflet-fullscreen' );
			wp_enqueue_style( 'soundmap-popup' );
			wp_enqueue_style( 'soundmap-markercluster' );
			wp_enqueue_style( $this->plugin_name . '-public' );

		} elseif ( is_soundmap_marker() ) {

			// wp_enqueue_style( 'leaflet-markercluster' );
			wp_enqueue_style( 'leaflet-fullscreen' );
			// wp_enqueue_style( 'soundmap-popup' );
			// wp_enqueue_style( 'soundmap-markercluster' );
			wp_enqueue_style( $this->plugin_name . '-single' );

		}
	}

	/**
	 * Set parameters for Leaflet public map.
	 *
	 * Setup parameters to pass as a js object to the public map used within templates.
	 * If no parameters are given, the setup will default to parameters needed by Sound Map archive templates. When only a marker ID is passed (e.g. inside a single marker loop) the setup will be for a single marker map, when a taxonomy slug is passed it will be for that taxonomy.
	 *
	 * @param int|null    $marker_id The marker ID passed when it's a single template.
	 * @param string|null $taxonomy  The taxonomy name when it's a specific taxonomy archive template.
	 */
	public function set_map_params( $marker_id = null, $taxonomy = null ) {

		if ( is_soundmap_archive() || is_soundmap_taxonomy() ) {

			// Get the currently registered marker types.
			$types = soundmap_get_content_types();

			if ( $types ) {
				// Setup initial empty arrays
				$rest_urls_content = [];
				$rest_urls_id      = [];

				foreach ( $types as $content_type ) {
					// Set the correct REST url to get.
					$rest_urls_content[ $content_type ] = home_url( '/wp-json/soundmap/v1/markers-content?filter=' . $content_type );
					$rest_urls_id[ $content_type ]      = home_url( '/wp-json/soundmap/v1/markers-id?filter=' . $content_type );
				}
			} else {
				$rest_urls_content[] = home_url( '/wp-json/soundmap/v1/markers-content' );
				$rest_urls_id[]      = home_url( '/wp-json/soundmap/v1/markers-id' );
			}

			wp_enqueue_script( 'leaflet-markercluster' );
			wp_enqueue_script( 'leaflet-ajax' );
			wp_enqueue_script( 'leaflet-featuregroup-subgroup' );
			wp_enqueue_script( 'leaflet-fullscreen' );

			wp_enqueue_script( $this->plugin_name . '-public' );

			$all_markers_rest_url   = home_url( '/wp-json/soundmap/v1/markers-content' );
			$sound_markers_rest_url = home_url( '/wp-json/soundmap/v1/markers-content?filter=sound_marker' );
			$place_markers_rest_url = home_url( '/wp-json/soundmap/v1/markers-content?filter=place_marker' );

			$params                           = [];
			$params['all_markers_rest_url']   = $all_markers_rest_url;
			$params['sound_markers_rest_url'] = $sound_markers_rest_url;
			$params['place_markers_rest_url'] = $place_markers_rest_url;
			$params['rest_urls_content']      = $rest_urls_content;
			$params['rest_urls_id']           = $rest_urls_id;
			$params['settings_lat']           = round( $this->config['soundmap_settings_lat'], 8 );
			$params['settings_lng']           = round( $this->config['soundmap_settings_lng'], 8 );
			$params['settings_zoom']          = $this->config['soundmap_settings_zoom'];

			wp_localize_script( $this->plugin_name . '-public', 'Soundmap', $params );

		} elseif ( is_soundmap_marker() ) {

			wp_enqueue_script( 'leaflet-ajax' );
			wp_enqueue_script( 'leaflet-fullscreen' );
			wp_enqueue_script( $this->plugin_name . '-single' );

			if ( ! empty( $marker_id ) ) {
				$params                = [];
				$params['marker_id']   = $marker_id;
				$params['marker_lat']  = round( soundmap_get_latitude( $marker_id ), 8 );
				$params['marker_lng']  = round( soundmap_get_longitude( $marker_id ), 8 );
				$params['marker_addr'] = soundmap_get_address( $marker_id );

				wp_localize_script( $this->plugin_name . '-single', 'Soundmap', $params );

			} else {
				return new WP_Error(
					'soundmap_no_marker_id',
					esc_html__( 'Hook soundmap_map_params requires the marker ID to load the single map.', 'soundmap' ),
					array( 'status' => 400 )
				);

			}
		}
	}

}
