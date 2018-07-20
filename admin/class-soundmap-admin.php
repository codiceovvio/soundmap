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

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->soundmap, plugin_dir_url( __FILE__ ) . 'css/soundmap-admin.css', array( 'leaflet-css' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->soundmap, plugin_dir_url( __FILE__ ) . 'js/soundmap-admin.js', array( 'jquery', 'leaflet-js' ), $this->version, false );

	}

}
