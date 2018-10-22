<?php
/**
 * The REST API functionality of the plugin.
 *
 * @link    https://github.com/codiceovvio/soundmap
 * @since   0.5.0
 *
 * @package Soundmap/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The REST API functionality of the plugin.
 *
 * Defines the plugin name, version, and registers all the needed REST routes,
 * as well as adding custom endpoints and populate them with proper data.
 *
 * @TODO Register REST endpoints for taxonomies and single by ID.
 * @since   0.5.0
 *
 * @package Soundmap/includes
 * @author  Codice Ovvio codiceovvio at gmail dot com
 */
class Soundmap_Rest_Routes {

	/**
	 * The ID of this plugin.
	 *
	 * @since  0.5.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  0.5.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.5.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name = null, $version = null ) {

		$this->plugin_name   = $plugin_name;
		$this->version       = $version;

	}

	/**
	 * Admin warning if REST API not available.
	 *
	 * @since 0.5.0
	 */
	public function missing_rest_api_warning() {

		global $wp_version;

		// If WordPress version is >= 4.7 we have REST support in core.
		if ( $wp_version >= 4.7 ) {

			return;

		// If WordPress version is < 4.7 we need WP-API plugin installed.
		} elseif ( is_plugin_active( 'WP-API-develop/plugin.php' )
			|| is_plugin_active( 'rest-api/plugin.php' )
			|| is_plugin_active( 'WP-API/plugin.php' ) ) {

				return;

		// If WP is < 4.7 and WP-API plugin is not installed, print a warning.
		} else { ?>

			<div class="update-nag notice">
				<p>
					<?php __( 'To use <strong>Sound Map</strong> plugin, you need to update to the latest version of WordPress (version 4.7 or above). To use <strong>Sound Map</strong> plugin with an older version of WordPress, you can install the <a href="https://wordpress.org/plugins/rest-api/">WP API Plugin</a> plugin. However, we&apos;d strongly advise you to update WordPress.', 'soundmap' ); ?>
				</p>
			</div>
		<?php
		}
	}

	/**
	 * API Route Constructor.
	 *
	 * Register all routes needed by Sound Map:
	 *   - query results for all markers ids and coordinates
	 *     ../wp-json/soundmap/v1/markers-id
	 *
	 *   - query results for a subset of markers ids and coordinates, filtered by
	 *     CONTENT_TYPE (where CONTENT_TYPE is a registered Sound Map content type).
	 *     ../wp-json/soundmap/v1/markers-id?filter=CONTENT_TYPE
	 *
	 *   - query results for all markers content
	 *     ../wp-json/soundmap/v1/markers-content
	 *
	 *   - query results for a subset of markers content, filtered by CONTENT_TYPE
	 *     ../wp-json/soundmap/v1/markers-content?filter=CONTENT_TYPE
	 *
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/#arguments Using a filter for marker type extra parameter.
	 *
	 * @since 0.5.0
	 */
	public function register_routes() {

		/**
		 * Rest route for all or a subset of markers ids.
		 *
		 * Register query results for all or a subset of markers ids and coordinates.
		 * We can filter a markers subset appending a filter argument with a Sound Map
		 * registered content type slug, e.g.:
		 *   ../wp-json/soundmap/v1/markers-id?filter=sound_marker
		 *
		 * In js map functions we can get the generated json via xhr request.
		 */
		register_rest_route( '/soundmap/v1', '/markers-id', array(
			'methods'  => WP_REST_Server::READABLE,
			// The callback is fired when this endpoint is matched by the WP_REST_Server class.
			'callback' => array( $this, 'get_all_markers_id' ),
			// The filter callback is fired before the main callback.
			'args' => $this->filter_marker_type(),
		) );

		/**
		 * Rest route for all or a subset of markers content.
		 *
		 * Register query results for all or a subset of markers title, content, etc.
		 * We can filter a markers subset appending a filter argument with a Sound Map
		 * registered content type slug, e.g.:
		 *   ../wp-json/soundmap/v1/markers-content?filter=sound_marker
		 *
		 * In js map functions we can get the generated json via xhr request.
		 */
		register_rest_route( '/soundmap/v1', '/markers-content', array(
			'methods'  => WP_REST_Server::READABLE,
			// The callback is fired when this endpoint is matched by the WP_REST_Server class.
			'callback' => array( $this, 'get_all_markers_content' ),
			// The filter callback is fired before the main callback.
			'args' => $this->filter_marker_type(),
		) );

	}

	/**
	 * Query all markers.
	 *
	 * @since 0.5.0
	 * @param array|string $marker_type One or all the registered soundmap content type.
	 * @return array List of markers.
	 */
	public function query_all_markers( $marker_type = null ) {

		global $post;

		$args = array(
			'posts_per_page' => -1,
			'offset'         => 0,
			'post_type'      => $marker_type,
			'post_status'    => 'publish',
		);

		$query_results = get_posts( $args );

		if ( empty( $query_results ) ) {
			return false;
		}
		return $query_results;
	}

	/**
	 * Query all markers ids and coordinates.
	 *
	 * @since 0.5.0
	 * @param array|string $marker_type One or all the registered soundmap content type.
	 * @return object|false Array with markers ids and coordinates, false on empty query.
	 */
	public function query_all_markers_id( $marker_type ) {

		if ( is_array( $marker_type ) ) {
			// Check if this query is available in a transient.
			$feature_collection = get_transient( 'soundmap_all_markers_id' );
		} else {
			// Check if this query is available in a transient.
			$feature_collection = get_transient( 'soundmap_all_' . $marker_type . 's_id' );
		}

		if ( false === $feature_collection ) {

			$query_results = $this->query_all_markers( $marker_type );

			if ( false === $query_results ) {
				return false;
			}

			// Set an initial empty object.
			$feature_collection = new stdclass();
			$feature_collection->type = 'FeatureCollection';
			$feature_collection->features = [];

			foreach ( $query_results as $marker ) {

				// If it is a revision, skip to the next marker.
				if ( wp_is_post_revision( $marker->ID ) ) {
					return;
				}
				// If no position is set, skip to the next marker.
				if ( false === soundmap_get_latitude( $marker->ID ) || false === soundmap_get_longitude( $marker->ID ) ) {
					return;
				}

				$marker_lat  = soundmap_get_latitude( $marker->ID );
				$marker_lng  = soundmap_get_longitude( $marker->ID );
				$marker_alt  = 0;
				$type        = get_post_type( $marker->ID );

				$feature                        = (object) [];
				$feature->type                  = 'Feature';
				$feature->geometry              = (object) [];
				$feature->geometry->type        = 'Point';
				$feature->geometry->coordinates = [
					(float) $marker_lng,
					(float) $marker_lat,
					(float) $marker_alt
				];
				$feature->properties            = (object) [];
				$feature->properties->id        = (int) $marker->ID;
				$feature->properties->type      = $type;
				$feature->properties->url       = esc_url( get_permalink( $marker->ID ) );

				$feature_collection->features[] = $feature;

			}
			if ( is_array( $marker_type ) ) {
				set_transient( 'soundmap_all_markers_id', $feature_collection, DAY_IN_SECONDS );
			} else {
				set_transient( 'soundmap_all_' . $marker_type . 's_id', $feature_collection, DAY_IN_SECONDS );
			}
		}
		return $feature_collection;

	}

	/**
	 * Query all markers content and fields.
	 *
	 * @since 0.5.0
	 * @param array|string $marker_type One or all the registered soundmap content type.
	 * @return object|false Array with markers ids and coordinates, false on empty query.
	 */
	public function query_all_markers_content( $marker_type ) {

		if ( is_array( $marker_type ) ) {
			// Check if this query is available in a transient.
			$feature_collection = get_transient( 'soundmap_all_markers_content' );
		} else {
			// Check if this query is available in a transient.
			$feature_collection = get_transient( 'soundmap_all_' . $marker_type . 's_content' );
		}

		if ( false === $feature_collection ) {

			$query_results = $this->query_all_markers( $marker_type );

			if ( empty( $query_results ) ) {
				return false;
			}

			// Set an initial empty object.
			$feature_collection = new stdclass();
			$feature_collection->type = 'FeatureCollection';
			$feature_collection->features = [];

			foreach ( $query_results as $marker ) {

				// If it is a revision, skip to the next marker.
				if ( wp_is_post_revision( $marker->ID ) ) {
					return;
				}
				// If no position is set, skip to the next marker.
				if ( false === soundmap_get_latitude( $marker->ID ) || false === soundmap_get_longitude( $marker->ID ) ) {
					return;
				}

				$marker_title   = sanitize_post_field( 'post_title', $marker->post_title, $marker->ID, 'js' );
				$marker_content = preg_replace( "/\r|\n/", '', wp_kses_post( $marker->post_content ) );
				$marker_tax     = get_the_terms( $marker->ID, 'sound_marker_category' );
				$marker_lat     = soundmap_get_latitude( $marker->ID );
				$marker_lng     = soundmap_get_longitude( $marker->ID );
				$marker_alt     = 0;
				$type           = get_post_type( $marker->ID );

				$feature                        = (object) [];
				$feature->type                  = 'Feature';
				$feature->geometry              = (object) [];
				$feature->geometry->type        = 'Point';
				$feature->geometry->coordinates = [
					(float) $marker_lng,
					(float) $marker_lat,
					(float) $marker_alt
				];
				$feature->properties            = (object) [];
				$feature->properties->id        = (int) $marker->ID;
				$feature->properties->title     = $marker_title;
				$feature->properties->content   = $marker_content;
				$feature->properties->tax       = $marker_tax;
				$feature->properties->type      = $type;
				$feature->properties->url       = esc_url( get_permalink( $marker->ID ) );

				$feature_collection->features[] = $feature;

			}
			if ( is_array( $marker_type ) ) {
				set_transient( 'soundmap_all_markers_content', $feature_collection, DAY_IN_SECONDS );
			} else {
				set_transient( 'soundmap_all_' . $marker_type . 's_content', $feature_collection, DAY_IN_SECONDS );
			}
		}
		return $feature_collection;

	}

	/**
	 * API Endpoint for markers id.
	 *
	 * Make a query for markers ids and coordinates, for all or a subset of a
	 * registered marker type, and build an array to pass to the map js file.
	 *
	 * @since 0.5.0
	 * @param string $request the type of rest route additional request.
	 * @return WP_REST_Response the requested query data wrapped into a rest response.
	 */
	public function get_all_markers_id( $request ) {

		// Get the currently registered marker types.
		$types = soundmap_get_content_types();

		if ( isset( $request['filter'] ) ) {
			$soundmap_filtered_markers_id = new stdClass();
			foreach ( $types as $marker_type ) {
				if ( $request['filter'] === $marker_type ) {
					$soundmap_filtered_markers_id = $this->query_all_markers_id( $marker_type );
				}
			}
			// Wrap the filtered markers data into a WP_REST_Response.
			return rest_ensure_response( $soundmap_filtered_markers_id );
		}
		// Check if we have markers.
		$soundmap_all_markers_id = $this->query_all_markers_id( $types );

		if ( false === $soundmap_all_markers_id ) {
			return;
		}
		// Wrap all markers data into a WP_REST_Response.
		return rest_ensure_response( $soundmap_all_markers_id );

	}

	/**
	 * API Endpoint for markers content.
	 *
	 * Make a query for markers content and fields, for all or a subset of a
	 * registered marker type, and build an array to pass to the map js file.
	 *
	 * @since 0.5.0
	 * @param string $request the type of rest route additional request.
	 * @return WP_REST_Response the requested query data wrapped into a rest response.
	 */
	public function get_all_markers_content( $request ) {

		// Get the currently registered marker types.
		$types = soundmap_get_content_types();

		if ( isset( $request['filter'] ) ) {
			$soundmap_filtered_markers_content = new stdClass();
			foreach ( $types as $marker_type ) {
				if ( $request['filter'] === $marker_type ) {
					$soundmap_filtered_markers_content = $this->query_all_markers_content( $marker_type );
				}
			}
			// Wrap the filtered markers data into a WP_REST_Response.
			return rest_ensure_response( $soundmap_filtered_markers_content );
		}
		// Check if we have markers.
		$soundmap_all_markers_content = $this->query_all_markers_content( $types );

		if ( false === $soundmap_all_markers_content ) {
			return;
		}
		// Wrap all markers data into a WP_REST_Response.
		return rest_ensure_response( $soundmap_all_markers_content );

	}

	/**
	 * Delete all markers transients.
	 *
	 * Fires when a new post or content type is saved or an
	 * existing post or content type is updated.
	 *
	 * @since 0.5.0
	 */
	public function delete_all_content_transients() {

		// Get the currently registered marker types.
		$content_types = soundmap_get_content_types();

		foreach( $content_types as $type ) {
			delete_transient( 'soundmap_all_' . $type . 's_id' );
			delete_transient( 'soundmap_all_' . $type . 's_content' );
		}
		delete_transient( 'soundmap_all_markers_id' );
		delete_transient( 'soundmap_all_markers_content' );
	}

	/**
	 * Validate a request argument based on details registered to the route.
	 *
	 * @since 0.5.0
	 * @param mixed            $value   Value of the 'filter' argument.
	 * @param WP_REST_Request  $request The current request object.
	 * @param string           $param   Key of the parameter. In this case it is 'filter'.
	 * @return WP_Error|boolean
	 */
	public function validate_marker_type_filter( $value, $request, $param ) {

		// If the 'filter' argument is not a string return an error.
		if ( ! is_string( $value ) ) {
			return new WP_Error(
				'soundmap_rest_invalid_param',
				esc_html__( 'The filter argument must be a content type registered by Soundmap loader class.', 'soundmap' ),
				array( 'status' => 400 )
			);
		}

		// Get the registered attributes for this endpoint request.
		$attributes = $request->get_attributes();

		// Grab the filter param schema.
		$args = $attributes['args'][ $param ];

		// If the filter param is not a value in our enum then we should return an error as well.
		if ( ! in_array( $value, $args['enum'], true ) ) {
			return new WP_Error(
				'soundmap_rest invalid_marker_type',
				sprintf(
					__( '%s is not a registered marker type, it should be %s' ),
					$param,
					implode( ', ', $args['enum'] )
				),
				array( 'status' => 400 )
			);
		}
	}

	/**
	 * Filter the marker displayed by content type.
	 *
	 * @since 0.5.0
	 * @return array the content type filter and callback.
	 */
	public function filter_marker_type() {

		$args = array();

		// Get the currently registered marker types.
		$content_types = soundmap_get_content_types();

		// Here we are registering the schema for the filter argument.
		$args['filter'] = array(
			// A human readable description of the argument.
			'description' => esc_html__( 'The filter parameter is used to filter the collection of markers by marker type', 'soundmap' ),
			// The type of data that the argument should be.
			'type'        => 'string',
			// What values filter can take on.
			'enum'        =>  $content_types,
			// Here we register the validation callback for the filter argument.
			'validate_callback' => array( $this, 'validate_marker_type_filter' ),
		);
		return $args;
	}

}
