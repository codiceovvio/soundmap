<header class="marker entry-header">

	<?php
	if ( is_singular() ) :
		the_title( '<h1 class="marker entry-title">', '</h1>' );
	else :
		the_title( '<h2 class="marker entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	endif;
	?>

	<div class="marker entry-meta">
		<?php soundmap_the_marker_taxonomies( $post->ID ); ?>
	</div><!-- .entry-meta -->

</header><!-- .entry-header -->
