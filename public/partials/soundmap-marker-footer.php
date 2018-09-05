<?php
/**
 * The marker entry footer html.
 *
 * @package Soundmap/public/partials
 *
 * @TODO replace template tags with proper conditional hooks.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<footer class="entry-footer">

	<?php
	if ( is_soundmap_archive() || is_soundmap_taxonomy() ) {

		soundmap_the_audio_file( $post->ID );
		soundmap_the_recording_datetime( $post->ID );
		soundmap_the_marker_author_url( $post->ID );

	} elseif ( is_soundmap_marker() ) {

		soundmap_the_latitude( $post->ID );
		soundmap_the_longitude( $post->ID );
		soundmap_the_address( $post->ID );
		soundmap_the_audio_file( $post->ID );
		soundmap_the_audio_info( $post->ID );
		soundmap_the_recording_datetime( $post->ID );
		soundmap_the_marker_author_url( $post->ID );

	}
	?>

</footer><!-- .entry-footer -->
