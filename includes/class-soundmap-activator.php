<?php
/**
 * Fired during plugin activation
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.1.0
 *
 * @package Soundmap/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since   0.1.0
 * @package Soundmap/includes
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Activator {

	/**
	 * Actions to fire on activation
	 *
	 * Register Sound Marker content type and flush permalinks.
	 *
	 * @since 0.1.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-soundmap-content-type.php';

		Soundmap_Content_Type::sound_marker_content_type();
		Soundmap_Content_Type::sound_marker_category();
		Soundmap_Content_Type::sound_marker_tag();

		flush_rewrite_rules();

	}

}
