<?php
/**
 * Conditional template tags.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.3.3
 *
 * @package Soundmap/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_soundmap' ) ) {
	/**
	 * Is it any of the soundmap page templates?
	 *
	 * Returns true if on a page which uses Sound Map templates.
	 *
	 * @return bool true if viewing a soundmap generated template.
	 */
	function is_soundmap() {

		$content_type = soundmap_get_content_types();

		return apply_filters( 'is_soundmap', is_soundmap_archive( $content_type ) || is_soundmap_taxonomy( $content_type ) || is_soundmap_marker( $content_type ) );
	}
}

if ( ! function_exists( 'is_soundmap_archive' ) ) {
	/**
	 * Is it a soundmap_archive page?
	 *
	 * Returns true when viewing a marker type archive.
	 *
	 * @param string|array $content_type the slug(s) of the content type(s) to check.
	 * @return bool true if is a marker type archive
	 */
	function is_soundmap_archive( $content_type = null ) {

		if ( ! $content_type ) {
			$content_type = soundmap_get_content_types();
		}
		return ( is_post_type_archive( $content_type ) );
	}
}

if ( ! function_exists( 'is_soundmap_taxonomy' ) ) {
	/**
	 * Is it a soundmap_taxonomy page?
	 *
	 * Returns true when viewing a marker taxonomy archive.
	 *
	 * @param string|array $content_type the slug(s) of the content type(s) to check.
	 * @return bool
	 */
	function is_soundmap_taxonomy( $content_type = null ) {

		if ( ! $content_type ) {
			$content_type = soundmap_get_content_types();
		}
		return is_tax( get_object_taxonomies( $content_type ) );
	}
}

if ( ! function_exists( 'is_soundmap_category' ) ) {
	/**
	 * Is it a soundmap_category page?
	 *
	 * Returns true when viewing a marker category.
	 *
	 * @param string|array $content_type the slug(s) of the content type(s) to check.
	 * @param string       $term The term slug your checking for. Leave blank to
	 *                     return true on any. (default: '').
	 * @return bool
	 */
	function is_soundmap_category( $content_type = null, $term = '' ) {

		if ( ! $content_type ) {
			$content_type = soundmap_get_content_types();
		}
		return is_tax( $content_type . '_category', $term );
	}
}

if ( ! function_exists( 'is_soundmap_tag' ) ) {
	/**
	 * Is it a soundmap_tag page?
	 *
	 * Returns true when viewing a marker tag.
	 *
	 * @param string|array $content_type the slug(s) of the content type(s) to check.
	 * @param string       $term The term slug your checking for. Leave blank to
	 *                     return true on any. (default: '').
	 * @return bool
	 */
	function is_soundmap_tag( $content_type = null, $term = '' ) {

		if ( ! $content_type ) {
			$content_type = soundmap_get_content_types();
		}
		return is_tax( $content_type . '_tag', $term );
	}
}

if ( ! function_exists( 'is_soundmap_marker' ) ) {
	/**
	 * Is it a soundmap_marker page?
	 *
	 * Returns true when viewing a single marker.
	 *
	 * @param string|array $content_type the slug(s) of the content type(s) to check.
	 * @return bool
	 */
	function is_soundmap_marker( $content_type = null ) {

		if ( ! $content_type ) {
			$content_type = soundmap_get_content_types();
		}

		if ( ! is_array( $content_type ) ) {
			return is_singular( array( $content_type ) );
		}
		return is_singular( $content_type );

	}
}

if ( ! function_exists( 'is_soundmap_ajax' ) ) {
	/**
	 * Is it a soundmap_ajax request?
	 *
	 * Returns true when the page is loaded via ajax.
	 *
	 * @return bool
	 */
	function is_soundmap_ajax() {
		return function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : defined( 'DOING_AJAX' );
	}
}

/**
 * Simple check for validating a URL, it must start with http:// or https://.
 * and pass FILTER_VALIDATE_URL validation.
 *
 * Taken from Woocommerce.
 *
 * @param  string $url to check.
 * @return bool
 */
function is_soundmap_valid_url( $url ) {

	// Must start with http:// or https://.
	if ( 0 !== strpos( $url, 'http://' ) && 0 !== strpos( $url, 'https://' ) ) {
		return false;
	}

	// Must pass validation.
	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return false;
	}

	return true;
}
