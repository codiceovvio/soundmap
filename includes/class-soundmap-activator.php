<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.1.0
 *
 * @package    Sound Map
 * @subpackage Soundmap/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    Sound Map
 * @subpackage Soundmap/includes
 * @author     Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.1.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-soundmap-content-type.php';

		Soundmap_Content_Type::sound_marker_content_type();
		Soundmap_Content_Type::sound_marker_categories();
		Soundmap_Content_Type::sound_marker_tags();

		flush_rewrite_rules();

	}

}
