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

	<?php do_action( 'soundmap_page_wrapper_start' ); ?>

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
								// Get the terms related to sound_marker.
								$term_items = get_the_terms( $post->ID, 'sound_marker_category' );
								$tags_items = get_the_terms( $post->ID, 'sound_marker_tag' );

								if ( ! empty( $term_items ) ) {
									$term_list = '';
									foreach ( $term_items as $term ) {
										$term_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
											esc_url( get_term_link( $term->slug, 'sound_marker_category' ) ),
											esc_html( $term->name )
										);
									}
									$term_list = rtrim( $term_list, ', ' );
									/* translators: 1: list of sound_marker categories. */
									printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'soundmap' ) . '</span>', $term_list );
								}
								if ( ! empty( $tags_items ) ) {
									$tags_list = '';
									foreach ( $tags_items as $tag ) {
										$tags_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
											esc_url( get_term_link( $tag->slug, 'sound_marker_tag' ) ),
											esc_html( $tag->name )
										);
									}
									$tags_list = rtrim( $tags_list, ', ' );
									/* translators: 1: list of sound_marker tags. */
									printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'soundmap' ) . '</span>', $tags_list );
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
					<?php the_sound_marker_lat( $post->ID ); ?>
					<?php the_sound_marker_lng( $post->ID ); ?>
					<?php the_sound_marker_addr( $post->ID ); ?>

					<?php the_sound_marker_audio_file( $post->ID ); ?>

					<?php if ( ! class_exists( 'getID3' ) ) {
						require( ABSPATH . WPINC . '/ID3/getid3.php' );
					}
					$getID3 = new getID3();
					$audio_file_path = get_sound_marker_audio_file_path( $post->ID );
					$audio_file_data = $getID3->analyze( $audio_file_path );
					echo '<p>';
					echo 'Audio is an '
					. $audio_file_data['fileformat']
					. ' file of '
					. round( ( ( $audio_file_data['filesize'] / 1000 ) / 1000 ), 2 )
					. ' megabytes, with playback time of '
					. $audio_file_data['playtime_string']
					. '</p>';
					echo '<br><br><h6>file Info (via getID3):</h6><br>';
					//stack_debug( $audio_file_data, false );
					echo '<br><br>';




					echo '<h6>date: ' . get_post_meta( get_the_ID(), 'sound_marker_rec_date', true ) . '</h6>';
					echo '<h6>time: ' . get_post_meta( get_the_ID(), 'sound_marker_rec_time', true ) . '</h6>';
					$author_urls = get_post_meta( get_the_ID(), 'sound_marker_author_url', false );
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

	<?php do_action( 'soundmap_page_wrapper_end' ); ?>

<?php
if ( locate_template( 'sidebar.php') != '') {
	get_sidebar();
}
get_footer();
