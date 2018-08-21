<?php

class Soundmap_Admin_Fields {

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
	 * @since    0.1.0
	 * @param    string    $plugin_name   The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->soundmap_options_page();

	}

	/**
	 * Register the settings menu for this plugin.
	 *
	 * @since    0.1.0
	 */
	private function soundmap_options_page() {

		$args = [];

		/**
		 * Register map tab options page settings.
		 */
		$args['map_tab'] = [
			'id'           => $this->plugin_name . '-map-settings',
			'title'        => 'Soundmap Options',
			'menu_title'   => 'Soundmap',
			'object_types' => ['options-page'],
			'parent_slug'  => 'options-general.php',
			'option_key'   => $this->plugin_name . '_map_settings',
			'tab_group'    => $this->plugin_name . '_map_settings',
			'tab_title'    => 'Map Settings',
			'save_button'  => esc_html__( 'Save Sound Map position', 'soundmap' ),
			'message_cb'   => __CLASS__ . '::soundmap_options_page_message_callback',
		];

		/**
		 * Register extra tab options page settings.
		 */
		$args['extra_tab'] = [
			'id'           => $this->plugin_name . '-extra-settings',
			'title'        => 'Soundmap Options',
			'menu_title'   => 'Extra Settings',
			'object_types' => ['options-page'],
			'parent_slug'  => $this->plugin_name . '_map_settings',
			'option_key'   => $this->plugin_name . '_extra_settings',
			'tab_group'    => $this->plugin_name . '_map_settings',
			'tab_title'    => 'Extra Settings',
			'save_button'  => esc_html__( 'Save Sound Map extra settings', 'soundmap' ),
			'message_cb'   => __CLASS__ . '::soundmap_options_page_message_callback',
		];

		/**
		 * Register layout tab options page settings.
		 */
		$args['layout_tab'] = [
			'id'           => $this->plugin_name . '-layout-settings',
			'title'        => 'Soundmap Options',
			'menu_title'   => 'Layout Settings',
			'object_types' => ['options-page'],
			'parent_slug'  => $this->plugin_name . '_map_settings',
			'option_key'   => $this->plugin_name . '_layout_settings',
			'tab_group'    => $this->plugin_name . '_map_settings',
			'tab_title'    => 'Layout Settings',
			'save_button'  => esc_html__( 'Save Sound Map layout settings', 'soundmap' ),
			'message_cb'   => __CLASS__ . '::soundmap_options_page_message_callback',
		];

		$this->soundmap_options = $args;

	}

	/**
	 * Register a metabox to handle soundmap options page and adds a menu item.
	 */
	public function register_options_metabox( $soundmap_options ) {

		$soundmap_options = $this->soundmap_options;


		// Setup registered tabs
		$map_tab_options = new_cmb2_box( $soundmap_options['map_tab'] );
		$extra_tab_options = new_cmb2_box( $soundmap_options['extra_tab'] );
		$layout_tab_options = new_cmb2_box( $soundmap_options['layout_tab'] );

		/**
		 * First Tab: Map settings.
		 */
		$map_tab_options->add_field( array(
			'desc' => esc_html__( 'Search by entering a location or an address', 'soundmap' ),
			'id'   => $this->plugin_name . '_settings_addr',
			'type' => 'text_medium',
			'classes'    => 'thin',
		) );
		$map_tab_options->add_field( array(
			'title'    => esc_html__( 'Map Initial Settings', 'soundmap' ),
			'desc'    => esc_html__( 'This is the default view settings when the main map is first loaded.', 'soundmap' ),
			'id'      => $this->plugin_name . '_map_div',
			'type'    => 'text',
			'render_row_cb' => __CLASS__ . '::soundmap_render_map_div',
		) );
		$map_tab_options->add_field( array(
			'id'   => $this->plugin_name . '_settings_lat',
			'desc' => esc_html__( 'Latitude', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'alignleft thin',
		) );
		$map_tab_options->add_field( array(
			'id'   => $this->plugin_name . '_settings_lng',
			'desc' => esc_html__( 'Longitude', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'alignleft thin',
		) );
		$map_tab_options->add_field( array(
			'id'   => $this->plugin_name . '_settings_zoom',
			'desc' => esc_html__( 'Zoom', 'soundmap' ),
			'type' => 'text_small',
			'classes'    => 'thin',
		) );

		/**
		* Second Tab: Extra settings.
		*/
		$extra_tab_options->add_field( array(
			$gmaps_api_help = 'Google API key. To get an API key visit google.com',
			'desc' => esc_html__( $gmaps_api_help, 'soundmap' ),
			'id'   => $this->plugin_name . '_google_api_key',
			'type' => 'text_medium',
			'classes'    => 'thin',
		) );

		/**
		* Third Tab: Layout settings.
		*/
		$layout_tab_options->add_field( array(
			'name'              => esc_html__( 'Frontend map style layer:', 'soundmap' ),
			'desc'              => esc_html__( 'Select the default map layer', 'soundmap' ),
			'id'                => $this->plugin_name . '_layer_select',
			'type'              => 'select',
			'show_option_none'  => false,
			'options'           => array(
				'osmmapnik'        => 'OpenStreetMap Mapnik',
				'osmblackandwhite' => 'OpenStreetMap Black&White',
				'esriworldimagery' => 'EsriWorld Imagery',
				'opentopomap'      => 'Open Topo Map',
			),
		) );
		$layout_tab_options->add_field( array(
			'name'    => 'Marker base Color',
			'desc' => esc_html__( 'field description (optional)', 'soundmap' ),
			'id'      => $this->plugin_name . 'marker_color',
			'type'    => 'colorpicker',
			'default' => '#d33',
		) );
		$layout_tab_options->add_field( array(
			'name' => 'Extra CSS styles',
			'desc' => esc_html__( 'You can add some extra CSS here to change the frontend map styles', 'soundmap' ),
			'id'   => 'textarea_code',
			'type' => 'textarea_code',
		) );
	}

	/**
	 * Render the map field.
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
			<span class="cmb-td cmb2-metabox-description"><?php echo esc_html( $description ); ?></span>
			<div id="map-settings" class="map-settings"></div>
		</div>
		<?php
	}

	/**
	 * Callback to define the optionss-saved message.
	 *
	 * @param CMB2  $cmb The CMB2 object.
	 * @param array $args {
	 *     An array of message arguments
	 *
	 *     @type bool   $is_options_page Whether current page is this options page.
	 *     @type bool   $should_notify   Whether options were saved and we should be notified.
	 *     @type bool   $is_updated      Whether options were updated with save (or stayed
	 *                                   the same).
	 *     @type string $setting         For add_settings_error(), Slug title of the setting
	 *                                   to which this error applies.
	 *     @type string $code            For add_settings_error(), Slug-name to identify the error.
	 *                                   Used as part of 'id' attribute in HTML output.
	 *     @type string $message         For add_settings_error(), The formatted message text
	 *                                   to display to the user (will be shown inside styled
	 *                                   `<div>` and `<p>` tags).
	 *                                   Will be 'Settings updated.' if $is_updated is true,
	 *                                   else 'Nothing to update.'
	 *     @type string $type            For add_settings_error(), Message type,
	 *                                   controls HTML class.
	 *                                   Accepts 'error', 'updated', '', 'notice-warning', etc.
	 *                                   Will be 'updated' if $is_updated is true,
	 *                                   else 'notice-warning'.
	 * }
	 *
	 * @since    0.1.1
	 */
	public static function soundmap_options_page_message_callback( $cmb, $args ) {

		if ( ! empty( $args['should_notify'] ) ) {

			if ( $args['is_updated'] ) {

				// Modify the updated message.
				$args['message'] = sprintf( esc_html__( '%1$s &mdash; %2$s Updated!', 'soundmap' ),
					$cmb->prop( 'title' ),
					$cmb->prop( 'tab_title' )
				);
			}

			add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
		}
	}

}
