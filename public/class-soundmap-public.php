<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.1.0
 *
 * @package    Sound Map
 * @subpackage Soundmap/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sound Map
 * @subpackage Soundmap/public
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Public {

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
	 * @param      string    $soundmap       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap = $soundmap;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1.0
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

		wp_enqueue_style( $this->soundmap, plugin_dir_url( __FILE__ ) . 'css/soundmap-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1.0
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

		wp_enqueue_script( $this->soundmap, plugin_dir_url( __FILE__ ) . 'js/soundmap-public.js', array( 'jquery' ), $this->version, false );

	}

}
