<?php
/**
 * The template for displaying all single sound markers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Soundmap/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
	<?php
	/**
	 * Hook: soundmap_marker_wrapper_start.
	 *
	 * @uses Soundmap_Template_Hooks->marker_wrapper_start()
	 */
	do_action( 'soundmap_marker_wrapper_start' );
	?>

		<?php
		/**
		 * Hook: soundmap_marker_header.
		 *
		 * @uses Soundmap_Template_Hooks->marker_header()
		 */
		do_action( 'soundmap_marker_header' );
		?>

			<?php
			/**
			 * Hook: soundmap_map_params.
			 *
			 * @uses Soundmap_Public->set_map_params()
			 */
			do_action( 'soundmap_map_params', get_the_ID() );
			?>

			<?php
			/**
			 * Hook: soundmap_map_single.
			 *
			 * Display the map html for archive pages.
			 *
			 * @param string $css_class The css class for the map div.
			 *
			 * @uses Soundmap_Template_Hooks->the_map( $css_class ) - 10
			 */
			$soundmap_css_class = '';
			do_action( 'soundmap_map_single', $soundmap_css_class );
			?>

			<?php
			/**
			 * Hook: soundmap_marker_content.
			 *
			 * @uses Soundmap_Template_Hooks->marker_content() - 20
			 */
			do_action( 'soundmap_marker_content' );
			?>

		<?php
		/**
		 * Hook: soundmap_marker_footer.
		 *
		 * @uses Soundmap_Template_Hooks->marker_footer()
		 */
		do_action( 'soundmap_marker_footer' );
		?>

	<?php
	/**
	 * Hook: soundmap_marker_wrapper_end.
	 *
	 * @uses Soundmap_Template_Hooks->marker_wrapper_end()
	 */
	do_action( 'soundmap_marker_wrapper_end' );
