<?php
/**
 * The html for the marker entry content.
 *
 * @package Soundmap/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
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
