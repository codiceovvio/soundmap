<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.1.0
 *
 * @package Soundmap/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since   0.1.0
 * @package Soundmap/includes
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    Soundmap_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		if ( defined( 'SOUNDMAP_VERSION' ) ) {
			$this->version = SOUNDMAP_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'soundmap';

		$this->load_vendor_libraries();
		$this->load_dependencies();
		$this->load_global_functions();
		$this->set_locale();
		$this->define_shared_hooks();
		$this->define_admin_hooks();
		$this->define_content_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();
		$this->register_rest_routes();

		do_action( 'soundmap_init' );

	}

	/**
	 * Load required vendor libraries for this plugin.
	 *
	 * Include the following vendor files:
	 *
	 * - CMB2. Helper framework to work with custom fields.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function load_vendor_libraries() {

		/**
		 * Bootstrap CMB2
		 *
		 * No need to check versions or if CMB2 is already loaded...
		 * the init file does that already!
		 * Check to see if CMB2 files exist or add a missing file warning.
		 */
		if ( file_exists( SOUNDMAP_PATH . 'includes/vendor/cmb2/init.php' ) ) {

			require_once SOUNDMAP_PATH . 'includes/vendor/cmb2/init.php';

		} elseif ( file_exists( SOUNDMAP_PATH . 'includes/vendor/CMB2/init.php' ) ) {

			require_once SOUNDMAP_PATH . 'includes/vendor/CMB2/init.php';

		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Soundmap_Loader. Orchestrates the hooks of the plugin.
	 * - Soundmap_i18n. Defines internationalization functionality.
	 * - Soundmap_Admin. Defines all hooks for the admin area.
	 * - Soundmap_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once SOUNDMAP_PATH . 'includes/class-soundmap-loader.php';

		/**
		* The class responsible for defining all actions that occur in the public & admin area.
		*/
		require_once SOUNDMAP_PATH . 'includes/class-soundmap-shared.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once SOUNDMAP_PATH . 'includes/class-soundmap-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once SOUNDMAP_PATH . 'admin/class-soundmap-admin.php';

		/**
		* The class responsible for defining all admin fields and metaboxes.
		*/
		require_once SOUNDMAP_PATH . 'admin/class-soundmap-admin-fields.php';

		/**
		 * The class responsible for creating new custom content types.
		 */
		require_once SOUNDMAP_PATH . 'admin/class-soundmap-content-factory.php';

		/**
		 * The class responsible for defining all custom content types.
		 */
		require_once SOUNDMAP_PATH . 'admin/class-soundmap-content-type.php';

		/**
		 * The class responsible for defining all custom content fields and metaboxes.
		 */
		require_once SOUNDMAP_PATH . 'admin/class-soundmap-content-fields.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once SOUNDMAP_PATH . 'public/class-soundmap-public.php';

		/**
		 * The class responsible for defining all the functions used to get all the
		 * template files with a correct hierarchy.
		 */
		require_once SOUNDMAP_PATH . 'public/class-soundmap-templates.php';

		/**
		 * The class responsible for defining all the hooks used to build the
		 * template files output.
		 */
		require_once SOUNDMAP_PATH . 'public/class-soundmap-template-hooks.php';

		/**
		 * The class responsible for creating all REST routes and custom endpoints.
		 */
		require_once SOUNDMAP_PATH . 'includes/class-soundmap-rest-routes.php';

		// Instantiate the main plugin loader.
		$this->loader = new Soundmap_Loader();

	}

	/**
	 * Load all the functions which are in global scope.
	 *
	 * Include the following files:
	 *
	 * - soundmap-api       Orchestrate the hooks used as template tags.
	 * - soundmap-templates Load all needed template parts via hooks.
	 *
	 * @since  0.3.1
	 * @access private
	 */
	private function load_global_functions() {

		/**
		* The API for soundmap template tags and all global functions used
		* within the plugin via hooks.
		*/
		require_once SOUNDMAP_PATH . 'includes/soundmap-api.php';

		/**
		* The API for soundmap conditional template tags.
		*/
		require_once SOUNDMAP_PATH . 'includes/soundmap-conditional-tags.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Soundmap_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new Soundmap_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin        = new Soundmap_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_fields = new Soundmap_Admin_Fields( $this->get_plugin_name(), $this->get_version() );

		// Load scripts and styles.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Add plugin settings link in plugins page.
		$this->loader->add_filter( 'plugin_action_links_' . SOUNDMAP_BASENAME, $plugin_admin, 'link_plugin_settings' );

		// Add a plugin options page via CMB2.
		$this->loader->add_action( 'cmb2_admin_init', $plugin_admin_fields, 'register_options_metabox' );

	}

	/**
	 * Register all of the hooks for the custom content functionality
	 * of the plugin.
	 *
	 * @since  0.4.0
	 * @access private
	 */
	private function define_content_hooks() {

		$plugin_content_type   = new Soundmap_Content_Type( $this->get_plugin_name(), $this->get_version() );
		$plugin_content_fields = new Soundmap_Content_Fields( $this->get_plugin_name(), $this->get_version() );

		// Register content types.
		$this->loader->add_action( 'init', $plugin_content_type, 'sound_marker_content_type' );
		$this->loader->add_action( 'init', $plugin_content_type, 'sound_marker_category' );
		$this->loader->add_action( 'init', $plugin_content_type, 'sound_marker_tag' );

		// Add custom fields to content types via CMB2.
		$this->loader->add_action( 'cmb2_init', $plugin_content_fields, 'sound_marker_map' );
		$this->loader->add_action( 'cmb2_init', $plugin_content_fields, 'sound_marker_recording' );
		$this->loader->add_action( 'cmb2_init', $plugin_content_fields, 'sound_marker_details' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function define_public_hooks() {

		$plugin_public = new Soundmap_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'register_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_scripts' );


		/**
		 * Get proper map parameters.
		 *
		 * @see 'soundmap_map_params' action hook
		 *
		 * @uses Soundmap_Public->set_map_params()
		 */
		$this->loader->add_action( 'soundmap_map_params', $plugin_public, 'set_map_params', 10, 2 );
		$this->loader->add_action( 'soundmap_map_params', $plugin_public, 'enqueue_styles' );

	}

	/**
	 * Register all of the hooks related to the templates.
	 *
	 * @since  0.3.1
	 * @access private
	 */
	private function define_template_hooks() {

		$plugin_templates      = new Soundmap_Templates( $this->get_plugin_name(), $this->get_version() );
		$plugin_template_hooks = new Soundmap_Template_Hooks( $this->get_plugin_name(), $this->get_version() );

		/**
		 * Load the right file template.
		 *
		 * Include the proper template in a given context.
		 * (e.g. sigle, archive, etc..)
		 */
		$this->loader->add_filter( 'template_include', $plugin_templates, 'load_file_template' );

		/**
		 * Page wrapper.
		 *
		 * @see 'soundmap_page_wrapper_start' action hook
		 * @see 'soundmap_page_wrapper_end' action hook
		 *
		 * @uses Soundmap_Template_Hooks->page_wrapper_start()
		 * @uses Soundmap_Template_Hooks->page_wrapper_end()
		 */
		$this->loader->add_action( 'soundmap_page_wrapper_start', $plugin_template_hooks, 'page_wrapper_start', 10 );
		$this->loader->add_action( 'soundmap_page_wrapper_end', $plugin_template_hooks, 'page_wrapper_end', 10 );

		/**
		 * Page and marker headings.
		 *
		 * @see 'soundmap_page_header' action hook
		 * @see 'soundmap_marker_header' action hook
		 *
		 * @uses Soundmap_Template_Hooks->page_header()
		 * @uses Soundmap_Template_Hooks->marker_header()
		 */
		$this->loader->add_action( 'soundmap_page_header', $plugin_template_hooks, 'page_header', 10 );
		$this->loader->add_action( 'soundmap_marker_header', $plugin_template_hooks, 'marker_header', 10 );

		/**
		 * Content wrapper.
		 *
		 * @see 'soundmap_marker_summary' action hook
		 *
		 * @uses Soundmap_Template_Hooks->marker_wrapper_start()
		 * @uses Soundmap_Template_Hooks->marker_header()
		 * @uses Soundmap_Template_Hooks->marker_summary()
		 * @uses Soundmap_Template_Hooks->marker_footer()
		 * @uses Soundmap_Template_Hooks->marker_wrapper_end()
		 */
		$this->loader->add_action( 'soundmap_marker_summary', $plugin_template_hooks, 'marker_wrapper_start', 10 );
		$this->loader->add_action( 'soundmap_marker_summary', $plugin_template_hooks, 'marker_header', 15 );
		$this->loader->add_action( 'soundmap_marker_summary', $plugin_template_hooks, 'marker_summary', 20 );
		$this->loader->add_action( 'soundmap_marker_summary', $plugin_template_hooks, 'marker_footer', 25 );
		$this->loader->add_action( 'soundmap_marker_summary', $plugin_template_hooks, 'marker_wrapper_end', 30 );

		/**
		 * Marker content.
		 *
		 * @see 'soundmap_marker_content' action hook
		 * @see 'soundmap_marker_footer' action hook
		 *
		 * @uses Soundmap_Template_Hooks->marker_content()
		 * @uses Soundmap_Template_Hooks->marker_footer()
		 *
		 * @TODO Soundmap_Template_Hooks->marker_title()
		 * @TODO Soundmap_Template_Hooks->marker_location()
		 * @TODO Soundmap_Template_Hooks->marker_address()
		 * @TODO Soundmap_Template_Hooks->marker_map()
		 * @TODO Soundmap_Template_Hooks->marker_meta()
		 * @TODO Soundmap_Template_Hooks->marker_rec_info()
		 * @TODO Soundmap_Template_Hooks->marker_rec_file()
		 * @TODO Soundmap_Template_Hooks->marker_rec_details()
		 */
		$this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_content', 10 );
		$this->loader->add_action( 'soundmap_marker_footer', $plugin_template_hooks, 'marker_footer', 10 );
		// $this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_location', 25 );
		// $this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_address', 30 );
		// $this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_map', 35 );
		// $this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_meta', 40 );
		// $this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_rec_info', 45 );
		// $this->loader->add_action( 'soundmap_marker_content', $plugin_template_hooks, 'marker_rec_file', 50 );
		// $this->loader->add_action( 'soundmap_after_single', $plugin_template_hooks, 'marker_rec_details', 10 );

		/**
		 * Archives.
		 *
		 * @see 'soundmap_map_archive' action hook
		 *
		 * @uses Soundmap_Template_Hooks->the_map()
		 */
		$this->loader->add_action( 'soundmap_map_archive', $plugin_template_hooks, 'the_map', 10, 2 );

		/**
		 * Single.
		 *
		 * @see 'soundmap_map_single' action hook
		 *
		 * @uses Soundmap_Template_Hooks->the_map()
		 */
		$this->loader->add_action( 'soundmap_map_single', $plugin_template_hooks, 'the_map', 10, 2 );

		/**
		 * Markers Loop.
		 *
		 * @uses Soundmap_Template_Hooks->no_markers_found()
		 */
		$this->loader->add_action( 'soundmap_no_markers_found', $plugin_template_hooks, 'no_markers_found', 20 );

		/**
		 * Map template functions.
		 *
		 * @see 'soundmap_marker_footer' action hook
		 *
		 * @uses Soundmap_Template_Hooks->get_all_markers()
		 * @uses Soundmap_Template_Hooks->get_single_marker()
		 * @uses Soundmap_Template_Hooks->get_taxonomy_markers()
		 */
		// $this->loader->add_filter( 'soundmap_map_full', $plugin_template_hooks, 'get_all_markers' );
		// $this->loader->add_filter( 'soundmap_map_single', $plugin_template_hooks, 'get_single_marker', 99 );
		// $this->loader->add_filter( 'soundmap_map_taxonomy', $plugin_template_hooks, 'get_taxonomy_markers', 99 );

	}

	/**
	 * Register all of the hooks shared between public-facing and admin functionality
	 * of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function define_shared_hooks() {

		$plugin_shared = new Soundmap_Shared( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_map_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shared, 'enqueue_map_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_map_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shared, 'enqueue_map_scripts' );

	}

	/**
	 * Define REST routes and endpoints used by Sound Map.
	 *
	 * @since    0.5.0
	 * @access   private
	 */
	private function register_rest_routes() {

		$plugin_rest_api = new Soundmap_Rest_Routes( $this->get_plugin_name(), $this->get_version() );
		// Add an admin notice if WordPress version is < 4.7 or WP-API plugin is not installed.
		$this->loader->add_action( 'admin_notices', $plugin_rest_api, 'missing_rest_api_warning' );
		// Load all custom endpoints.
		$this->loader->add_action( 'rest_api_init', $plugin_rest_api, 'register_routes' );

		// Delete all markers transients on marker save or update.
		$this->loader->add_action( 'save_post', $plugin_rest_api, 'delete_all_content_transients' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 0.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  0.1.0
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  0.1.0
	 * @return Soundmap_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  0.1.0
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
