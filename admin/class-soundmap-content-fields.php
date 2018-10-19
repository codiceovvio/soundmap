<?php
/**
 * The edit content specific functionality of the plugin.
 *
 * Defines the plugin name, version, and some cmb2 fields for the edit-post.php screen.
 * The fields are arranged into three metaboxes
 *
 * @package Soundmap/admin
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The edit content specific functionality of the plugin.
 *
 * Defines the plugin name, version, and some cmb2 fields for the edit-post.php screen.
 * The fields are arranged into three metaboxes
 *
 * @package Soundmap/admin
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Content_Fields {

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
	 * The options for sound_marker content.
	 *
	 * @since  0.1.1
	 * @access private
	 * @var    array $sound_marker_options The options for sound_marker content.
	 */
	private $sound_marker_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Hook in and add a metabox for Sound Marker location map. It must hook
	 * to the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function sound_marker_map() {

		$prefix = 'sound_marker';

		/**
		 * Map metabox to set a location for the sound marker.
		 */
		$marker_map = new_cmb2_box( array(
			'id'           => $prefix . '_map',
			'title'        => esc_html__( 'Sound Marker - recording location', 'soundmap' ),
			'object_types' => array( 'sound_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
		) );
		$marker_map->add_field( array(
			'id'            => $prefix . '_map_div',
			'desc'          => esc_html__( 'Click to save a location for this recording', 'soundmap' ),
			'type'          => 'text',
			'render_row_cb' => __CLASS__ . '::soundmap_render_map_div',
			'show_in_rest'  => false,
		) );
		$marker_map->add_field( array(
			'id'      => $prefix . '_lat',
			'desc'    => esc_html__( 'Latitude', 'soundmap' ),
			'type'    => 'text_small',
			'classes' => 'alignleft thin',
		) );
		$marker_map->add_field( array(
			'id'      => $prefix . '_lng',
			'desc'    => esc_html__( 'Longitude', 'soundmap' ),
			'type'    => 'text_small',
			'classes' => 'alignleft thin',
		) );
		$marker_map->add_field( array(
			'desc'         => esc_html__( 'Search by entering a location or an address', 'soundmap' ),
			'id'           => $prefix . '_addr',
			'type'         => 'text_medium',
			'classes'      => 'thin',
			'show_in_rest' => false,
		) );

	}

	/**
	 * Hook in and add a metabox for Sound Marker recording info. It
	 * must hook to the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function sound_marker_recording() {

		$prefix = 'sound_marker';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_recording = new_cmb2_box( array(
			'id'           => $prefix . '_recording',
			'title'        => esc_html__( 'Sound Marker - recording file', 'soundmap' ),
			'object_types' => array( 'sound_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
			'context'      => 'side', //  'normal', 'advanced', 'side', form_top, before_permalink, after_title, and after_editor
			'priority'     => 'high',  //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
			//'remove_box_wrap' => true,
		) );
		$marker_recording->add_field( array(
			'name'       => 'Single Audio File',
			'desc'       => 'Upload an audio file. Allowed extensions: mp3',
			'id'         => $prefix . '_audio_file',
			'type'       => 'file',
			'options'    => array(
				'url' => false, // Hide the text input for the url.
			),
			'text'       => array(
				'add_upload_file_text' => 'Add or Upload audio file', // Change upload button text.
			),
			// Query_args are passed to wp.media's library query.
			'query_args' => array(
				'type' => 'audio/mpeg',
			),
		) );
		$marker_recording->add_field( array(
			'name'       => esc_html__( 'Multiple Audio Files', 'soundmap' ),
			'desc'       => esc_html__( 'Upload multiple audio files to create a playlist.  Allowed extensions: mp3', 'soundmap' ),
			'id'         => $prefix . '_audio_playlist',
			'type'       => 'file_list',
			// Query_args are passed to wp.media's library query.
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
	 * Hook in and add a metabox for Sound Marker recording details. It
	 * must hook to the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function sound_marker_details() {

		$prefix = 'sound_marker';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_details = new_cmb2_box( array(
			'id'           => $prefix . '_details',
			'title'        => esc_html__( 'Sound Marker - recording details', 'soundmap' ),
			'object_types' => array( 'sound_marker' ), // Post type
			// Enables CMB2 REST API for this box, and set http method the box is visible in.
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
		) );
		$marker_details->add_field( array(
			'name'        => esc_html__( 'Recording Date', 'soundmap' ),
			'desc'        => esc_html__( 'The date is derived from the uploaded file timestamp. If incorrect, you can update this field to fix it.', 'soundmap' ),
			'id'          => $prefix . '_rec_date',
			'type'        => 'text_date',
		) );
		$marker_details->add_field( array(
			'name'        => esc_html__( 'Recording Time', 'soundmap' ),
			'desc'        => esc_html__( 'The time is derived from the uploaded file timestamp. If incorrect, you can update this field to fix it.', 'soundmap' ),
			'id'          => $prefix . '_rec_time',
			'type'        => 'text_time',
			'time_format' => 'H:i', // Set to 24hr format.
		) );
		$marker_details->add_field( array(
			'id'          => $prefix . '_license',
			'name'        => esc_html__( 'Recording License', 'soundmap' ),
			'desc'        => esc_html__( 'Recording license', 'soundmap' ),
			'type'        => 'text_small',
		) );
		$marker_details->add_field( array(
			'id'          => $prefix . '_license',
			'name'        => esc_html__( 'Recording License', 'soundmap' ),
			'desc'        => esc_html__( 'Choose which CreativeCommons license should apply to this recording.', 'soundmap' ),
			'type'        => 'radio',
			'options'     => array(
				'ccby'     => __( 'CC BY - Attribution', 'soundmap' ),
				'ccbysa'   => __( 'CC BY-SA - Attribution-ShareAlike', 'soundmap' ),
				'ccbynd'   => __( 'CC BY-ND - Attribution-NoDerivs', 'soundmap' ),
				'ccbync'   => __( 'CC BY-NC - Attribution-NonCommercial', 'soundmap' ),
				'ccbyncsa' => __( 'CC BY-NC-SA - Attribution-NonCommercial-ShareAlike', 'soundmap' ),
				'ccbyncnd' => __( 'CC BY-NC-ND - Attribution-NonCommercial-NoDerivs', 'soundmap' ),
			),
			'default' => 'ccby',
		) );
		$marker_details->add_field( array(
			'name'        => esc_html__( 'Author URL', 'soundmap' ),
			'desc'        => esc_html__( 'Optional website or other URL', 'soundmap' ),
			'id'          => $prefix . '_author_url',
			'type'        => 'text_url',
			'protocols'   => array( 'http', 'https', 'mailto' ), // Array of allowed protocols.
			//'repeatable' => true,
		) );
		$marker_details->add_field( array(
			'name'        => esc_html__( 'Author Email', 'soundmap' ),
			'desc'        => esc_html__( 'The Author email', 'soundmap' ),
			'id'          => $prefix . '_author_email',
			'type'        => 'text_email',
		) );

	}

	/**
	 * Manually render the map html field.
	 *
	 * @since 0.1.1
	 *
	 * @param array      $field_args Array of field arguments.
	 * @param CMB2_Field $field      The field object.
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
