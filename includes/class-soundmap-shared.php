<?php

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.1.0
 *
 * @package    Sound Map
 * @package    Soundmap/includes
 */

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for shared hooks
 * and for stylesheet and JavaScript.
 *
 * @package    Sound Map
 * @package    Soundmap/includes
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) { exit; }

class Soundmap_Shared {

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
	 * The API key for Google Maps.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $api_key    User provided API key to load Google Maps.
	 */
	private $api_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param    string    $soundmap   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap      = $soundmap;
		$this->version       = $version;
		$this->get_google_api_key();

	}

	/**
	 * Register the stylesheets for public & admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_map_styles() {

		wp_enqueue_style( 'leaflet-css', plugin_dir_url( __FILE__ ) . 'vendor/leaflet/leaflet.css', array(), $this->version, 'all' );

		if ( isset( $this->api_key ) ) {
			// wp_enqueue_style( 'leaflet-gplaces-css', plugin_dir_url( __FILE__ ) . 'vendor/leaflet-google-places-autocomplete/leaflet-gplaces-autocomplete.css', array( 'leaflet-css' ), $this->version, false );
		}

		wp_enqueue_style( 'leaflet-osm-geocoder-css', plugin_dir_url( __FILE__ ) . 'vendor/leaflet-control-osm-geocoder/Control.OSMGeocoder.css', array( 'leaflet-css' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for public & admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_map_scripts() {

		wp_enqueue_script( 'leaflet-js', plugin_dir_url( __FILE__ ) . 'vendor/leaflet/leaflet.js', array( 'jquery' ), $this->version, false );

		if ( isset( $this->api_key ) ) {

			wp_enqueue_script( 'maps-places', 'https://maps.googleapis.com/maps/api/js?key=' . $this->api_key . '&libraries=places', array( 'jquery', 'leaflet-js' ), $this->version, false );
			wp_enqueue_script( 'leaflet-gplaces-js', plugin_dir_url( __FILE__ ) . 'vendor/leaflet-google-places-autocomplete/leaflet-gplaces-autocomplete.js', array( 'jquery', 'leaflet-js', 'maps-places' ), $this->version, false );

		}

		wp_localize_script( 'maps-places', 'google_key', $this->api_key );

		wp_enqueue_script( 'leaflet-osm-geocoder-js', plugin_dir_url( __FILE__ ) . 'vendor/leaflet-control-osm-geocoder/Control.OSMGeocoder.js', array( 'jquery', 'leaflet-js' ), $this->version, false );

	}

	/**
	 * Get the API key for Maps javascript
	 *
	 * Get the API key from plugin's settings in the database, if provided
	 *
	 * @since    0.1.1
	 *
	 * @return   string    Google Maps API key if set, else null
	 */
	private function get_google_api_key() {

		// Get option for user provided API key
		$_extra_settings = get_option( $this->soundmap . '_extra_settings' );

		if ( isset( $_extra_settings[$this->soundmap . '_google_api_key'] ) ) {
			$this->api_key = $_extra_settings[$this->soundmap . '_google_api_key'];
		}

	}

}
