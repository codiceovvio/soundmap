<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
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
						if ( is_singular() ) :
							the_title( '<h1 class="entry-title">', '</h1>' );
						else :
							the_title( '<h2 class="entry-title custom"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
						endif;
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
						echo 'get infos about the location: <br>';
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
							echo '<p>author URL: <span>' . $author_url_list . '</span></p>';
						}
						?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-<?php the_ID(); ?> -->
			<?php
			endwhile;
			the_posts_navigation();
		else : ?>

			<section class="no-results not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Nothing Found', '_s' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<?php
					if ( is_home() && current_user_can( 'publish_posts' ) ) :
						printf(
							'<p>' . wp_kses(
								/* translators: 1: link to WP admin new post page. */
								__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', '_s' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							) . '</p>',
							esc_url( admin_url( 'post-new.php' ) )
						);
					elseif ( is_search() ) :
						?>

						<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', '_s' ); ?></p>
						<?php
						get_search_form();
					else :
						?>

						<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', '_s' ); ?></p>
						<?php
						get_search_form();
					endif;
					?>
				</div><!-- .page-content -->
			</section><!-- .no-results -->

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
if ( locate_template( 'sidebar.php') != '') {
	get_sidebar();
}
get_footer();
