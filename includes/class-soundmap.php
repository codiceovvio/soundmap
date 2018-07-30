<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.1.0
 *
 * @package    Sound Map
 * @subpackage Soundmap/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Sound Map
 * @subpackage Soundmap/includes
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Soundmap_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $soundmap    The string used to uniquely identify this plugin.
	 */
	protected $soundmap;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
		if ( defined( 'SOUNDMAP_VERSION' ) ) {
			$this->version = SOUNDMAP_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->soundmap = 'soundmap';

		$this->load_vendor_libraries();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_shared_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load required vendor libraries for this plugin.
	 *
	 * Include the following vendor files:
	 *
	 * - CMB2. Helper framework to work with custom fields.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_vendor_libraries() {

		/**
		 * Bootstrap CMB2
		 *
		 * No need to check versions or if CMB2 is already loaded...
		 * the init file does that already!
		 * Check to see if CMB2 files exist or add a missing file warning.
		 */
		if ( file_exists( SOUNDMAP_PATH . 'includes/vendor/cmb2/init.php' )) {

			require_once SOUNDMAP_PATH . 'includes/vendor/cmb2/init.php';

		} elseif ( file_exists( SOUNDMAP_PATH . 'includes/vendor/CMB2/init.php' ) ) {

			require_once SOUNDMAP_PATH . 'includes/vendor/CMB2/init.php';

		} elseif ( ! defined( 'CMB2_LOADED' ) ) {

			trigger_error( 'CMB2 Library not loaded!!' );

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
	 * @since    0.1.0
	 * @access   private
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
		 * The class responsible for defining all options used to create the templates.
		 */
		require_once SOUNDMAP_PATH . 'public/class-soundmap-templates.php';

		$this->loader = new Soundmap_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Soundmap_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Soundmap_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin          = new Soundmap_Admin( $this->get_soundmap(), $this->get_version() );
		$plugin_admin_fields   = new Soundmap_Admin_Fields( $this->get_soundmap(), $this->get_version() );
		$plugin_content_types  = new Soundmap_Content_Type( $this->get_soundmap(), $this->get_version() );
		$plugin_content_fields = new Soundmap_Content_Fields( $this->get_soundmap(), $this->get_version() );

		// Load scripts and styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Register content types
		$this->loader->add_action( 'init', $plugin_content_types, 'sound_marker_content_type' );
		$this->loader->add_action( 'init', $plugin_content_types, 'sound_marker_categories' );
		$this->loader->add_action( 'init', $plugin_content_types, 'sound_marker_tags' );
		$this->loader->add_action( 'init', $plugin_content_types, 'place_marker_content_type' );
		$this->loader->add_action( 'init', $plugin_content_types, 'place_marker_categories' );
		// Add a plugin options page
		$this->loader->add_action( 'cmb2_admin_init', $plugin_admin_fields, 'register_options_metabox' );
		$this->loader->add_filter( 'plugin_action_links_' . SOUNDMAP_BASENAME, $plugin_admin, 'link_plugin_settings' );
		// Add custom fields to content types
		$this->loader->add_action( 'cmb2_init', $plugin_content_fields, 'sound_marker_map' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public    = new Soundmap_Public( $this->get_soundmap(), $this->get_version() );
		$plugin_templates = new Soundmap_Templates( $this->get_soundmap(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks shared between public-facing and admin functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_shared_hooks() {

		$plugin_shared = new Soundmap_Shared( $this->get_soundmap(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_map_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shared, 'enqueue_map_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_map_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shared, 'enqueue_map_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_soundmap() {
		return $this->soundmap;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Soundmap_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
