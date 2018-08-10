<?php
/**
 * The template for displaying all single place markers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package    Sound Map
 * @subpackage Soundmap/public/templates
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
					if ( 'place_marker' === get_post_type() ) :
						?>
						<div class="entry-meta custom">
							<?php
								// Get the terms related to sound_marker.
								$term_items = get_the_terms( $post->ID, 'place_marker_category' );

								if ( ! empty( $term_items ) ) {
									$term_list = '';
									foreach ( $term_items as $term ) {
										$term_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
											esc_url( get_term_link( $term->slug, 'place_marker_category' ) ),
											esc_html( $term->name )
										);
									}
									$term_list = rtrim( $term_list, ', ' );
									/* translators: 1: list of sound_marker categories. */
									printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'soundmap' ) . '</span>', $term_list );
									echo '</p>';
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
					echo '<br><h6>lat: ' . get_post_meta( get_the_ID(), 'place_marker_lat', true ) . '</h6>';
					echo '<h6>lng: ' . get_post_meta( get_the_ID(), 'place_marker_lng', true ) . '</h6>';
					echo '<h6>addr: ' . get_post_meta( get_the_ID(), 'place_marker_addr', true ) . '</h6>';
					$author_urls = get_post_meta( get_the_ID(), 'place_marker_author_url', false );
					if ( ! empty( $author_urls ) ) {
						$author_url_list = '';
						foreach ( $author_urls[0] as $author_url ) {
							$author_url_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
								esc_url( $author_url ),
								esc_html( $author_url )
							);
						}
						$author_url_list = rtrim( $author_url_list, ', ' );
						echo '<h6>author URL: <span>' . $author_url_list . '</span></h6>';
					}
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
