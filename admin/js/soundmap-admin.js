(function( $ ) {
	'use strict';

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
		    OSMHot,
		    OSMBlackAndWhite,
		    OSMMapnik,
		    HyddaFull,
		    StamenToner,
		    StamenTonerLite,
		    EsriWorldStreetMap,
		    EsriWorldImagery;

		/**
		 * Add all layers URL fragments
		 */
		OpenTopoMap = L.tileLayer( 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
			maxZoom: 21,
			attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
		}),
		OSMHot = L.tileLayer( 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles courtesy of <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>'
		}),
		OSMBlackAndWhite = L.tileLayer( 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}),
		OSMMapnik = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}),
		HyddaFull = L.tileLayer( 'https://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png', {
			maxZoom: 21,
			attribution: 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}),
		StamenToner = L.tileLayer( 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.{ext}', {
			attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
			subdomains: 'abcd',
			minZoom: 0,
			maxZoom: 20,
			ext: 'png'
		}),
		StamenTonerLite = L.tileLayer( 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}.{ext}', {
			attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
			subdomains: 'abcd',
			minZoom: 0,
			maxZoom: 20,
			ext: 'png'
		}),
		EsriWorldStreetMap = L.tileLayer( 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
			attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
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
		var baseLayers = {
			'Open Topo Map': OpenTopoMap,
			'OpenStreetMap Hot': OSMHot,
			'OpenStreetMap BlackAndWhite': OSMBlackAndWhite,
			'OpenStreetMap Mapnik': OSMMapnik,
			'Hydda Full': HyddaFull,
			'Stamen Toner': StamenToner,
			'Stamen Toner Lite': StamenTonerLite,
			'EsriWorld Street Map': EsriWorldStreetMap,
			'EsriWorld Imagery': EsriWorldImagery
		};

		if ( $( '#map-settings' ).length ) {

			/**
			 * Roma coordinates
			 *
			 * @type {Array}
			 */
			var roma = [
				41.891781,
				12.505855
			];

			/**
			 * Setup settings page map with controls
			 *
			 * @type object
			 */
			var map_settings = L.map( 'map-settings', {
				center: [41.8869599, 12.3854333],
				zoom: 11,
				scrollWheelZoom: false,
				layers: [
					OSMBlackAndWhite
				]
			});


			var singleMarker = L.marker( roma ).addTo( map_settings );

			singleMarker.bindPopup( 'I am a standalone popup in Roma.' ).openPopup();

			/**
			 * Disable zoom when scrolling on embedded Map
			 */
			map_settings.once( 'focus', function() {
				map_settings.scrollWheelZoom.enable();
			});

			/**
			 * Add all referenced layers to the map
			 */
			L.control.layers( baseLayers ).addTo( map_settings );

		} else if ( $( '#map-marker' ).length ) {

			/**
			 * Firenze coordinates
			 *
			 * @type {Array}
			 */
			var firenze = [
				43.84538,
				10.671609
			];

			/**
			 * Setup sound marker edit page map with controls
			 *
			 * @type object
			 */
			var map_marker = L.map( 'map-marker', {
				center: [41.9040036, 12.4715662],
				zoom: 11,
				scrollWheelZoom: false,
				layers: [
					OSMMapnik
				]
			});

			var firenzeMarker = L.marker( firenze ).addTo( map_marker );

			firenzeMarker.bindPopup( 'I am a standalone popup in Firenze.' ).openPopup();

			/**
			 * Disable zoom when scrolling on embedded Map
			 */
			map_marker.on( 'mouseover', function() {
				window.setTimeout( map_marker.scrollWheelZoom.enable
						.bind (map_marker.scrollWheelZoom ),
					2000
				);
			});
			map_marker.on( 'mouseout',
				map_marker.scrollWheelZoom.disable
					.bind( map_marker.scrollWheelZoom
				)
			);

			// map_marker.once( 'focus', function() {
			// 	map_marker.scrollWheelZoom.enable();
			// });

			/**
			 * Add all referenced layers to the map
			 */
			L.control.layers( baseLayers ).addTo( map_marker );

		}

	});

})( jQuery );
