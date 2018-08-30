(function( $ ) {
	'use strict';

	/**
	 * @TODO autocomplete with OSM: check those addresses
	 * https://derickrethans.nl/leaflet-and-nominatim.html
	 * https://photon.komoot.de/
	 * https://github.com/Twista/leaflet-google-places-autocomplete
	 */

	/**
	 * All functions here fire after DOM is ready
	 * -----------------------------------------------
	 */
	$( document ).ready( function() {

	});

	/**
	 * All functions here fire when on window load
	 * -----------------------------------------------
	 */
	$( window ).load( function() {

		var OpenTopoMap,
			OSMBlackAndWhite,
			OSMMapnik,
			EsriWorldImagery;

		var baseLayers,
			layerChange,
			markerDrag,
			mapClick,
			map_marker,
			initial_view,
			post_type,
			marker;


		// Init an undefined marker
		marker: undefined;

		// Get the current post type
		post_type = Soundmap.post_type;

		/**
		 * Add all layers URL fragments
		 */
		OpenTopoMap = L.tileLayer( 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
			maxZoom: 21,
			attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
		}),
		OSMBlackAndWhite = L.tileLayer( 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}),
		OSMMapnik = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}),
		EsriWorldImagery = L.tileLayer( 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
			attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
			maxZoom: 19
		});

		/**
		 * Setup layer controls (e.g. labels)
		 *
		 * @type {Object}
		 */
		baseLayers = {
			'Open Topo Map': OpenTopoMap,
			'OpenStreetMap BlackAndWhite': OSMBlackAndWhite,
			'OpenStreetMap Mapnik': OSMMapnik,
			'EsriWorld Imagery': EsriWorldImagery
		}

		/**
		 * Center coordinates
		 *
		 * @type {Array}
		 */
		initial_view = [
			Soundmap.settings_lat,
			Soundmap.settings_lng
		];

		/**
		 * Return marker coordinates after click event
		 *
		 * Get the lat, lng values of the marker after
		 * users setting its position with a click
		 *
		 * @param {String} event the event listener to add
		 * @return {Int} marker value
		 */
		mapClick = function( event ) {

			var _latlng = event.latlng;

			if ( marker == null ) {

				marker = L.marker(
					_latlng, {
						draggable: true
					}
				).addTo( map_marker );
				marker.addEventListener( 'dragend', this.markerDrag, this );

			} else {

				marker.setLatLng( _latlng );

			}
			$( '[name=' + post_type + '_lat]' ).val(_latlng.lat);
			$( '[name=' + post_type + '_lng]' ).val(_latlng.lng);
		}

		/**
		 * Return marker coord after drag event
		 *
		 * Get the coordinates of the marker after
		 * users end setting marker position
		 *
		 * @param {String} event the event listener to add
		 * @return {Array} coordinates in lat, lng array
		 */
		markerDrag = function( event ) {
			var _latlng = marker.getLatLng();
			$( '[name=' + post_type + '_lat]' ).val( _latlng.lat ).trigger( 'change' );
			$( '[name=' + post_type + '_lng]' ).val( _latlng.lng ).trigger( 'change' );
		}

		if ( $( '#map-marker' ).length ) {

				/**
			 * Setup sound marker edit page map with controls
			 *
			 * @type object
			 */
			map_marker = L.map( 'map-marker', {
				center: initial_view,
				zoom: Soundmap.settings_zoom,
				scrollWheelZoom: false,
				layers: [
					OSMMapnik
				]
			});
			var map_center = map_marker.getCenter();

			/**
			 * Disable zoom when scrolling on embedded Map
			 */
			map_marker.once( 'focus', function() {
				map_marker.scrollWheelZoom.enable();
			});

			/**
			 * Add all referenced layers to the map
			 */
			L.control.layers( baseLayers ).addTo( map_marker );

			map_marker.addEventListener('click', mapClick, this);

			if ( $( '[name=' + post_type + '_lat]' ).val() ) {
				var _latlng = [
					parseFloat( $( '[name=' + post_type + '_lat]' ).val() ),
					parseFloat( $( '[name=' + post_type + '_lng]' ).val() )];
				marker = L.marker(
					_latlng, {
						draggable: true
					}
				).addTo( map_marker );
				map_marker.panTo( _latlng );
				marker.addEventListener( 'dragend', markerDrag, this );
			}

			// Load Google autocomplete if an API key is set
			if ( typeof google_key !== 'undefined' ) {

				/**
				* Add Google Maps API autocomplete features
				*/
				new L.Control.GPlaceAutocomplete( {
					callback: function( place ) {
						var loc = place.geometry.location;
						map_marker.setView( [loc.lat(), loc.lng()], map_marker.zoom );
						$( '#' + post_type + '_lat').val( loc.lat );
						$( '#' + post_type + '_lng').val( loc.lng );
					}
				}).addTo( map_marker );

			} else {

				// use OSM if no Google API key provided
				var osmGeocoder = new L.Control.OSMGeocoder();
				map_marker.addControl( osmGeocoder );

			}

		}

	});

})( jQuery );
