<?php

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.1.0
 *
 * @package    Sound Map
 * @subpackage Soundmap/includes
 */

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for shared hooks
 * and for stylesheet and JavaScript.
 *
 * @package    Sound Map
 * @subpackage Soundmap/includes
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
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param    string    $soundmap   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap   = $soundmap;
		$this->version    = $version;

	}

	/**
	 * Register the stylesheets for public & admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'leaflet-css', plugin_dir_url( __FILE__ ) . 'vendor/leaflet/leaflet.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for public & admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'leaflet-js', plugin_dir_url( __FILE__ ) . 'vendor/leaflet/leaflet.js', array( 'jquery' ), $this->version, false );

	}

}
