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
 * Get a template part.
 *
 * @since    0.3.1
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template (default: '').
 */
function soundmap_get_template_part( $slug, $name = null ) {

	$template_engine = new Soundmap_Templates();
	return $template_engine->get_template_part( $slug, $name );

}

/**
 * Get all the registered content types.
 *
 * @since 0.3.3
 *
 * @return array The registered content types slugs.
 */
function soundmap_get_content_types() {

	$sm_content_type = new Soundmap_Content_Type( 'soundmap', SOUNDMAP_VERSION );

	$content_type = $sm_content_type->get_registered_types();
	if ( empty( $content_type ) ) {
		return;
	}
	return $content_type;
}

/**
 * Get a marker audio file url.
 *
 * Get the url for the audio file attached to a sound marker.
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_audio_file_url( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	$type = get_post_type( $marker_id );
	// get the marker audio file url, if set
	$audio_file_url = get_post_meta( $marker_id, $type . '_audio_file', true );
	// check if the file url is set and valid
	if ( ! empty( $audio_file_url ) && wp_http_validate_url( $audio_file_url ) ) {
		return esc_url( $audio_file_url );
	}
	return false;

}

/**
 * Get a marker audio file path.
 *
 * Get the absolute file path for the audio file attached
 * to a sound marker. Validate the file name and path before return.
 *
 * @param int|null $marker_id the sound marker object ID
 * @return string|false [description]
 */
function soundmap_get_audio_file_path( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return;
	}
	// exit if no marker audio file is attached
	if ( false === soundmap_get_audio_file_url( $marker_id ) ) {
		return;
	}
	$type = get_post_type( $marker_id );
	$audio_file_id   = get_post_meta( $marker_id, $type . '_audio_file_id', true );
	$audio_file_path = get_attached_file( $audio_file_id );

	// filter the default file path
	$audio_file_path = apply_filters( 'soundmap_get_audio_file_path', $audio_file_path );
	// check if the file path is set and valid
	if ( ! empty( $audio_file_path ) && 0 == validate_file( $audio_file_path ) ) {
		return $audio_file_path;
	}
	return false;

}

/**
 * [soundmap_the_audio_file description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_the_audio_file( int $marker_id = null ) {

	$audio_file_url = soundmap_get_audio_file_url( $marker_id );
	// exit if no marker audio file is attached
	if ( false === $audio_file_url ) {
		return;
	}
	// build the output html
	$output = sprintf(
		'<audio class="%3$s" controls="controls" preload="metadata">'
		. "\t" . '<source src="%1$s" type="audio/mpeg">'
		. "\t" . '%2$s'
		. '</audio>',
		esc_url( $audio_file_url ),
		__( 'Your browser does not support the audio element.', 'soundmap' ),
		esc_attr( is_singular() ? 'marker-player' : 'archive-player' )
	);
	// filter the html before output it
	$output = apply_filters( 'soundmap_the_audio_file', $output );
	echo $output;

}

/**
 * [soundmap_get_audio_info description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_audio_info( int $marker_id = null ) {

	$audio_file_path = soundmap_get_audio_file_path( $marker_id );

	// Exit if audio file path is invalid.
	if ( false === $audio_file_path ) {
		return;
	}
	// Require getID3 class.
	if ( ! class_exists( 'getID3' ) ) {
		require( ABSPATH . WPINC . '/ID3/getid3.php' );
	}
	$getID3 = new getID3();
	$audio_file_data = $getID3->analyze( $audio_file_path );

	return $audio_file_data;


}

/**
 * [soundmap_the_audio_info description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return void [description]
 */
function soundmap_the_audio_info( int $marker_id = null ) {

	$audio_file_data = soundmap_get_audio_info( $marker_id );

	echo '<p>';
	echo 'Audio is an '
	. $audio_file_data['fileformat']
	. ' file of '
	. round( ( ( $audio_file_data['filesize'] / 1000 ) / 1000 ), 2 )
	. ' megabytes, with playback time of '
	. $audio_file_data['playtime_string']
	. '</p>';
	echo '<br><h6>file Info (via getID3):</h6><br>';
	//stack_debug( $audio_file_data, false );
	echo '<br>';

}


/**
 * Get the sound marker latitude
 *
 * @param int|null $marker_id the sound marker object ID
 * @return string|false the sound marker latitude
 */
function soundmap_get_latitude( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	$type = get_post_type( $marker_id );
	// get the marker latitude, if set
	$lat = get_post_meta( $marker_id, $type . '_lat', true );
	// check and return it if is set and valid
	if ( ! empty( $lat ) && is_numeric( $lat ) ) {
		return $lat;
	}
	return false;

}

/**
 * Get the sound marker longitude
 *
 * @param int|null $marker_id the sound marker object ID
 * @return string|false the sound marker longitude if set, else false
 */
function soundmap_get_longitude( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	$type = get_post_type( $marker_id );
	// get the marker longitude, if set
	$lng = get_post_meta( $marker_id, $type . '_lng', true );
	// check and return it if is set and valid
	if ( ! empty( $lng ) && is_numeric( $lng ) ) {
		return $lng;
	}
	return false;

}

/**
 * Output the sound marker latitude
 *
 * @param int|null $marker_id the sound marker object ID
 * @return void
 */
function soundmap_the_latitude( int $marker_id = null ) {

	// exit if no marker latitude is set
	if ( false === soundmap_get_latitude( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-lat">%1$s %2$s</span><br>',
		esc_html__( 'Lat:', 'soundmap' ),
		esc_html( soundmap_get_latitude( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'soundmap_the_latitude', $output );
	echo $output;

}

/**
 * Output the sound marker longitude
 *
 * @param int|null $marker_id the sound marker object ID
 * @return void
 */
function soundmap_the_longitude( int $marker_id = null ) {

	// exit if no marker longitude is set
	if ( false === soundmap_get_longitude( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-lng">%1$s %2$s</span><br>',
		esc_html__( 'Lng:', 'soundmap' ),
		esc_html( soundmap_get_longitude( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'soundmap_the_longitude', $output );
	echo $output;

}

/**
 * Get the sound marker address
 *
 * @param int|null $marker_id the sound marker object ID
 * @return string|false the sound marker address if set, else false
 */
function soundmap_get_address( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	$type = get_post_type( $marker_id );
	// get the marker address, if set
	$addr = get_post_meta( $marker_id, $type . '_addr', true );
	// check and return it if set
	if ( ! empty( $addr ) ) {
		return $addr;
	}
	return false;

}

/**
 * Output the sound marker address
 *
 * @param int|null $marker_id the sound marker object ID
 * @return void
 */
function soundmap_the_address( int $marker_id = null ) {

	// exit if no marker address is set
	if ( false === soundmap_get_address( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-address">%1$s %2$s</span><br>',
		esc_html__( 'Address:', 'soundmap' ),
		esc_html( soundmap_get_address( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'soundmap_the_address', $output );
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
function soundmap_the_marker_taxonomies( int $marker_id = null, string $tax_slug = '' ) {

	$type = get_post_type( $marker_id );

	// Get the terms related to sound_marker.
	$term_items = get_the_terms( $marker_id, $type . '_category' );
	$tags_items = get_the_terms( $marker_id, $type . '_tag' );

	if ( ! empty( $term_items ) ) {
		$term_list = '';
		foreach ( $term_items as $term ) {
			$term_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
				esc_url( get_term_link( $term->slug, $type . '_category' ) ),
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
				esc_url( get_term_link( $tag->slug, $type . '_tag' ) ),
				esc_html( $tag->name )
			);
		}
		$tags_list = rtrim( $tags_list, ', ' );
		/* translators: 1: list of sound_marker tags. */
		printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'soundmap' ) . '</span>', $tags_list );
	}

}

/**
 * Check if a date has a valid format
 *
 * @param string $date A date in one of the format defined in
 *                     http://php.net/manual/it/function.date.php
 * @return bool true if the data is valid, false if not.
 */
function soundmap_is_valid_date( $date ) {

	$date = str_replace( '/', '-', $date );

	$timestamp = strtotime( $date );
	if ( is_numeric( $timestamp ) ) {

		$month = date( 'm', $timestamp );
		$day   = date( 'd', $timestamp );
		$year  = date( 'Y', $timestamp );

		return checkdate( $month, $day, $year );
	}

	return false;
}

/**
 * [soundmap_get_recording_datetime description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_recording_date( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	$type = get_post_type( $marker_id );
	// Get the marker date and time, if set.
	$date = get_post_meta( $marker_id, $type . '_rec_date', true );
	// Check and return it if is set and valid.
	if ( empty( $date ) && ! soundmap_is_valid_date( $date ) ) {
		return;
	}
	return $date;

}
/**
 * [soundmap_get_recording_datetime description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_recording_time( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	$type = get_post_type( $marker_id );
	// Get the marker date and time, if set.
	$time = get_post_meta( $marker_id, $type . '_rec_time', true );
	// Check and return it if is set and valid.
	if ( empty( $time ) ) {
		return;
	}
	return $time;

}

/**
 * Prints the recording date and time.
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_the_recording_datetime( int $marker_id = null ) {

	// Exit if no marker date is set.
	if ( false === soundmap_get_recording_date( $marker_id ) ) {
		return;
	}
	// Get the marker date and time, if set.
	$date = soundmap_get_recording_date( $marker_id );
	$time = soundmap_get_recording_time( $marker_id );
	$output = '';
	// Check and return it if is set and valid.
	if ( ! empty( $date ) && soundmap_is_valid_date( $date ) ) {

		if ( ! empty( $time ) ) {
			// Build the output html.
			$output = sprintf( '<p class="marker-datetime">%1$s<span class="date">%2$s</span> %4$s <span class="time">%3$s</span></p>',
				esc_html__( 'Recorded: ', 'soundmap' ),
				esc_html( $date ),
				esc_html( $time ),
				/* translators: "at" joins date and time strings */
				esc_html__( 'at', 'soundmap' )
			);
		} else {
			// Build the output html.
			$output = sprintf( '<p class="marker-datetime">%1$s<span class="date">%2$s</span></p>',
				esc_html__( 'Recorded: ', 'soundmap' ),
				esc_html( $date )
			);
		}
	}
	// Filter the html before output it.
	$output = apply_filters( 'soundmap_the_recording_datetime', $output );

	echo $output;

}

/**
 * [soundmap_get_marker_author description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_marker_author( int $marker_id = null ) {
	// code here
}

/**
 * [soundmap_get_marker_author description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_the_marker_author_url( int $marker_id = null ) {

	$type = get_post_type( $marker_id );

	$author_urls = get_post_meta( get_the_ID(), $type . '_author_url', false );

	if ( ! empty( $author_urls ) ) {
		$author_url_list = '';
		foreach ( $author_urls[0] as $author_url ) {
			$author_url_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
				esc_url( $author_url ),
				esc_html( $author_url )
			);
		}
		$author_url_list = rtrim( $author_url_list, ', ' );
		echo '<p class="soundmap-author">Author URL: <span>' . $author_url_list . '</span></p>';
	}
}

/**
 * [soundmap_get_all_markers description]
 * %s [description]
 *
 * @return [type] [description]
 */
function soundmap_get_all_markers() {
	// code here
}

/**
 * [soundmap_get_single_marker description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_single_marker( int $marker_id = null ) {
	// code here
}

/**
 * [get_soundmap_popup_window description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_get_marker_popup( int $marker_id = null ) {
	// code here
}

/**
 * [get_soundmap_popup_window description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function soundmap_the_marker_popup( int $marker_id = null ) {
	// code here
}

/**
 * [soundmap_the_map_rss_feed description]
 * %s [description]
 *
 * @return [type] [description]
 */
function soundmap_the_map_rss_feed() {
	// code here
}
