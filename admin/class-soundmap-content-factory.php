<?php
/**
 * The content types factory for the plugin.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.4.0
 *
 * @package Soundmap/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The content types factory for the plugin.
 *
 * This class adds methods to register new content types (e.g. post types), custom
 * taxonomies, as well as custom messages and filters for the created content.
 * It also holds two static arrays, one for all the content types registered with
 * this class, and the other for all the taxonomies
 *
 * @since   0.4.0
 * @package Soundmap/admin
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Content_Factory {

	/**
	 * The ID of this plugin.
	 *
	 * @since  0.4.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  0.4.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The registered content types.
	 *
	 * @since 0.4.0
	 * @var string|array $content_types The content types registered within this class.
	 */
	private static $content_types;

	/**
	 * The registered content types.
	 *
	 * @since 0.4.0
	 * @var string|array $taxonomies The taxonomies registered within this class.
	 */
	private static $taxonomies;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.4.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Create a custom content type
	 *
	 * Static method to setup a custom content type used by Sound Map plugin, passing
	 * an array with slug, singular and plural name, a description, an admin menu icon
	 * and a few other parameters. Different types of content can be added via other
	 * plugins as extensions, having the default geolocation features already set up.
	 *
	 * @since  0.4.0
	 * @access public
	 * @uses   register_post_type()
	 *
	 * @param array  $names An associative array with 'singular', 'plural', 'slug' and 'description' keys.
	 * @example $names = [
	 *              'singular'    => 'My Content Type',
	 *              'plural'      => 'My Content Types',
	 *              'slug'        => 'my_content_type',
	 *              'description' => 'The description for this custom content.'
	 *         ];
	 * @param array  $supports  WP feature(s) the content type supports, which include 'title',
	 *                          'editor', 'comments', 'revisions', 'trackbacks', 'author',
	 *                          'excerpt', 'page-attributes', 'thumbnail', 'custom-fields',
	 *                          and 'post-formats'.
	 * @param string $menu_icon The icon to show in the dashboard sidebar.
	 * @param string $cap_type  Whether it behaves like a post or like a page. Default is 'post'.
	 * @param array  $args      Array of arguments for registering the content type.
	 * @return WP_Post_Type|WP_Error The registered post type object, or an error object.
	 */
	public static function add_content_type( array $names, array $supports = [], string $menu_icon = '', string $cap_type = 'post', array $args = [] ) {

		if ( ! $names ) {
			return false;
		}
		$single       = $names['singular'];
		$plural       = $names['plural'];
		$content_type = $names['slug'];
		$description  = $names['description'];

		$labels = [
			'name'                  => _x( $plural, 'Post type general name', 'soundmap' ),
			'singular_name'         => _x( $single, 'Post type singular name', 'soundmap' ),
			'menu_name'             => _x( $plural, 'Admin Menu text', 'soundmap' ),
			'name_admin_bar'        => _x( $single, 'Add New on Toolbar', 'soundmap' ),
			'add_new'               => __( "Add New {$single}", 'soundmap' ),
			'add_new_item'          => __( "Add New {$single}", 'soundmap' ),
			'new_item'              => __( "New {$single}", 'soundmap' ),
			'edit_item'             => __( "Edit {$single}", 'soundmap' ),
			'update_item'           => __( "Update {$single}", 'soundmap' ),
			'view_item'             => __( "View {$single}", 'soundmap' ),
			'view_items'            => __( "View {$plural}", 'soundmap' ),
			'all_items'             => __( $plural, 'soundmap' ),
			'search_items'          => __( "Search {$plural}", 'soundmap' ),
			'parent_item_colon'     => __( "Parent {$single}:", 'soundmap' ),
			'archives'              => __( "{$single} archives", 'soundmap' ),
			'attributes'            => __( "{$single} attributes", 'soundmap' ),
			'not_found'             => __( "No {$plural} found", 'soundmap' ),
			'not_found_in_trash'    => __( "No {$plural} found in Trash", 'soundmap' ),
			'featured_image'        => _x( "{$single} Image", 'Overrides the "Featured Image" phrase for this post type.', 'soundmap' ),
			'set_featured_image'    => _x( "Set {$single} image", 'Overrides the "Set featured image" phrase for this post type.', 'soundmap' ),
			'remove_featured_image' => _x( "Remove {$single} image", 'Overrides the "Remove featured image" phrase for this post type.', 'soundmap' ),
			'use_featured_image'    => _x( "Use as {$single} image", 'Overrides the "Use as featured image" phrase for this post type.', 'soundmap' ),
			'insert_into_item'      => _x( "Insert into {$single}", 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post).', 'soundmap' ),
			'uploaded_to_this_item' => _x( "Uploaded to this {$single}", 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'soundmap' ),
			'items_list'            => _x( "{$plural} list", 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list".', 'soundmap' ),
			'items_list_navigation' => _x( "{$plural} list navigation", 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation".', 'soundmap' ),
			'filter_items_list'     => _x( "Filter {$plural} list", 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list".', 'soundmap' ),
		];

		// Define an array for core features support defaults.
		$default_supports = [
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',
			'trackbacks',
		];
		$supports         = wp_parse_args( $supports, $default_supports );

		// Define an array for args defaults.
		$default_args = [
			'label'               => __( $single, 'soundmap' ),
			'description'         => __( $description, 'soundmap' ),
			'labels'              => $labels,
			'supports'            => $supports,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => $menu_icon,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => $cap_type,
			'show_in_rest'        => true,
		];
		$args         = wp_parse_args( $args, $default_args );

		/**
		 * Add the new content type slug to the
		 * Soundmap_Content_Factory::content_types array.
		 */
		self::$content_types[] = $content_type;

		return register_post_type( $content_type, $args );

	}

	/**
	 * Create a custom taxonomy
	 *
	 * Static method to setup a custom taxonomy and attach it to any registered content type,
	 * passing an array with slug, singular and plural name, a description, the content type
	 * object (e.g. the slug) which the taxonomy should refer to, and an array of arguments
	 * for registering a taxonomy.
	 *
	 * @since  0.4.0
	 * @access public
	 * @uses   register_taxonomy()
	 *
	 * @example $names = [
	 *              'singular'    => 'Taxonomy name',
	 *              'plural'      => 'Taxonomies name',
	 *              'slug'        => 'taxonomy_slug',
	 *              'description' => 'The description for this taxonomy.'
	 *         ];
	 * @param array  $names        An associative array with 'singular', 'plural', 'slug' and 'description' keys.
	 * @param string $content_type Slug of the object type the taxonomy should refer to.
	 * @param array  $args         An array of arguments for registering a taxonomy.
	 */
	public static function add_taxonomy( array $names, string $content_type = '', array $args = [] ) {

		if ( ! $names ) {
			return false;
		}
		$single      = $names['singular'];
		$plural      = $names['plural'];
		$taxonomy    = $names['slug'];
		$description = $names['description'];

		$labels = [
			'name'                       => _x( $plural, 'Taxonomy General Name', 'soundmap' ),
			'singular_name'              => _x( $single, 'Taxonomy Singular Name', 'soundmap' ),
			'menu_name'                  => _x( $plural, 'Admin Menu text', 'soundmap' ),
			'all_items'                  => __( $plural, 'soundmap' ),
			'parent_item'                => __( "Parent {$single}", 'soundmap' ),
			'parent_item_colon'          => __( "Parent {$single}:", 'soundmap' ),
			'new_item_name'              => __( "New {$single}", 'soundmap' ),
			'add_new'                    => __( "Add New {$single}", 'soundmap' ),
			'add_new_item'               => __( "Add New {$single}", 'soundmap' ),
			'edit_item'                  => __( "Edit {$single}", 'soundmap' ),
			'update_item'                => __( "Update {$single}", 'soundmap' ),
			'view_item'                  => __( "View {$single}", 'soundmap' ),
			'view_items'                 => __( "View {$plural}", 'soundmap' ),
			'separate_items_with_commas' => __( "Separate {$plural} with commas", 'soundmap' ),
			'add_or_remove_items'        => __( "Add or remove {$plural}", 'soundmap' ),
			'choose_from_most_used'      => __( "Choose from most used {$plural}", 'soundmap' ),
			'popular_items'              => __( "Popular {$plural}", 'soundmap' ),
			'search_items'               => __( "Search {$plural}", 'soundmap' ),
			'not_found'                  => __( "No {$plural} Found", 'soundmap' ),
			'no_terms'                   => __( "No {$plural}", 'soundmap' ),
			'items_list'                 => __( "{$plural} list", 'soundmap' ),
			'items_list_navigation'      => __( "{$plural} list navigation", 'soundmap' ),
		];

		$default_args = [
			'labels'             => $labels,
			// Hierarchical true to act like categories, false like tags.
			'hierarchical'       => true,
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_in_quick_edit' => true,
			'show_tagcloud'      => true,
		];
		$args         = wp_parse_args( $args, $default_args );

		// Add the new taxonomy and the associated object type to $taxonomies array.
		self::$taxonomies[] = [ $taxonomy, $content_type ];

		register_taxonomy( $taxonomy, $content_type, $args );

	}

	/**
	 * Get all the registered content types.
	 *
	 * @since  0.4.0
	 * @access public
	 *
	 * @return array The registered content types slugs.
	 */
	public static function get_registered_content_types() {

		return self::$content_types;

	}

	/**
	 * Get all the registered taxonomies.
	 *
	 * @since  0.4.0
	 * @access public
	 *
	 * @return array The registered taxonomies array.
	 */
	public static function get_registered_taxonomies() {

		return self::$taxonomies;

	}

}
