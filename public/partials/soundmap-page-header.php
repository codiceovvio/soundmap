<?php
/**
 * The html for the page header end.
 *
 * @package Soundmap/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<header class="page-header">

	<?php
	the_archive_title( '<h1 class="page-title">', '</h1>' );
	the_archive_description( '<div class="archive-description">', '</div>' );
	?>

</header><!-- .page-header -->
