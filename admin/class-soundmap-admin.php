<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      1.0.0
 *
 * @package    Soundmap
 * @subpackage Soundmap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Soundmap
 * @subpackage Soundmap/admin
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $soundmap    The ID of this plugin.
	 */
	private $soundmap;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Soundmap_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Soundmap_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->soundmap, plugin_dir_url( __FILE__ ) . 'css/soundmap-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Soundmap_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Soundmap_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->soundmap, plugin_dir_url( __FILE__ ) . 'js/soundmap-admin.js', array( 'jquery' ), $this->version, false );

	}

}
