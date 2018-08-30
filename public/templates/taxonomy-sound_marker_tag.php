<?php
/**
 * The template for displaying taxonomy 'sound_marker_tag' page.
 *
 * This template can be overridden by copying it to one of the following locations:
 *   - yourtheme/soundmap/taxonomy-sound_marker_tag.php.
 *   - yourtheme/template-parts/taxonomy-sound_marker_tag.php.
 *   - yourtheme/templates/taxonomy-sound_marker_tag.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    Sound Map
 * @package    Soundmap/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Simply includes the sound_marker archive template.
soundmap_get_template_part( 'archive', 'sound_marker' );
