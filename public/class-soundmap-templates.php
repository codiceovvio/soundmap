<?php


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
	 * The template to load.
	 *
	 * @since    0.2.1
	 * @access   private
	 * @var      array    $template    The template to load.
	 */
	private $template;

	/**
	 * Registered content types
	 *
	 * @var array the registered content type slugs
	 */
	private $content_type;

	/**
	 * Registered taxonomies
	 *
	 * @var    array    the registered content types taxonomies.
	 */
	private $content_tax;

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
		$this->content_tax = [
			'sound_marker_category',
			'sound_marker_tag',
			'place_marker_category',

		];

		$template = $this->template;

	}

	/**
	 * Load a custom template file
	 * %s [description]
	 *
	 * @param [type] $template [description]
	 * @return [type] [description]
	 */
	public function load_file_template( $template ) {

		$content_type = $this->content_type;

		foreach ( $content_type as $post_type ) {

			$file = $this->get_post_type_templates( $post_type );

			if ( ! false == $file ) {
				$template = soundmap_get_template( $file );
				break;
			}

		}
		return $template;

	}

	/**
	 * Get the default filename for a template.
	 *
	 * @param string $post_type [description]
	 * @return string [description]
	 */
	private static function get_post_type_templates( $post_type = '' ) {

		if ( ! $post_type ) {
			return false;
		}

		if ( is_singular( $post_type ) ) {

			$default_file = 'single-' . $post_type;

		} elseif ( is_tax( get_object_taxonomies( $post_type ) ) ) {

			$object = get_queried_object();

			if ( is_tax( $object->taxonomy ) ) {
				$default_file = 'taxonomy-' . $object->taxonomy;
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
	 * Returns the path to a template file
	 *
	 * Looks for the file in these directories, in this order:
	 *    Current theme
	 *    Parent theme
	 *    Current theme templates folder
	 *    Parent theme templates folder
	 *    This plugin
	 *
	 * To use a custom soundmap template in a theme, copy the
	 * file from public/templates into a templates folder in your
	 * theme. Customize as needed, but keep the file name as-is. The
	 * plugin will automatically use your custom template file instead
	 * of the ones included in the plugin.
	 *
	 * @param    string    $name    The name of a template file
	 * @return   string    The path to the template
	 */
	public static function template_loader( $name ) {

		$template = '';

		$locations[] = "{$name}.php";
		$locations[] = "/templates/{$name}.php";

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


/**
 * Returns a template file to load
 *
 * @param    string    $name    the template file name to get,
 *                              e.g single-sound_marker, taxonomy-sound_marker_tag,
 *                              etc, without extension
 * @return   string    the template file to load
 */
function soundmap_get_template( $name ) {

	return Soundmap_Templates::template_loader( $name );

}
