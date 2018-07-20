(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

$( window ).load(function() {

	var OpenTopoMap,

		/**
		 * Add all layers URL fragments
		 */
		OpenTopoMap = L.tileLayer( 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
		    maxZoom: 21,
		    attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
		}),
		OpenStreetMapHot = L.tileLayer( 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
		    maxZoom: 19,
		    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles courtesy of <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>'
		}),
		OpenStreetMapBlackAndWhite = L.tileLayer( 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
		    maxZoom: 18,
		    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}),
		OpenStreetMapMapnik = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
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
		    'OpenStreetMap Hot': OpenStreetMapHot,
		    'OpenStreetMap BlackAndWhite': OpenStreetMapBlackAndWhite,
		    'OpenStreetMap Mapnik': OpenStreetMapMapnik,
		    'Hydda Full': HyddaFull,
		    'Stamen Toner': StamenToner,
		    'Stamen Toner Lite': StamenTonerLite,
		    'EsriWorld Street Map': EsriWorldStreetMap,
		    'EsriWorld Imagery': EsriWorldImagery
		};

		/**
		 * Setup some markers coordinates
		 * to test clustering
		 *
		 * @type {Array}
		 */
		var locations = [
		    [ 41.891781, 12.515855 ],
		    [ 41.791781, 12.535845 ],
		    [ 41.873781, 12.525835 ],
		    [ 41.765931, 12.555825 ],
		    [ 41.895781, 12.575815 ],
		    [ 41.894281, 12.585875 ],
		    [ 41.893751, 12.535885 ],
		    [ 41.895531, 12.555895 ],
		    [ 41.896737, 12.535955 ],
		    [ 41.897783, 12.546835 ],
		    [ 41.898731, 12.507875 ],
		    [ 41.899381, 12.498845 ],
		    [ 41.856781, 12.489865 ],
		    [ 41.832781, 12.503855 ],
		    [ 41.796781, 12.502825 ],
		    [ 41.794781, 12.515885 ],
		    [ 41.887781, 12.715875 ],
		    [ 41.889781, 12.615835 ],
		    [ 41.844781, 12.515845 ],
		    [ 41.857781, 12.647865 ],
		    [ 41.843781, 12.425855 ],
		    [ 41.867781, 12.335885 ],
		    [ 41.837781, 12.345825 ]
		];

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
		 * Setup a base map with controls
		 *
		 * @type object
		 */
		var map = L.map( 'map-settings', {
		    center: [41.9040036, 12.4715662],
		    zoom: 11,
		    layers: [
		        OpenStreetMapBlackAndWhite
		    ]
		});

		var singleMarker = L.marker( roma ).addTo( map );

		singleMarker.bindPopup( 'I am a standalone popup in Roma.' ).openPopup();

		/**
		 * Add all referenced layers to the map
		 */
		L.control.layers( baseLayers ).addTo( map );

	});

})( jQuery );
