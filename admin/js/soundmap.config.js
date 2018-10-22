/**
 * Map functions for the plugin settings screen.
 *
 * @file   This files defines the Settings Map edit functions.
 * @author Codice Ovvio.
 * @since  0.1.0
 */

(function( $ ) {
	'use strict';

	/**
	 * @TODO autocomplete with OSM: check those addresses
	 * https://derickrethans.nl/leaflet-and-nominatim.html
	 * https://photon.komoot.de/
	 * https://github.com/Twista/leaflet-google-places-autocomplete
	 *
	 * @TODO a better one is http://osmnames.org/api/
	 * @see http://osmnames.org
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
			mapDrag,
			mapZoom,
			mapSettings,
			initialView;

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
		initialView = [
			Soundmap.settings_lat,
			Soundmap.settings_lng
		];

		/**
		 * Return map center after drag event
		 *
		 * Get the center coordinates of the map after
		 * users end setting the map view
		 *
		 * @param {String} event the event listener to add
		 * @return {Array} coordinates in lat, lng array
		 */
		mapDrag = function( event ) {
			var _loc = mapSettings.getCenter();
			$( '[name=soundmap_settings_lat]').val( _loc.lat ).trigger( 'change' );
			$( '[name=soundmap_settings_lng]').val( _loc.lng ).trigger( 'change' );
		}

		/**
		 * Return map zoom after drag event
		 *
		 * Get the zoom value of the map after
		 * users end setting the map zoom
		 *
		 * @param {String} event the event listener to add
		 * @return {Int} zoom value
		 */
		mapZoom = function( event ) {
			$( '#soundmap_settings_zoom' ).val( mapSettings.getZoom() );
		}

		if ( $( '#map-settings' ).length ) {

			/**
			 * Setup settings page map with controls
			 *
			 * @type object
			 */
			mapSettings = L.map( 'map-settings', {
				center: initialView,
				zoom: Soundmap.settings_zoom,
				scrollWheelZoom: false,
				layers: [
					OSMBlackAndWhite
				]
			});
			var map_center = mapSettings.getCenter();

			/**
			 * Disable zoom when scrolling on embedded Map
			 */
			mapSettings.once( 'focus', function() {
				mapSettings.scrollWheelZoom.enable();
			});

			/**
			 * Add all referenced layers to the map
			 */
			L.control.layers( baseLayers ).addTo( mapSettings );

			mapSettings.addEventListener( 'moveend', mapDrag, this );
			mapSettings.addEventListener( 'baselayerchange', mapDrag, this );
			mapSettings.addEventListener( 'zoomend', mapZoom, this );

			// Load Google autocomplete if an API key is set
			if ( typeof google_key !== 'undefined' ) {

				/**
				* Add Google Maps API autocomplete features
				*/
				new L.Control.GPlaceAutocomplete( {
					callback: function( place ) {
						var _loc = place.geometry.location;
						mapSettings.setView( [_loc.lat(), _loc.lng()], mapSettings.zoom );
						$( '#soundmap_settings_lat').val( _loc.lat );
						$( '#soundmap_settings_lng').val( _loc.lng );
					}
				}).addTo( mapSettings );

			} else {

				// use OSM if no Google API key provided
				var osmGeocoder = new L.Control.OSMGeocoder();
				mapSettings.addControl( osmGeocoder );

			}

		}

	});

})( jQuery );
