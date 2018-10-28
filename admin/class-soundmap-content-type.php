<?php
/**
 * The custom content types registered by the plugin.
 *
 * Defines the plugin name, version, and the main content type with related
 * taxonomies.
 *
 * @since  0.1.1
 * @package Soundmap/admin
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The custom content types registered by the plugin.
 *
 * Defines the plugin name, version, and the main content type with related
 * taxonomies.
 *
 * @since  0.1.1
 * @package Soundmap/admin
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Content_Type {

	/**
	 * The ID of this plugin.
	 *
	 * @since  0.1.1
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  0.1.1
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.1
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Creates the Sound Marker content type
	 *
	 * @since  0.1.1
	 * @access public
	 * @uses   Soundmap_Content_Factory::add_content_type()
	 */
	public static function sound_marker_content_type() {

		$names     = [
			'singular'    => __( 'Sound Marker', 'soundmap' ),
			'plural'      => __( 'Sound Markers', 'soundmap' ),
			'slug'        => 'sound_marker',
			'description' => __( 'Custom post type for Sound Markers with geolocalization and map features', 'soundmap' ),
		];
		$menu_icon = 'dashicons-location';

		$supports = [];

		Soundmap_Content_Factory::add_content_type( $names, $supports, $menu_icon );

	}

	/**
	 * Creates the Sound Marker Category new taxonomy
	 *
	 * @since  0.1.1
	 * @access public
	 * @uses   Soundmap_Content_Factory::add_taxonomy()
	 */
	public static function sound_marker_category() {

		$names = [
			'singular'    => __( 'Sound Marker Category', 'soundmap' ),
			'plural'      => __( 'Sound Marker Categories', 'soundmap' ),
			'slug'        => 'sound_marker_category',
			'description' => __( 'Custom categories for Sound Markers', 'soundmap' ),
		];

		$object_type = 'sound_marker';

		$args = [
			'hierarchical' => true,
		];

		Soundmap_Content_Factory::add_taxonomy( $names, $object_type, $args );

	}

	/**
	 * Creates the Sound Marker Tag new taxonomy
	 *
	 * @since  0.1.1
	 * @access public
	 * @uses   Soundmap_Content_Factory::add_taxonomy()
	 */
	public static function sound_marker_tag() {

		$names = [
			'singular'    => __( 'Sound Marker Tag', 'soundmap' ),
			'plural'      => __( 'Sound Marker Tags', 'soundmap' ),
			'slug'        => 'sound_marker_tag',
			'description' => __( 'Custom tags for Sound Markers', 'soundmap' ),
		];

		$object_type = 'sound_marker';

		$args = [
			'hierarchical' => false,
		];

		Soundmap_Content_Factory::add_taxonomy( $names, $object_type, $args );

	}

	/**
	 * Automatically set sound_marker_category tree
	 *
	 * Automatically assign the parent term when a child term is selected
	 * hooking on post save or update.
	 *
	 * @since  0.5.1
	 * @param int $post_id The current post ID.
	 */
	private function sound_marker_category_tree( $post_id ) {

		global $post;

		if( isset( $post ) && $post->post_type != 'sound_marker' ) {
			return $post_id;
		}

		// Get all assigned terms.
		$terms = wp_get_post_terms( $post_id, 'sound_marker_category' );

		foreach( $terms as $term ) {

			while( $term->parent != 0 && ! has_term( $term->parent, 'sound_marker_category', $post ) ) {
				// move upward until we get to 0 level terms
				wp_set_object_terms( $post_id, array( $term->parent ), 'sound_marker_category', true );
				$term = get_term( $term->parent, 'sound_marker_category' );
			}
		}
	}

}
