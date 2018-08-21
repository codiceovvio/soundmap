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
	 * @var      string    $plugin_name;    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param    string    $plugin_name   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * [output_page_wrapper_start description]
	 * %s [description]
	 *
	 * @return [type] [description]
	 */
	public function output_page_wrapper_start() {

		return soundmap_get_template_part( 'soundmap-page', 'wrapper-start' );

	}

	/**
	 * [output_page_wrapper_end description]
	 * %s [description]
	 *
	 * @return [type] [description]
	 */
	public function output_page_wrapper_end() {

		return soundmap_get_template_part( 'soundmap-page', 'wrapper-end' );

	}

	/**
	 * Content Wrappers.
	 *
	 * @see soundmap_output_content_wrapper_start()
	 * @see soundmap_output_content_wrapper_end()
	 */
	public function output_content_wrapper_start() {}
	public function output_content_wrapper_end() {}

	/**
	 * Single marker content.
	 *
	 * @hooked
	 *
	 * @see Soundmap_Template_Hooks->single_marker_title()
	 * @see Soundmap_Template_Hooks->single_marker_content()
	 * @see Soundmap_Template_Hooks->single_marker_location()
	 * @see Soundmap_Template_Hooks->single_marker_address()
	 * @see Soundmap_Template_Hooks->single_marker_map()
	 * @see Soundmap_Template_Hooks->single_marker_meta()
	 * @see Soundmap_Template_Hooks->single_marker_rec_info()
	 * @see Soundmap_Template_Hooks->single_marker_rec_file()
	 * @see Soundmap_Template_Hooks->single_marker_rec_details()
	 */
	/** This action is documented in public/class-soundmap-template-hooks.php */
	public function single_marker_title() {}
	public function single_marker_content() {}
	public function single_marker_location() {}
	public function single_marker_address() {}
	public function single_marker_map() {}
	public function single_marker_meta() {}
	public function single_marker_rec_info() {}
	public function single_marker_rec_file() {}
	public function single_marker_rec_details() {}



	public function before_main_content() {}
	public function before_single_marker() {}
	public function before_single_marker_summary() {}
	public function single_marker_summary() {}
	public function after_single_marker_summary() {}
	public function after_single_marker() {}

	public function marker_before_loop() {}
	public function marker_loop_start() {}
	public function marker_loop_end() {}
	public function marker_after_loop() {}
	public function marker_title() {}

	public function archive_description() {}
	public function no_markers_found() {}

	public function marker_meta_start() {}
	public function marker_meta_end() {}

}
