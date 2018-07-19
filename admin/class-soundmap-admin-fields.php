<?php

class Soundmap_Admin_Fields {

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
	 * The options for this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      array    $version    The options page for this plugin.
	 */
	private $soundmap_options;

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
		 * Registers main options page menu item and form.
		 */
		$args['map_tab'] = [
			'id'           => $this->soundmap . '-map-settings',
			'title'        => 'Soundmap Options',
			'menu_title'   => 'Soundmap',
			'object_types' => ['options-page'],
			'parent_slug'  => 'options-general.php',
			'option_key'   => 'soundmap_map_settings',
			'tab_group'    => 'soundmap_map_settings',
			'tab_title'    => 'Map Settings',
		];

		/**
		 * Registers secondary options page, and set main item as parent.
		 */
		$args['audio_tab'] = [
			'id'           => $this->soundmap . '-audio-settings',
			'title'        => 'Soundmap Options',
			'menu_title'   => 'Audio Settings',
			'object_types' => ['options-page'],
			'option_key'   => 'soundmap_secondary_options',
			'parent_slug'  => 'soundmap_map_settings',
			'tab_group'    => 'soundmap_map_settings',
			'tab_title'    => 'Audio Settings',
		];

		/**
		 * Registers tertiary options page, and set main item as parent.
		 */
		$args['layout_tab'] = [
			'id'           => $this->soundmap . '-layout-settings',
			'title'        => 'Soundmap Options',
			'menu_title'   => 'Layout Settings',
			'object_types' => ['options-page'],
			'option_key'   => 'soundmap_tertiary_options',
			'parent_slug'  => 'soundmap_map_settings',
			'tab_group'    => 'soundmap_map_settings',
			'tab_title'    => 'Layout Settings',
		];

		$this->soundmap_options = $args;

	}

	/**
	 * Hook in and register a metabox to handle a theme options page and adds a menu item.
	 */
	public function register_options_metabox( $soundmap_options ) {

		$soundmap_options = $this->soundmap_options;

		// setup registered tabs
		$map_tab_options = new_cmb2_box( $soundmap_options['map_tab'] );
		$audio_tab_options = new_cmb2_box( $soundmap_options['audio_tab'] );
		$layout_tab_options = new_cmb2_box( $soundmap_options['layout_tab'] );

		/**
		 * First Tab: Map settings.
		 */
		$map_tab_options->add_field( array(
			'name'    => esc_html__( 'Map Initial Settings', 'soundmap' ),
			'desc'    => esc_html__( 'This is the default view settings when the main map is first loaded.', 'soundmap' ),
			'id'      => $this->soundmap . '_map_div',
			'type'    => 'text',
			'render_row_cb' => __CLASS__ . '::soundmap_render_map_div',
		) );

		/**
		* Second Tab: Audio settings.
		*/
		$audio_tab_options->add_field( array(
			'name'    => 'Test Radio',
			'desc'    => 'field description (optional)',
			'id'      => 'radio',
			'type'    => 'radio',
			'options' => array(
				'option1' => 'Option One',
				'option2' => 'Option Two',
				'option3' => 'Option Three',
			),
		) );

		/**
		* Third Tab: Layout settings.
		*/
		$layout_tab_options->add_field( array(
			'name'    => 'Site Background Color',
			'desc'    => 'field description (optional)',
			'id'      => 'bg_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
		) );
		$layout_tab_options->add_field( array(
			'name' => 'Test Text Area for Code',
			'desc' => 'field description (optional)',
			'id'   => 'textarea_code',
			'type' => 'textarea_code',
		) );
	}

	/**
	 * A CMB2 options-page display callback override which adds tab navigation among
	 * CMB2 options pages which share this same display callback.
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 */
	public function soundmap_options_display_with_tabs( $cmb_options ) {

		$tabs = $this->soundmap_options_page_tabs( $cmb_options );

		?>

		<div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
			<?php if ( get_admin_page_title() ) : ?>
				<h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
			<?php endif; ?>
			<h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
					<a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
				<?php endforeach; ?>
			</h2>
			<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
				<input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
				<?php $cmb_options->options_page_metabox(); ?>
				<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
			</form>
		</div>

		<?php

	}

	/**
	 * Gets navigation tabs array for CMB2 options pages which share the given
	 * display_cb param.
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 *
	 * @return array Array of tab information.
	 */
	public function soundmap_options_page_tabs( $cmb_options ) {

		$tab_group = $cmb_options->cmb->prop( 'tab_group' );
		$tabs      = array();

		foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
			if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
				$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
					? $cmb->prop( 'tab_title' )
					: $cmb->prop( 'title' );
			}
		}

		return $tabs;

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
			<p class="description"><?php echo esc_html( $description ); ?></p>
			<div id="map-settings" class="map-settings"></div>
		</div>
		<?php
	}

}
