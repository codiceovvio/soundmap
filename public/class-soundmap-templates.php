<?php


class Soundmap_Templates {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      string    $soundmap    The ID of this plugin.
	 */
	private $soundmap;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options for this plugin.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      array    $version    The options page for this plugin.
	 */
	private $soundmap_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.1
	 * @param    string    $soundmap   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap   = $soundmap;
		$this->version    = $version;
		// $this->load_map_template();
		$this->load_single_template();
		// $this->load_archive_template();
		// $this->load_taxonomy_template();
		// $this->load_feed_template();
		// $this->load_popup_template();

	}

	public function load_single_template() {

	    global $post;

		// If single sound marker template is not found on theme or child theme directories
	    if ( $post->post_type == 'sound_marker' && $template !== locate_template( array( 'single-sound_marker.php' ) ) ) {
	        // Load the single template from soundmap plugin directory
	        return SOUNDMAP_PATH . 'public/templates/single-sound_marker.php';
	    }

	    return $template;
	}

}
