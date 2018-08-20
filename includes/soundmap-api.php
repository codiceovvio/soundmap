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
	$audio_file_id   = get_post_meta( $marker_id, 'sound_marker_audio_file_id', true );
	$audio_file_path = get_attached_file( $audio_file_id );

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

	$audio_file_path = get_sound_marker_audio_file_path( $marker_id );

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
 * [the_sound_marker_audio_info description]
 * %s [description]
 *
 * @param int $marker_id [description]
 * @return void [description]
 */
function the_sound_marker_audio_info( int $marker_id = null ) {

	$audio_file_data = get_sound_marker_audio_info( $marker_id );

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
function the_sound_marker_tax( int $marker_id = null, string $tax_slug = '' ) {

	// Get the terms related to sound_marker.
	$term_items = get_the_terms( $marker_id, 'sound_marker_category' );
	$tags_items = get_the_terms( $marker_id, 'sound_marker_tag' );

	if ( ! empty( $term_items ) ) {
		$term_list = '';
		foreach ( $term_items as $term ) {
			$term_list .= sprintf( '<a href="%1$s">%2$s</a>, ',
				esc_url( get_term_link( $term->slug, 'sound_marker_category' ) ),
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
				esc_url( get_term_link( $tag->slug, 'sound_marker_tag' ) ),
				esc_html( $tag->name )
			);
		}
		$tags_list = rtrim( $tags_list, ', ' );
		/* translators: 1: list of sound_marker tags. */
		printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'soundmap' ) . '</span>', $tags_list );
	}

}

function is_it_a_date($str){
    $str = str_replace('/', '-', $str);
    $stamp = strtotime($str);
    if (is_numeric($stamp)){
       $month = date( 'm', $stamp );
       $day   = date( 'd', $stamp );
       $year  = date( 'Y', $stamp );
       return checkdate($month, $day, $year);
    }
    return false;
}

/**
 * [get_sound_marker_rec_datetime description]
 * %s [description]
 *
 * @param int|null $marker_id the sound marker object ID
 * @return [type] [description]
 */
function get_sound_marker_rec_datetime( int $marker_id = null ) {

	if ( ! $marker_id ) {
		return false;
	}
	// Get the marker date and time, if set.
	$date = get_post_meta( $marker_id, 'sound_marker_rec_date', true );
	$time = get_post_meta( $marker_id, 'sound_marker_rec_time', true );
	// Check and return it if is set and valid.
	if ( ! empty( $date ) && is_it_a_date( $date ) ) {

		if ( ! empty( $time ) ) {
			return $date . ' - ' . $time;
		}
		return $date;
	}
	return false;

}

function the_sound_marker_rec_datetime( int $marker_id = null ) {
	// exit if no marker address is set
	if ( false === get_sound_marker_rec_datetime( $marker_id ) ) {
		return;
	}
	// build the output html
	$output = sprintf( '<span class="marker-datetime">%1$s %2$s</span><br>',
		esc_html__( 'Recorded: ', 'soundmap' ),
		esc_html( get_sound_marker_rec_datetime( $marker_id ) )
	);
	// filter the html before output it
	$output = apply_filters( 'the_sound_marker_rec_datetime', $output );
	echo $output;
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
 * Prints the map html
 *
 * Prints the html used by leaflet to initialize a map in the frontend.
 *
 * @param string $css_id      the css ID for the map div
 * @param bool   $all_markers Whether to get all the markers
 * @param array  $options     An array of options that soundmap accepts
 * @return mixed The html with the map and the markers loaded
 */
function the_soundmap( $css_id = 'map-front', $all_markers = false, $options = array() ) {

	printf(
		'<div id="%1$s" class="soundmap-canvas">
		</div>',
		esc_attr( $css_id )
	);

	if ( $all_markers ) {
		// load all markers

	} else {
		// load some markers

	}

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
