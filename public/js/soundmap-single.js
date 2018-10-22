/**
 * Map functions for the frontend single template.
 *
 * @file   This files defines the frontend Map functions.
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

		// Toggle Info sections - debug only
		// REMOVE WHEN FIXED
		$( ".toggle" ).click( function() {
			$( this ).next().slideToggle();
		});
		$( ".toggle" ).next().hide();

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
			singleView,
			mapPublic,
			singleIcon,
			singleMarker;

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
		singleView = [
			Soundmap.marker_lat,
			Soundmap.marker_lng
		];

		if ( $( '#map-single' ).length ) {

			console.log(Soundmap);

			/**
			 * Setup settings page map with controls
			 *
			 * @type object
			 */
			mapPublic = L.map( 'map-single', {
				center: singleView,
				zoom: 18,
				scrollWheelZoom: false,
				layers: [
					EsriWorldImagery
				]
			});

			var singleIcon = L.divIcon( {
			  className: 'marker-cluster marker-cluster-single',
			  iconAnchor:[13,36],
			  html: '<div></div>'
			});
			var singleMarker = L.marker(
			  singleView, {
			    icon: singleIcon
			  })
			  .addTo( mapPublic );

			/**
			 * Disable zoom when scrolling on embedded Map
			 */
			mapPublic.once( 'focus', function() {
				mapPublic.scrollWheelZoom.enable();
			});

			/**
			 * Add all referenced layers to the map
			 */
			L.control.layers( baseLayers ).addTo( mapPublic );

		}

	});

})( jQuery );
