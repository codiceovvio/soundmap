<?php

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
	 * @since    0.1.0
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
			// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
			// 'context'    => 'normal',
			// 'priority'   => 'high',
			// 'show_names' => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
			// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
		) );
		$marker_map->add_field( array(
			//'name' => esc_html__( 'Sound Marker recording location', 'soundmap' ),
			'desc' => esc_html__( 'click to save a location for this recording', 'soundmap' ),
			'id'   => $this->soundmap . '_map_div',
			'type' => 'text',
			'render_row_cb' => __CLASS__ . '::soundmap_render_map_div',
		) );


		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_details = new_cmb2_box( array(
			'id'            => $prefix . '_details',
			'title'         => esc_html__( 'Sound Marker - Marker details', 'soundmap' ),
			'object_types'  => array( 'sound_marker' ), // Post type
			// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
			// 'context'    => 'normal',
			// 'priority'   => 'high',
			// 'show_names' => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
			// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
		) );

		$marker_details->add_field( array(
			'name'       => esc_html__( 'Test Text', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => $prefix . 'text',
			'type'       => 'text',
			'show_on_cb' => 'yourprefix_hide_if_no_cats', // function should return a bool value
			// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
			// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
			// 'on_front'        => false, // Optionally designate a field to wp-admin only
			// 'repeatable'      => true,
			// 'column'          => true, // Display field value in the admin post-listing columns
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Test Text Small', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => $prefix . 'textsmall',
			'type' => 'text_small',
			// 'repeatable' => true,
			// 'column' => array(
			// 	'name'     => esc_html__( 'Column Title', 'cmb2' ), // Set the admin column title
			// 	'position' => 2, // Set as the second column.
			// );
			// 'display_cb' => 'yourprefix_display_text_small_column', // Output the display of the column values through a callback.
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Test Text Medium', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => $prefix . 'textmedium',
			'type' => 'text_medium',
		) );
		$marker_details->add_field( array(
			'name'       => esc_html__( 'Read-only Disabled Field', 'cmb2' ),
			'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'         => $prefix . 'readonly',
			'type'       => 'text_medium',
			'default'    => esc_attr__( 'Hey there, I\'m a read-only field', 'cmb2' ),
			'save_field' => false, // Disables the saving of this field.
			'attributes' => array(
				'disabled' => 'disabled',
				'readonly' => 'readonly',
			),
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Website URL', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => $prefix . 'url',
			'type' => 'text_url',
			// 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
			// 'repeatable' => true,
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Test Text Email', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => $prefix . 'email',
			'type' => 'text_email',
			// 'repeatable' => true,
		) );
		$marker_details->add_field( array(
			'name' => esc_html__( 'Test Time', 'cmb2' ),
			'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'   => $prefix . 'time',
			'type' => 'text_time',
			// 'time_format' => 'H:i', // Set to 24hr format
		) );



		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$marker_recording = new_cmb2_box( array(
			'id'            => $prefix . '_recording',
			'title'         => esc_html__( 'Sound Marker - Audio file', 'soundmap' ),
			'object_types'  => array( 'sound_marker' ), // Post type
			// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
			// 'context'    => 'normal',
			// 'priority'   => 'high',
			// 'show_names' => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
			// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
		) );
		$marker_recording->add_field( array(
			'name'             => esc_html__( 'Test Select', 'cmb2' ),
			'desc'             => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'               => $prefix . 'select',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => array(
				'standard' => esc_html__( 'Option One', 'cmb2' ),
				'custom'   => esc_html__( 'Option Two', 'cmb2' ),
				'none'     => esc_html__( 'Option Three', 'cmb2' ),
			),
		) );
		$marker_recording->add_field( array(
			'name'             => esc_html__( 'Test Radio inline', 'cmb2' ),
			'desc'             => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'               => $prefix . 'radio_inline',
			'type'             => 'radio_inline',
			'show_option_none' => 'No Selection',
			'options'          => array(
				'standard' => esc_html__( 'Option One', 'cmb2' ),
				'custom'   => esc_html__( 'Option Two', 'cmb2' ),
				'none'     => esc_html__( 'Option Three', 'cmb2' ),
			),
		) );
		$marker_recording->add_field( array(
			'name'    => esc_html__( 'Test Radio', 'cmb2' ),
			'desc'    => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'      => $prefix . 'radio',
			'type'    => 'radio',
			'options' => array(
				'option1' => esc_html__( 'Option One', 'cmb2' ),
				'option2' => esc_html__( 'Option Two', 'cmb2' ),
				'option3' => esc_html__( 'Option Three', 'cmb2' ),
			),
		) );
		$marker_recording->add_field( array(
			'name'     => esc_html__( 'Test Taxonomy Select', 'cmb2' ),
			'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
			'id'       => $prefix . 'taxonomy_select',
			'type'     => 'taxonomy_select',
			'taxonomy' => 'category', // Taxonomy Slug
		) );
		$marker_recording->add_field( array(
			'name'         => esc_html__( 'Multiple Files', 'cmb2' ),
			'desc'         => esc_html__( 'Upload or add multiple images/attachments.', 'cmb2' ),
			'id'           => $prefix . 'file_list',
			'type'         => 'file_list',
			'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
		) );
		$marker_recording->add_field( array(
			'name' => esc_html__( 'oEmbed', 'cmb2' ),
			'desc' => sprintf(
				/* translators: %s: link to codex.wordpress.org/Embeds */
				esc_html__( 'Enter a youtube, twitter, or instagram URL. Supports services listed at %s.', 'cmb2' ),
				'<a href="https://codex.wordpress.org/Embeds">codex.wordpress.org/Embeds</a>'
			),
			'id'   => $prefix . 'embed',
			'type' => 'oembed',
		) );
		$marker_recording->add_field( array(
			'name'         => 'Testing Field Parameters',
			'id'           => $prefix . 'parameters',
			'type'         => 'text',
			'before_row'   => 'yourprefix_before_row_if_2', // callback.
			'before'       => '<p>Testing <b>"before"</b> parameter</p>',
			'before_field' => '<p>Testing <b>"before_field"</b> parameter</p>',
			'after_field'  => '<p>Testing <b>"after_field"</b> parameter</p>',
			'after'        => '<p>Testing <b>"after"</b> parameter</p>',
			'after_row'    => '<p>Testing <b>"after_row"</b> parameter</p>',
		) );

	}

	/**
	 * Manually render a field.
	 *
	 * @param  array      $field_args Array of field arguments.
	 * @param  CMB2_Field $field      The field object.
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
