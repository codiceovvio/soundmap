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

							<?php soundmap_the_marker_taxonomies( $post->ID ); ?>

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
					<?php soundmap_the_latitude( $post->ID ); ?>
					<?php soundmap_the_longitude( $post->ID ); ?>
					<?php soundmap_the_address( $post->ID ); ?>

					<?php soundmap_the_audio_file( $post->ID ); ?>
					<?php soundmap_the_audio_info( $post->ID ); ?>
					<?php soundmap_the_recording_datetime( $post->ID ); ?>

					<?php $author_urls = get_post_meta( get_the_ID(), 'sound_marker_author_url', false );
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
