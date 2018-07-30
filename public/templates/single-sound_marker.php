<?php
/**
 * The template for displaying all single sound markers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package    Sound Map
 */
get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php
					if ( is_singular() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					endif;
					if ( 'sound_marker' === get_post_type() ) :
						?>
						<div class="entry-meta custom">
							<?php
								$terms_list = wp_get_object_terms( get_the_ID(), 'sound_marker_category' );
								if ( $terms_list ) {
									/* translators: 1: list of categories. */
									printf( '<p class="cat-links">' . esc_html__( 'Posted in %1$s', 'soundmap' ) . '</p>', $terms_list ); // WPCS: XSS OK.
								}
								/* translators: used between list items, there is a space after the comma */
								$tags_list = wp_get_object_terms( get_the_ID(), 'sound_marker_tag' );
								if ( $tags_list ) {
									/* translators: 1: list of tags. */
									printf( '<p class="tags-links">' . esc_html__( 'Tagged %1$s', 'soundmap' ) . '</p>', $tags_list ); // WPCS: XSS OK.
								}
							?>
						</div><!-- .entry-meta -->
					<?php endif; ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php
					the_content( sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'soundmap' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					) );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php
					echo '<p>lat: ' . get_post_meta( get_the_ID(), 'sound_marker_lat', true ) . '</p>';
					echo '<p>lng: ' . get_post_meta( get_the_ID(), 'sound_marker_lng', true ) . '</p>';
					echo '<p>addr: ' . get_post_meta( get_the_ID(), 'sound_marker_addr', true ) . '</p>';
					echo 'file: <audio controls>
							  <source src="' . get_post_meta( get_the_ID(), 'sound_marker_audio_file', true ) . '" type="audio/mpeg">
							Your browser does not support the audio element.
							</audio>';
					echo '<p>date: ' . get_post_meta( get_the_ID(), 'sound_marker_rec_date', true ) . '</p>';
					echo '<p>time: ' . get_post_meta( get_the_ID(), 'sound_marker_rec_time', true ) . '</p>';
					echo '<p>author URL: ' . get_post_meta( get_the_ID(), 'sound_marker_author_url', true ) . '</p>';
					echo '<p>author email: ' . get_post_meta( get_the_ID(), 'sound_marker_author_email', true ) . '</p>';

					?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-<?php the_ID(); ?> -->

		<?php the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
if ( locate_template( 'sidebar.php') != '') {
	get_sidebar();
}
get_footer();
