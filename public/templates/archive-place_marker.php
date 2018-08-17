<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    Sound Map
 * @package    Soundmap/public/templates
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
			?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php
							the_title( '<h2 class="entry-title custom"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
						 ?>
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
						echo 'Archive template for the places: <br>';
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
							echo '<p>Author URL: <span>' . $author_url_list . '</span></p>';
						}
						?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-<?php the_ID(); ?> -->
			<?php
			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
