<?php


/**
 * The edit content specific functionality of the plugin.
 *
 * Defines the plugin name, version, and some cmb2 fields for the edit-post.php screen.
 * The fields are arranged into three metaboxes
 *
 * @package    Sound Map
 * @subpackage Soundmap/admin
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Content_Fields {

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
	 * The options for sound_marker content.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      array    $version    The options for sound_marker content.
	 */
	private $sound_marker_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param    string    $soundmap   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $soundmap, $version ) {

		$this->soundmap   = $soundmap;
		$this->version    = $version;

	}

	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function sound_marker_map() {

		$prefix = 'sound_marker';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_map = new_cmb2_box( array(
			'id'            => $prefix . '_map',
			'title'         => esc_html__( 'Sound Marker - recording location', 'soundmap' ),
			'object_types'  => array( 'sound_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
		) );
		$marker_map->add_field( array(
			'id'   => $prefix . '_map_div',
			'desc' => esc_html__( 'click to save a location for this recording', 'soundmap' ),
			'type' => 'text',
			'render_row_cb' => __CLASS__ . '::soundmap_render_map_div',
			'show_in_rest' => false,
		) );
		$marker_map->add_field( array(
			'id'   => $prefix . '_lat',
			'desc' => esc_html__( 'Latitude', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'alignleft thin',
		) );
		$marker_map->add_field( array(
			'id'   => $prefix . '_lng',
			'desc' => esc_html__( 'Longitude', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'alignleft thin',
		) );
		$marker_map->add_field( array(
			'desc' => esc_html__( 'Search by entering a location or an address', 'soundmap' ),
			'id'   => $prefix . '_addr',
			'type' => 'text_medium',
			'classes'    => 'thin',
			'show_in_rest' => false,
		) );

	}

	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function sound_marker_recording() {

		$prefix = 'sound_marker';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_recording = new_cmb2_box( array(
			'id'            => $prefix . '_recording',
			'title'         => esc_html__( 'Sound Marker - recording file', 'soundmap' ),
			'object_types'  => array( 'sound_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
		) );
		$marker_recording->add_field( array(
			'name'    => 'Single Audio File',
			'desc'    => 'Upload an audio file. Allowed extensions: mp3',
			'id'      => $prefix . '_audio_file',
			'type'    => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Add or Upload audio file' // Change upload button text.
			),
			// query_args are passed to wp.media's library query.
			'query_args' => array(
				'type' => 'audio/mpeg',
			),
		) );
		$marker_recording->add_field( array(
			'name'         => esc_html__( 'Multiple Audio Files', 'soundmap' ),
			'desc'         => esc_html__( 'Upload multiple audio files to create a playlist.  Allowed extensions: mp3', 'soundmap' ),
			'id'           => $prefix . '_audio_playlist',
			'type'         => 'file_list',
			// query_args are passed to wp.media's library query.
			'query_args' => array(
				'type' => 'audio/mpeg',
			),
		) );
		$marker_recording->add_field( array(
			'name' => esc_html__( 'oEmbed', 'soundmap' ),
			'desc' => esc_html__( 'Enter a SoundCloud URL. Supports only SoundCloud services', 'soundmap' ),
			'id'   => $prefix . '_embed',
			'type' => 'oembed',
		) );

	}

	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function sound_marker_details() {

		$prefix = 'sound_marker';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_details = new_cmb2_box( array(
			'id'            => $prefix . '_details',
			'title'         => esc_html__( 'Sound Marker - recording details', 'soundmap' ),
			'object_types'  => array( 'sound_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Recording Date', 'soundmap' ),
			'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
			'id'   => $prefix . '_rec_date',
			'type' => 'text_date',
			// 'date_format' => 'Y-m-d',
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Recording Time', 'soundmap' ),
			'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
			'id'   => $prefix . '_rec_time',
			'type' => 'text_time',
			'time_format' => 'H:i', // Set to 24hr format
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Author URL', 'soundmap' ),
			'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
			'id'   => $prefix . '_author_url',
			'type' => 'text_url',
			'protocols' => array( 'http', 'https', 'mailto' ), // Array of allowed protocols
			'repeatable' => true,
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Author Email', 'soundmap' ),
			'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
			'id'   => $prefix . '_author_email',
			'type' => 'text_email',
		) );

	}


	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function place_marker_map() {

		$prefix = 'place_marker';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_map = new_cmb2_box( array(
			'id'            => $prefix . '_map',
			'title'         => esc_html__( 'Place Marker - location', 'soundmap' ),
			'object_types'  => array( 'place_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
		) );
		$marker_map->add_field( array(
			'id'   => $prefix . '_map_div',
			'desc' => esc_html__( 'click to save a location for this place', 'soundmap' ),
			'type' => 'text',
			'render_row_cb' => __CLASS__ . '::soundmap_render_map_div',
			'show_in_rest' => false,
		) );
		$marker_map->add_field( array(
			'id'   => $prefix . '_lat',
			'desc' => esc_html__( 'Latitude', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'alignleft thin',
		) );
		$marker_map->add_field( array(
			'id'   => $prefix . '_lng',
			'desc' => esc_html__( 'Longitude', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'alignleft thin',
		) );
		$marker_map->add_field( array(
			'desc' => esc_html__( 'Search by entering a location or an address', 'soundmap' ),
			'id'   => $prefix . '_addr',
			'type' => 'text_medium',
			'classes'    => 'thin',
			'show_in_rest' => false,
		) );

	}


	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function place_marker_details() {

			$prefix = 'place_marker';

			/**
			 * Sample metabox to demonstrate each field type included
			 */
			$marker_details = new_cmb2_box( array(
				'id'            => $prefix . '_details',
				'title'         => esc_html__( 'Place Marker - details', 'soundmap' ),
				'object_types'  => array( 'place_marker' ), // Post type
				// Enables CMB2 REST API for this box, and set http method the box is visible in.
				'show_in_rest' => WP_REST_Server::ALLMETHODS,
			) );
			$marker_details->add_field( array(
				'name' => esc_html__( 'Wiki URL', 'soundmap' ),
				'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
				'id'   => $prefix . '_wiki_url',
				'type' => 'text_url',
				// 'date_format' => 'Y-m-d',
			) );
			$marker_details->add_field( array(
				'name' => esc_html__( 'Place Info', 'soundmap' ),
				'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
				'id'   => $prefix . '_info',
				'type' => 'textarea',
				'time_format' => 'H:i', // Set to 24hr format
			) );
			$marker_details->add_field( array(
				'name' => esc_html__( 'Author URL', 'soundmap' ),
				'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
				'id'   => $prefix . '_author_url',
				'type' => 'text_url',
				'protocols' => array( 'http', 'https', 'mailto' ), // Array of allowed protocols
			) );
			$marker_details->add_field( array(
				'name' => esc_html__( 'Author Email', 'soundmap' ),
				'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
				'id'   => $prefix . '_author_email',
				'type' => 'text_email',
			) );

		}

	/**
	 * Manually render a field.
	 *
	 * @param  array      $field_args Array of field arguments.
	 * @param  CMB2_Field $field      The field object.
	 *
	 * @since    0.1.1
	 */
	public static function soundmap_render_map_div( $field_args, $field ) {
		$classes     = $field->row_classes();
		$id          = $field->args( 'id' );
		$label       = $field->args( 'name' );
		$name        = $field->args( '_name' );
		$value       = $field->escaped_value();
		$description = $field->args( 'description' );
		?>
		<div class="cmb-row <?php echo esc_attr( $id ) . ' ' . esc_attr( $classes ); ?>">
			<h5 id="map-marker-title" class="cmb2-metabox-title"><?php echo esc_html( $label ); ?></h5>
			<p class="cmb2-metabox-description"><?php echo esc_html( $description ); ?></p>
			<div id="map-marker" class="map-marker"></div>
		</div>
		<?php
	}

}
