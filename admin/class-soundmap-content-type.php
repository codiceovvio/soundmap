<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sound Map
 * @package    Soundmap/admin
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Content_Type {

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
	 * @since      0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Creates the Sound Marker content type
	 *
	 * @since     0.1.0
	 * @access    public
	 * @uses      register_post_type()
	 */
	public static function sound_marker_content_type() {

		$plural       = 'Sound Markers';
		$single       = 'Sound Marker';
		$description  = 'Custom post type for Sound Markers with geolocalization and map features';
		$content_type = 'sound_marker';
		$menu_icon    = 'dashicons-location';
		$cap_type     = 'post';

		$labels   = [
			'name'                  => __( $plural, 'soundmap' ),
			'singular_name'         => __( $single, 'soundmap' ),
			'menu_name'             => __( $plural, 'soundmap' ),
			'name_admin_bar'        => __( $single, 'soundmap' ),
			'archives'              => __( "{$single} archives", 'soundmap' ),
			'attributes'            => __( "{$single} attributes", 'soundmap' ),
			'parent_item_colon'     => __( "Parent {$single}:", 'soundmap' ),
			'add_new'               => __( "Add New {$single}", 'soundmap' ),
			'add_new_item'          => __( "Add New {$single}", 'soundmap' ),
			'all_items'             => __( $plural, 'soundmap' ),
			'new_item'              => __( "New {$single}", 'soundmap' ),
			'edit_item'             => __( "Edit {$single}" , 'soundmap' ),
			'update_item'           => __( "Update {$single}" , 'soundmap' ),
			'view_item'             => __( "View {$single}", 'soundmap' ),
			'view_items'            => __( "View {$plural}", 'soundmap' ),
			'search_items'          => __( "Search {$plural}", 'soundmap' ),
			'not_found'             => __( "No {$plural} Found", 'soundmap' ),
			'not_found_in_trash'    => __( "No {$plural} Found in Trash", 'soundmap' ),
			'featured_image'        => __( "{$single} Image", 'soundmap' ),
			'set_featured_image'    => __( "Set {$single} image", 'soundmap' ),
			'remove_featured_image' => __( "Remove {$single} image", 'soundmap' ),
			'use_featured_image'    => __( "Use as {$single} image", 'soundmap' ),
			'insert_into_item'      => __( "Insert into {$single}", 'soundmap' ),
			'uploaded_to_this_item' => __( "Uploaded to this {$single}", 'soundmap' ),
			'items_list'            => __( "{$plural} list", 'soundmap' ),
			'items_list_navigation' => __( "{$plural} list navigation", 'soundmap' ),
			'filter_items_list'     => __( "Filter {$plural} list", 'soundmap' ),
		];
		$supports = [
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',
			'trackbacks',
		];

		$args = [
			'label'                 => __( $single, 'soundmap' ),
			'description'           => __( $description, 'soundmap' ),
			'labels'                => $labels,
			'supports'              => $supports,
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => $menu_icon,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => $cap_type,
			'show_in_rest'          => true,
		];

		return register_post_type( $content_type, $args );

	}

	/**
	 * Creates the Sound Marker Category new taxonomy
	 *
	 * @since 	0.1.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function sound_marker_categories() {

		$plural       = 'Sound Marker Categories';
		$single       = 'Sound Marker Category';
		$description  = 'Custom categories for Sound Markers';
		$taxonomy     = 'sound_marker_category';
		$object_type  = 'sound_marker';

		$labels = [
			'name'                       => _x( $plural, 'Taxonomy General Name', 'soundmap' ),
			'singular_name'              => _x( $single, 'Taxonomy Singular Name', 'soundmap' ),
			'menu_name'                  => __( $plural, 'soundmap' ),
			'all_items'                  => __( $plural, 'soundmap' ),
			'parent_item'                => __( "Parent {$single}", 'soundmap' ),
			'parent_item_colon'          => __( "Parent {$single}:", 'soundmap' ),
			'new_item_name'              => __( "New {$single}", 'soundmap' ),
			'add_new'                    => __( "Add New {$single}", 'soundmap' ),
			'add_new_item'               => __( "Add New {$single}", 'soundmap' ),
			'edit_item'                  => __( "Edit {$single}" , 'soundmap' ),
			'update_item'                => __( "Update {$single}" , 'soundmap' ),
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

		$args   = [
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			// 'update_count_callback'      => "{$taxonomy}_posts_count",
		];

		register_taxonomy( $taxonomy, $object_type, $args );

	}

	/**
	 * Creates the Sound Marker Tag new taxonomy
	 *
	 * @since 	0.1.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function sound_marker_tags() {

		$plural       = 'Sound Marker Tags';
		$single       = 'Sound Marker Tag';
		$description  = 'Custom tags for Sound Markers';
		$taxonomy     = 'sound_marker_tag';
		$object_type  = 'sound_marker';

		$labels = [
			'name'                       => _x( $plural, 'Taxonomy General Name', 'soundmap' ),
			'singular_name'              => _x( $single, 'Taxonomy Singular Name', 'soundmap' ),
			'menu_name'                  => __( $plural, 'soundmap' ),
			'all_items'                  => __( $plural, 'soundmap' ),
			'parent_item'                => __( "Parent {$single}", 'soundmap' ),
			'parent_item_colon'          => __( "Parent {$single}:", 'soundmap' ),
			'new_item_name'              => __( "New {$single}", 'soundmap' ),
			'add_new_item'               => __( "Add New {$single}", 'soundmap' ),
			'edit_item'                  => __( "Edit {$single}" , 'soundmap' ),
			'update_item'                => __( "Update {$single}" , 'soundmap' ),
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

		$args   = [
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			// 'update_count_callback'      => "{$taxonomy}_posts_count",
		];

		register_taxonomy( $taxonomy, $object_type, $args );

	}

	/**
	 * Creates the Place Marker content type
	 *
	 * @since     0.1.1
	 * @access    public
	 * @uses      register_post_type()
	 */
	public static function place_marker_content_type() {

		$plural       = 'Place Markers';
		$single       = 'Place Marker';
		$description  = 'Custom post type for Place Markers with geolocalization and map features';
		$content_type = 'place_marker';
		$menu_icon    = 'dashicons-location-alt';
		$cap_type     = 'post';

		$labels   = [
			'name'                  => __( $plural, 'soundmap' ),
			'singular_name'         => __( $single, 'soundmap' ),
			'menu_name'             => __( $plural, 'soundmap' ),
			'name_admin_bar'        => __( $single, 'soundmap' ),
			'archives'              => __( "{$single} archives", 'soundmap' ),
			'attributes'            => __( "{$single} attributes", 'soundmap' ),
			'parent_item_colon'     => __( "Parent {$single}:", 'soundmap' ),
			'add_new'               => __( "Add New {$single}", 'soundmap' ),
			'add_new_item'          => __( "Add New {$single}", 'soundmap' ),
			'all_items'             => __( $plural, 'soundmap' ),
			'new_item'              => __( "New {$single}", 'soundmap' ),
			'edit_item'             => __( "Edit {$single}" , 'soundmap' ),
			'update_item'           => __( "Update {$single}" , 'soundmap' ),
			'view_item'             => __( "View {$single}", 'soundmap' ),
			'view_items'            => __( "View {$plural}", 'soundmap' ),
			'search_items'          => __( "Search {$plural}", 'soundmap' ),
			'not_found'             => __( "No {$plural} Found", 'soundmap' ),
			'not_found_in_trash'    => __( "No {$plural} Found in Trash", 'soundmap' ),
			'featured_image'        => __( "{$single} Image", 'soundmap' ),
			'set_featured_image'    => __( "Set {$single} image", 'soundmap' ),
			'remove_featured_image' => __( "Remove {$single} image", 'soundmap' ),
			'use_featured_image'    => __( "Use as {$single} image", 'soundmap' ),
			'insert_into_item'      => __( "Insert into {$single}", 'soundmap' ),
			'uploaded_to_this_item' => __( "Uploaded to this {$single}", 'soundmap' ),
			'items_list'            => __( "{$plural} list", 'soundmap' ),
			'items_list_navigation' => __( "{$plural} list navigation", 'soundmap' ),
			'filter_items_list'     => __( "Filter {$plural} list", 'soundmap' ),
		];
		$supports = [
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',
			'trackbacks',
		];

		$args = [
			'label'                 => __( $single, 'soundmap' ),
			'description'           => __( $description, 'soundmap' ),
			'labels'                => $labels,
			'supports'              => $supports,
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => $menu_icon,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => $cap_type,
			'show_in_rest'          => true,
		];
		return register_post_type( $content_type, $args );

	}

	/**
	 * Creates the Place Marker Category new taxonomy
	 *
	 * @since 	0.1.1
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function place_marker_categories() {

		$plural       = 'Place Marker Categories';
		$single       = 'Place Marker Category';
		$description  = 'Custom categories for Place Markers';
		$taxonomy     = 'place_marker_category';
		$object_type  = 'place_marker';

		$labels = [
			'name'                       => _x( $plural, 'Taxonomy General Name', 'soundmap' ),
			'singular_name'              => _x( $single, 'Taxonomy Singular Name', 'soundmap' ),
			'menu_name'                  => __( $plural, 'soundmap' ),
			'all_items'                  => __( $plural, 'soundmap' ),
			'parent_item'                => __( "Parent {$single}", 'soundmap' ),
			'parent_item_colon'          => __( "Parent {$single}:", 'soundmap' ),
			'new_item_name'              => __( "New {$single}", 'soundmap' ),
			'add_new'                    => __( "Add New {$single}", 'soundmap' ),
			'add_new_item'               => __( "Add New {$single}", 'soundmap' ),
			'edit_item'                  => __( "Edit {$single}" , 'soundmap' ),
			'update_item'                => __( "Update {$single}" , 'soundmap' ),
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

		$args   = [
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			// 'update_count_callback'      => "{$taxonomy}_posts_count",
		];

		register_taxonomy( $taxonomy, $object_type, $args );

	}

	/**
	 * Get all the registered content types.
	 *
	 * @since     0.3.3
	 * @access    public
	 * @return array The registered content types slugs.
	 */
	public function get_registered_types() {

		$types[] = $this->sound_marker_content_type()->name;
		$types[] = $this->place_marker_content_type()->name;

		return $types;

	}

}
