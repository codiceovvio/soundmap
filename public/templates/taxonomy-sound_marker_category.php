<?php
/**
 * The template for displaying taxonomy 'sound_marker_category' page.
 *
 * This template can be overridden by copying it to one of the following locations:
 *   - yourtheme/soundmap/taxonomy-sound_marker_category.php.
 *   - yourtheme/template-parts/taxonomy-sound_marker_category.php.
 *   - yourtheme/templates/taxonomy-sound_marker_category.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Soundmap/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Simply includes the sound_marker archive template.
soundmap_get_template_part( 'archive', 'sound_marker' );
