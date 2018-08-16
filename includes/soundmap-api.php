<?php
/**
 * Global functions & template tags
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.3.1
 *
 * @package    Sound Map
 * @package    Soundmap/includes
 */

/**
 * Returns a template file to load.
 *
 * @since    0.3.0
 * @param string $name the template file name to get,
 *                              e.g single-sound_marker, taxonomy-sound_marker_tag,
 *                              etc, without extension
 * @return string the template file to load
 */
function soundmap_get_template( $name ) {

	return Soundmap_Templates::template_loader( $name );

}

/**
 * Get a template part.
 *
 * @since    0.3.1
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template (default: '').
 */
function soundmap_get_template_part( $slug, $name = '' ) {

	return Soundmap_Templates::get_template_part( $slug, $name );

}

/**
 * [get_sound_marker_audio_file description]
 * %s [description]
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_sound_marker_audio_file_url( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	// get the marker audio file url, if set
	$audio_file_url = get_post_meta( $marker_id, 'sound_marker_audio_file', true );
	// check if the file url is set and valid
	if ( ! empty( $audio_file_url ) && wp_http_validate_url( $audio_file_url ) ) {
		return esc_url( $audio_file_url );
	}
	return false;

}

/**
 * [get_sound_marker_audio_file description]
 * %s [description]
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return string|false [description]
 */
function get_sound_marker_audio_file_path( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return;
	}
	// exit if no marker audio file is attached
	if ( false === get_sound_marker_audio_file_url( $marker_id ) ) {
		return;
	}
	$uploads = wp_upload_dir();
	$uploads_path = ( $uploads['path'] . '/' );
	$audio_file_path = $uploads_path . basename( get_sound_marker_audio_file_url( $marker_id ) );

	// filter the default file path
	$audio_file_path = apply_filters( 'get_sound_marker_audio_file_path', $audio_file_path );
	// check if the file path is set and valid
	if ( ! empty( $audio_file_path ) && 0 == validate_file( $audio_file_path ) ) {
		return $audio_file_path;
	}
	return false;

}

/**
 * [the_sound_marker_audio_file description]
 * %s [description]
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return [type] [description]
 */
function the_sound_marker_audio_file( int $marker_id = null ) {

	$audio_file_url = get_sound_marker_audio_file_url( $marker_id );
	// exit if no marker audio file is attached
	if ( false === $audio_file_url ) {
		return;
	}
	// build the output html
	$output = sprintf(
		'<audio class="single-player" controls="controls" preload="metadata">'
		. "\t" . '<source src="%1$s" type="audio/mpeg">'
		. "\t" . '%2$s'
		. '</audio>',
		esc_url( $audio_file_url ),
		__( 'Your browser does not support the audio element.', 'soundmap' )
	);
	// filter the html before output it
	$output = apply_filters( 'the_sound_marker_audio_file', $output );
	echo $output;

}

/**
 * [get_sound_marker_audio_info description]
 * %s [description]
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_sound_marker_audio_info( int $marker_id = null ) {

	// exit if audio file path is invalid
	if ( false === get_sound_marker_audio_file_path( $marker_id ) ) {
		return;
	}
}

/**
 * Get the sound marker latitude
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return string|false the sound marker latitude
 */
function get_sound_marker_lat( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	// get the marker latitude, if set
	$lat = get_post_meta( $marker_id, 'sound_marker_lat', true );
	// check and return it if is set and valid
	if ( ! empty( $lat ) && is_numeric( $lat ) ) {
		return $lat;
	}
	return false;

}

/**
 * Get the sound marker longitude
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return string|false the sound marker longitude if set, else false
 */
function get_sound_marker_lng( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	// get the marker longitude, if set
	$lng = get_post_meta( $marker_id, 'sound_marker_lng', true );
	// check and return it if is set and valid
	if ( ! empty( $lng ) && is_numeric( $lng ) ) {
		return $lng;
	}
	return false;

}

/**
 * Output the sound marker latitude
 *
 * @param  int|null $marker_id the sound marker object ID
 * @return void
 */
function the_sound_marker_lat( int $marker_id = null ) {

	// exit if no marker latitude is set
	if ( false === get_sound_marker_lat( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-lat">%1$s %2$s</span><br>',
		esc_html__( 'Lat:', 'soundmap' ),
		esc_html( get_sound_marker_lat( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'the_sound_marker_lat', $output );
	echo $output;

}

/**
 * Output the sound marker longitude
 *
 * @param  int|null $marker_id the sound marker object ID
 * @return void
 */
function the_sound_marker_lng( int $marker_id = null ) {

	// exit if no marker longitude is set
	if ( false === get_sound_marker_lng( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-lng">%1$s %2$s</span><br>',
		esc_html__( 'Lng:', 'soundmap' ),
		esc_html( get_sound_marker_lng( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'the_sound_marker_lng', $output );
	echo $output;

}

/**
 * Get the sound marker address
 *
 * @param  int|null     $marker_id the sound marker object ID
 * @return string|false the sound marker address if set, else false
 */
function get_sound_marker_addr( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	// get the marker address, if set
	$addr = get_post_meta( $marker_id, 'sound_marker_addr', true );
	// check and return it if set
	if ( ! empty( $addr ) ) {
		return $addr;
	}
	return false;

}

/**
 * Output the sound marker address
 *
 * @param  int|null $marker_id the sound marker object ID
 * @return void
 */
function the_sound_marker_addr( int $marker_id = null ) {

	// exit if no marker address is set
	if ( false === get_sound_marker_addr( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-address">%1$s %2$s</span><br>',
		esc_html__( 'Address:', 'soundmap' ),
		esc_html( get_sound_marker_addr( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'the_sound_marker_addr', $output );
	echo $output;

}

/**
 * [get_sound_marker_tax description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @param string $tax_slug [description]
 * @return [type] [description]
 */
function get_sound_marker_tax( int $marker_id = null, string $tax_slug = '' ) {
	// code here
}

/**
 * [get_sound_marker_rec_datetime description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_sound_marker_rec_datetime( int $marker_id = null ) {
	// code here
}

/**
 * [get_sound_marker_author description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_sound_marker_author( int $marker_id = null ) {
	// code here
}

/**
 * [get_all_sound_markers description]
 * %s [description]
 *
 * @return [type] [description]
 */
function get_all_sound_markers() {
	// code here
}

/**
 * [get_single_sound_marker description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_single_sound_marker( int $marker_id = null ) {
	// code here
}

/**
 * [the_soundmap description]
 * %s [description]
 *
 * @return [type] [description]
 */
function the_soundmap() {
	// code here
}

/**
 * [get_soundmap_popup_window description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_sound_marker_popup( int $marker_id = null ) {
	// code here
}

/**
 * [get_soundmap_popup_window description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function the_sound_marker_popup( int $marker_id = null ) {
	// code here
}

/**
 * [the_soundmap_rss_feed description]
 * %s [description]
 *
 * @return [type] [description]
 */
function the_soundmap_rss_feed() {
	// code here
}
