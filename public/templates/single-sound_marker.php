<?php
/**
 * The template for displaying all single sound markers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package    Sound Map
 * @package    Soundmap/public/templates
 */
get_header( 'sound-marker' ); ?>

	<?php
	/**
	* Hook: soundmap_page_wrapper_start.
	*
	* @uses Soundmap_Template_Hooks->page_wrapper_start()
	*/
	do_action( 'soundmap_page_wrapper_start' );
	?>

		<?php
		while ( have_posts() ) : the_post();

			soundmap_get_template_part( 'content', get_post_type() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	<?php
	/**
	* Hook: soundmap_page_wrapper_end.
	*
	* @uses Soundmap_Template_Hooks->page_wrapper_end()
	*/
	do_action( 'soundmap_page_wrapper_end' );
	?>

<?php
if ( locate_template( 'sidebar.php') != '') {
	get_sidebar();
}
get_footer();
