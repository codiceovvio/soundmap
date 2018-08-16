<?php


/**
 * The template hooks of the plugin.
 *
 * Defines the plugin name, version, and methods to add all the
 * hooks used in the plugin templates.
 *
 * @package    Sound Map
 * @package    Soundmap/public
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Template_Hooks {

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
	 * @since    0.1.1
	 * @param    string    $soundmap   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap = $soundmap;
		$this->version = $version;

	}

	/**
	 * [page_wrapper_start description]
	 * %s [description]
	 *
	 * @return [type] [description]
	 */
	public function page_wrapper_start() {

		return soundmap_get_template_part( 'soundmap-page', 'wrapper-start' );

	}

	/**
	 * [page_wrapper_end description]
	 * %s [description]
	 *
	 * @return [type] [description]
	 */
	public function page_wrapper_end() {

		return soundmap_get_template_part( 'soundmap-page', 'wrapper-end' );

	}

}
