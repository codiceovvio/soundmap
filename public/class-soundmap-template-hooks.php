<?php
/**
 * The template hooks of the plugin.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.3.3
 *
 * @package Soundmap/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The template hooks of the plugin.
 *
 * Defines the plugin name, version, and methods to add all the
 * hooks used in the plugin templates.
 *
 * @since   0.3.3
 * @package Soundmap/public
 * @author  Codice Ovvio codiceovvio at gmail dot com.
 */
class Soundmap_Template_Hooks {

	/**
	 * The ID of this plugin.
	 *
	 * @since  0.3.2
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  0.3.2
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Static instance of this class.
	 *
	 * Used by third-party code to remove actions hooks.
	 *
	 * @since 0.3.3
	 * @var null|object an instance of this class.
	 */
	protected static $instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.3.3
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		self::$instance = $this;

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * The map html output.
	 *
	 * Create the html needed by leaflet to attach a js map and load the markers.
	 * It accepts a number of options to pass to the javascript map object.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_map_archive' action hook.
	 *
	 * @param string $css_class   Css additional class(es) applied to the map wrapper div.
	 * @param bool   $all_markers True to get all the markers, false to get a subset. Default true.
	 * @param bool   $display     True to display, false to return the map html. Default true.
	 * @param array  $options     An array of options to pass to the js map object.
	 *
	 * @return string|void String on retrieve, null when displaying.
	 */
	public function the_map( $css_class = '', $all_markers = true, $display = true, $options = [] ) {

		// Set the css id applied to the map wrapper div.
		if ( is_soundmap_archive() || is_soundmap_taxonomy()  ) {
			$css_id = 'map-archive';
		} elseif( is_soundmap_marker() ) {
			$css_id = 'map-single';
		}

		if ( ! empty( $css_class ) ) {
			$map_html = sprintf(
				'<div id="%1$s" class="%2$s soundmap-map">
				</div>',
				esc_attr( $css_id ),
				esc_attr( $css_class )
			);
		} else {
			$map_html = sprintf(
				'<div id="%s" class="soundmap-map">
				</div>',
				esc_attr( $css_id )
			);
		}

		if ( $all_markers ) {
			// Load all markers.
		} else {
			// Load some markers.
		}
		$map_html = apply_filters( 'soundmap_map_html', $map_html, $all_markers, $options );

		// Send it out.
		if ( $display ) {
			echo $map_html; // WPCS: XSS OK.
		} else {
			return $map_html;
		}

	}

	/**
	 * Output the page wrapper start html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_page_wrapper_start' action hook.
	 */
	public function page_wrapper_start() {
		soundmap_get_template_part( 'soundmap-page', 'wrapper-start' );
	}

	/**
	 * Output the page wrapper end html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_page_wrapper_end' action hook.
	 */
	public function page_wrapper_end() {
		soundmap_get_template_part( 'soundmap-page', 'wrapper-end' );
	}

	/**
	 * Output archive pages header html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_page_header' action hook.
	 */
	public function page_header() {
		soundmap_get_template_part( 'soundmap-page', 'header' );
	}

	/**
	 * Output the marker wrapper start html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_marker_summary' action hook.
	 */
	public function marker_wrapper_start() {
		soundmap_get_template_part( 'soundmap-marker', 'wrapper-start' );
	}

	/**
	 * Output the marker wrapper end html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_marker_summary' action hook.
	 */
	public function marker_wrapper_end() {
		soundmap_get_template_part( 'soundmap-marker', 'wrapper-end' );
	}

	/**
	 * Output the marker header html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_marker_summary' action hook.
	 */
	public function marker_header() {
		soundmap_get_template_part( 'soundmap-marker', 'header' );

	}

	/**
	 * Output the marker excerpt.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_marker_summary' action hook.
	 */
	public function marker_summary() {
		soundmap_get_template_part( 'soundmap-marker', 'summary' );
	}

	/**
	 * Output the marker entry-footer html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_marker_summary' action hook.
	 */
	public function marker_footer() {
		soundmap_get_template_part( 'soundmap-marker', 'footer' );
	}

	/**
	 * Output the marker content html.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_marker_content' action hook.
	 */
	public function marker_content() {
		soundmap_get_template_part( 'soundmap-marker', 'content' );
	}

	/**
	 * Output no markers messages.
	 *
	 * Get the template part with appropriate messages when no markers are found.
	 *
	 * @since 0.3.3
	 * @see 'soundmap_no_markers_found' action hook.
	 */
	public function no_markers_found() {
		soundmap_get_template_part( 'content', 'no-markers' );
	}

	public function marker_title() {}
	public function marker_location() {}
	public function marker_address() {}
	public function marker_meta() {}
	public function marker_rec_info() {}
	public function marker_rec_file() {}
	public function marker_rec_details() {}

	public function before_main_content() {}
	public function before_marker() {}
	public function before_marker_summary() {}

	public function after_marker_summary() {}
	public function after_marker() {}

	public function marker_before_loop() {}
	public function marker_loop_start() {}
	public function marker_loop_end() {}
	public function marker_after_loop() {}

	public function marker_meta_start() {}
	public function marker_meta_end() {}

	/**
	 * Get the singleton instance of this class.
	 *
	 * Used for removing actions and/or filters declared here.
	 *
	 * @since 0.3.3
	 *
	 * @return Soundmap_Template_Hooks
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

}
