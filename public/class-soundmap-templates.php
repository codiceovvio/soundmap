<?php


/**
 * The templates loader functionality of the plugin.
 *
 * Defines the plugin name, version, and methods to get the correct template
 * filenames depending from the context, and load them when required.
 *
 * @package    Sound Map
 * @package Soundmap/public
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Templates {

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
	* The registered content types
	*
	* @since    0.3.0
	* @access   protected
	* @var      array    $content_type    The registered content type slugs.
	*/
	protected $content_type;

	/**
	 * The template to load.
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      array    $template    The template to load.
	 */
	protected $template;

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
		$this->content_type = [
			'sound_marker',
			'place_marker'
		];

		$template = $this->template;

	}

	/**
	 * Load a custom template file
	 * %s [description]
	 *
	 * @since    0.3.0
	 * @param [type] $template [description]
	 * @return [type] [description]
	 */
	public function load_file_template( $template ) {

		$content_type = $this->content_type;

		foreach ( $content_type as $post_type ) {

			$file = $this->get_post_type_templates( $post_type );

			if ( file_exists( soundmap_get_template( $file ) ) ) {
				$template = soundmap_get_template( $file );
				break;
			}

		}
		return $template;

	}

	/**
	 * Get the default filename for a template.
	 *
	 *
	 * @since    0.3.0
	 * @param string $post_type [description]
	 * @return string [description]
	 */
	private function get_post_type_templates( $post_type = '' ) {

		if ( ! $post_type ) {
			return false;
		}

		if ( is_singular( $post_type ) ) {

			$default_file = 'single-' . $post_type;

		} elseif ( is_tax( get_object_taxonomies( $post_type ) ) ) {

			$object = get_queried_object();

			if ( is_tax( $object->taxonomy ) ) {
				//$default_file = 'taxonomy-' . $object->taxonomy;
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

		} else {

			$default_file = false;

		}
		return $default_file;
	}

	/**
	 * Get template part (for templates like the content-marker).
	 *
	 * The template part is searched in this order:
 	 *   - current theme root folder
 	 *   - parent theme root folder
 	 *   - current theme templates, template-parts, and soundmap folders
 	 *   - parent theme templates, template-parts, and soundmap folders
 	 *   - this plugin partials folder
	 *
	 * SOUNDMAP_TEMPLATE_DEBUG will prevent overrides in themes from taking priority.
	 *
	 * @param mixed  $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template (default: '').
	 */
	public static function get_template_part( $slug, $name = '' ) {

		if ( ! $slug ) {
			return false;
		}

		$template  = '';
		$locations = [];

		if ( $name ) {
			$locations[] = "{$slug}-{$name}.php";
			$locations[] = "/templates/{$slug}-{$name}.php";
			$locations[] = "/template-parts/{$slug}-{$name}.php";
			$locations[] = "/soundmap/{$slug}-{$name}.php";
		} else {
			$locations[] = "{$slug}.php";
			$locations[] = "/templates/{$slug}.php";
			$locations[] = "/template-parts/{$slug}.php";
			$locations[] = "/soundmap/{$slug}.php";
		}

		/**
		 * Filter the locations to search for a template part.
		 *
		 * @param array $locations Template part names and/or paths to check.
		 */
		apply_filters( 'soundmap_template_part_path', $locations );


		// Look in theme/slug-name.php and theme/subfolder/slug-name.php.
		if ( $name && ! SOUNDMAP_TEMPLATE_DEBUG ) {
			$template = locate_template( $locations, true );
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( SOUNDMAP_PATH . "public/partials/{$slug}-{$name}.php" ) ) {
			$template = SOUNDMAP_PATH . "public/partials/{$slug}-{$name}.php";
		}
		// Get default slug.php.
		if ( ! $template && file_exists( SOUNDMAP_PATH . "public/partials/{$slug}.php" ) ) {
			$template = SOUNDMAP_PATH . "public/partials/{$slug}.php";
		}

		// If template part doesn't exist, look in theme/slug.php and theme/subfolder/slug.php.
		if ( ! $template && ! SOUNDMAP_TEMPLATE_DEBUG ) {
			$template = locate_template( $locations, true );
		}

		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @param mixed  $template The template variable to return.
		 * @param string $slug     The slug name for the generic template.
		 * @param string $name     The name of the specialised template.
		 */
		$template = apply_filters( 'soundmap_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}

	/**
	 * Returns the path to a template file
	 *
	 * Looks for the file in these directories, in this order:
	 *    Current theme root folder
	 *    Parent theme root folder
	 *    Current theme templates, template-parts, and soundmap folders
	 *    Parent theme templates, template-parts, and soundmap folders
	 *    This plugin templates folder
	 *
	 * To use a custom soundmap template in a theme, copy the file from
	 * public/templates into your theme or child theme root (as well as
	 * a templates, template-parts or soundmap folder in your theme).
	 * Customize as needed, but keep the file name as-is. The plugin
	 * will automatically use your custom template file instead of the
	 * ones included in the plugin.
	 *
	 * @since    0.3.0
	 * @param    string    $name    The name of a template file
	 * @return   string    The path to the template
	 */
	public static function template_loader( $name ) {

		$template = '';

		if ( ! SOUNDMAP_TEMPLATE_DEBUG ) {
			$locations[] = "{$name}.php";
			$locations[] = "/templates/{$name}.php";
			$locations[] = "/template-parts/{$name}.php";
			$locations[] = "/soundmap/{$name}.php";
		}

		/**
		 * Filter the locations to search for a template file
		 *
		 * @param    array    $locations    File names and/or paths to check
		 */
		apply_filters( 'soundmap-templates-path', $locations );

		$template = locate_template( $locations, true );

		if ( empty( $template ) ) {
			// Load the template from soundmap plugin directory
			$template = SOUNDMAP_PATH . 'public/templates/' . $name . '.php';
		}
		return $template;

	}

}
