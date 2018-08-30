<?php
/**
 * The template for displaying all single place markers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package    Sound Map
 * @package    Soundmap/public/templates
 */
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
	?>
