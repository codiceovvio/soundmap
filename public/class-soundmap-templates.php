<?php
/**
 * The templates loader functionality of the plugin.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.1.0
 *
 * @package Soundmap/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The templates loader functionality of the plugin.
 *
 * Defines the plugin name, version, and methods to get the correct template
 * filenames depending from the context, and load them when required.
 *
 * @TODO Fix templates load order. needs correct fallback and override logic between theme and plugin. See also Woocommerce approach.
 * @TODO Set a common archive template for all registered post types. See those links: https://wpsites.net/wordpress-tips/use-one-archive-template-for-all-custom-post-type-archives/ https://stackoverflow.com/questions/33505064/wordpress-template-for-multiple-custom-post-types https://wordpress.stackexchange.com/questions/28520/multiple-post-types-in-archives-filter
 *
 * @package Sound Map
 * @package Soundmap/public
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Templates {

	/**
	 * The ID of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The template to load.
	 *
	 * @since  0.3.0
	 * @access protected
	 * @var    array $template The template to load.
	 */
	protected $template;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.1
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name = null, $version = null ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$template = $this->template;

	}

	/**
	 * Load a custom template file.
	 *
	 * @since 0.3.0
	 * @param string $template The template to load.
	 * @return string The template if found.
	 */
	public function load_file_template( $template ) {

		$content_type = Soundmap_Content_Factory::get_registered_content_types();

		foreach ( $content_type as $post_type ) {

			$file = $this->get_post_type_templates( $post_type );

			if ( file_exists( $this->template_loader( $file ) ) ) {
				$template = $this->template_loader( $file );
				break;
			}
		}
		return $template;

	}

	/**
	 * Get the default filename for a template.
	 *
	 * @since 0.3.0
	 * @param string $post_type The post_type slug.
	 * @return string The template slug for the file.
	 */
	protected function get_post_type_templates( $post_type ) {

		if ( ! $post_type ) {
			return false;
		}
		$default_file = '';

		if ( is_singular( $post_type ) ) {

			if ( file_exists( SOUNDMAP_PATH . 'public/templates/single-' . $post_type . '.php' ) ) {
				$default_file = 'single-' . $post_type;
			} elseif ( file_exists( SOUNDMAP_PATH . 'public/templates/single-marker.php' ) ) {
				$default_file = 'single-marker';
			} else {
				$default_file = 'single';
			}
		} elseif ( is_tax( get_object_taxonomies( $post_type ) ) ) {

			$object = get_queried_object();
			if ( is_tax( $object->taxonomy ) ) {
				if ( file_exists( SOUNDMAP_PATH . 'public/templates/taxonomy-' . $object->taxonomy . '.php' ) ) {
					$default_file = 'taxonomy-' . $object->taxonomy;
				} elseif ( file_exists( SOUNDMAP_PATH . 'public/templates/taxonomy.php' ) ) {
					$default_file = 'taxonomy';
				} else {
					$default_file = 'archive';
				}
			} else {
				$default_file = 'archive-' . $post_type;
			}
		} elseif ( is_post_type_archive( $post_type ) ) {

			$default_file = 'archive-' . $post_type;

		}

		return $default_file;

	}

	/**
	 * Return a list of paths to check for template locations.
	 *
	 * @since 0.3.2
	 * @return mixed|void
	 */
	protected function get_template_paths() {

		if ( ! SOUNDMAP_TEMPLATE_DEBUG ) {

			// Get path from theme root or subdirectories.
			$file_paths[20] = trailingslashit( get_template_directory() );
			$file_paths[21] = trailingslashit( get_template_directory() ) . 'templates';
			$file_paths[22] = trailingslashit( get_template_directory() ) . 'template-parts';
			$file_paths[23] = trailingslashit( get_template_directory() ) . 'soundmap';

			// Get path conditionally from child theme root or subdirectories.
			if ( is_child_theme() ) {
				$file_paths[10] = trailingslashit( get_stylesheet_directory() );
				$file_paths[11] = trailingslashit( get_stylesheet_directory() ) . 'templates';
				$file_paths[12] = trailingslashit( get_stylesheet_directory() ) . 'template-parts';
				$file_paths[13] = trailingslashit( get_stylesheet_directory() ) . 'soundmap';
			}
		}
		// Get path from plugin folders.
		$file_paths[50] = SOUNDMAP_PATH . 'public/partials';
		$file_paths[51] = SOUNDMAP_PATH . 'public/templates';

		/**
		 * Allow ordered list of template paths to be amended.
		 *
		 * @since 0.3.2
		 *
		 * @param array $file_paths First come folders in child theme from index 10, then
		 *                          in parent theme from 20, and in plugin from 50.
		 */
		$file_paths = apply_filters( 'soundmap_template_paths', $file_paths );

		// Sort the file paths based on priority.
		ksort( $file_paths, SORT_NUMERIC );

		return array_map( 'trailingslashit', $file_paths );
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file. If the template is
	 * not found in either of those, it looks in the theme-compat folder last.
	 *
	 * @TODO Fix templates load order.
	 *
	 * Adapted from EDD, Woocommerce and bbPress.
	 * @since 0.3.2
	 *
	 * @uses Soundmap_Templates::get_template_paths() Return a list of paths to check for template locations.
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 * @param bool         $load           If true the template file will be loaded if it is found.
	 * @param bool         $require_once   Whether to require_once or require. Default true.
	 *                                     Has no effect if $load is false.
	 *
	 * @return string The template filename if one is located.
	 */
	public function locate_template( $template_names, $load = false, $require_once = true ) {
		// No file found yet.
		$located = false;

		// Remove empty entries.
		$template_names = array_filter( (array) $template_names );
		$template_paths = $this->get_template_paths();

		// Try to find a template file.
		foreach ( $template_names as $template_name ) {

			// Continue if template is empty.
			if ( empty( $template_name ) ) {
				continue;
			}

			// Trim off any slashes from the template name.
			$template_name = ltrim( $template_name, '/' );

			// Try locating this template file by looping through the template paths.
			foreach ( $template_paths as $template_path ) {

				if ( file_exists( $template_path . $template_name ) ) {
					$located = $template_path . $template_name;
					break;
				}
			}

			if ( $located ) {
				break;
			}
		}

		if ( ( true === $load ) && ! empty( $located ) ) {
			load_template( $located, $require_once );
		}

		return $located;
	}

	/**
	 * Given a slug and optional name, create the file names of templates.
	 *
	 * @since 0.3.2
	 *
	 * @param string $slug The generic part for the template name.
	 * @param string $name The specialized part for the template name.
	 *
	 * @return array
	 */
	protected function get_template_file_names( $slug, $name ) {
		$templates = [];
		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '.php';
		}
		$templates[] = $slug . '.php';

		/**
		 * Allow template choices to be filtered.
		 *
		 * The resulting array should be in the order of most specific first, to least specific last.
		 * e.g. 0 => recipe-instructions.php, 1 => recipe.php
		 *
		 * @since 0.3.2
		 *
		 * @param array  $templates Names of template files that should be looked for,
		 *                          for given slug and name.
		 * @param string $slug      Template slug.
		 * @param string $name      Template name.
		 */
		return apply_filters( 'soundmap_get_template_file_names', $templates, $slug, $name );
	}

	/**
	 * Get template part (for templates like the content-marker).
	 *
	 * The template part is searched in this order:
	 *   - current and parent theme root folder
	 *   - current and parent theme templates, template-parts, and soundmap folders
	 *   - this plugin partials and templates folder
	 *
	 * SOUNDMAP_TEMPLATE_DEBUG will prevent overrides in themes from taking priority.
	 *
	 * Adapted from EDD and Woocommerce.
	 *
	 * @param mixed       $slug The slug name for the generic template.
	 * @param string|null $name The name of the specialised template (default: null).
	 * @param bool        $load Whether to load or not the template.
	 */
	public function get_template_part( $slug, $name = null, $load = true ) {
		// Execute code for this part.
		do_action( 'soundmap_get_template_part_' . $slug, $slug, $name );

		$load_template = apply_filters( 'soundmap_allow_template_part_' . $slug . '_' . $name, true );
		if ( false === $load_template ) {
			return '';
		}

		// Get files names of templates, for given slug and name.
		$templates = $this->get_template_file_names( $slug, $name );

		// Allow template parts to be filtered.
		$templates = apply_filters( 'soundmap_get_template_part', $templates, $slug, $name );

		// Return the part that is found.
		return $this->locate_template( $templates, $load, false );
	}

	/**
	 * Returns the path to a template file
	 *
	 * Looks for the file in these directories, in this order:
	 *    Current and parent theme root folder
	 *    Current and parent theme templates, template-parts, and soundmap folders
	 *    This plugin templates folder
	 *
	 * To use a custom soundmap template in a theme, copy the file from
	 * public/templates into your theme or child theme root (as well as
	 * a templates, template-parts or soundmap folder in your theme).
	 * Customize as needed, but keep the file name as-is. The plugin
	 * will automatically use your custom template file instead of the
	 * ones included in the plugin.
	 *
	 * @TODO Fix templates load order.
	 *
	 * @since    0.3.0
	 * @param string $slug The name of a template file.
	 * @param string $name The name of a template file.
	 * @param bool   $load Whether to load or not the file.
	 * @return string The path to the template.
	 */
	public function template_loader( $slug, $name = null, $load = false ) {
		// Execute code for this part.
		do_action( 'soundmap_get_template_part_' . $slug, $slug, $name );

		// Get files names of templates, for given slug and name.
		$templates = $this->get_template_file_names( $slug, $name );

		// Return the part that is found.
		return $this->locate_template( $templates, $load );
	}

}
