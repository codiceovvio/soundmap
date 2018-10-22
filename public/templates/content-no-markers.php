<?php
/**
 * Template part for displaying a message that markers cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Soundmap/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<section class="no-results not-found">

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'soundmap' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_search() ) :
			?>

			<p><?php esc_html_e( 'Sorry, but no marker matched your search terms. Please try again with some different keywords.', 'soundmap' ); ?></p>
			<?php
			get_search_form();
		else :
			?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find the marker you&rsquo;re looking for. Perhaps searching can help.', 'soundmap' ); ?></p>
			<?php
			get_search_form();
		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
