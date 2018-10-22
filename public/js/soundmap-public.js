/**
 * Map functions for the frontend archive template.
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
	 * Render a marker popup.
	 *
	 * @param {Array} feature [description]
	 * @param {String} layer   [description]
	 * @return {Array} [description]
	 */
	function popUp( feature, layer ) {

		let out = [];
		if ( feature.properties ) {

			let key = '';
			for ( key in feature.properties ) {
				if ( 'title' == key ) {
					out.push(
						'<h3 class="info-title">'
						+ '<a href="' + feature.properties['url'] + '" rel="bookmark">'
						+ feature.properties['title']
						+ '</a>'
						+ '</h3>'
					);
				} else if ( 'content' == key ) {
					out.push( '<p class="info-desc">' + feature.properties[key] + '</p>' );
				}
			}
			layer.bindPopup( out.join( '' ) );
		}
	}


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
			initialView,
			mapPublic,
			allMarkers,
			allMarkersGroup,
			soundMarkers,
			soundMarkersGroup,
			placeMarkers,
			placeMarkersGroup,
			markerCluster,
			groupControl;

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

		if ( $( '#map-archive' ).length ) {

			/**
			 * Setup a base map with controls
			 *
			 * @type object
			 */
			mapPublic = L.map( 'map-archive', {
				center: initialView,
				zoom: 11,
				scrollWheelZoom: false,
				fullscreenControl: true,
				fullscreenControlOptions: {
					position: 'topleft'
				},
				layers: [
					OSMBlackAndWhite
				]
			});

			// Creating the markers with clustering and subgroups
			markerCluster = L.markerClusterGroup({
				showCoverageOnHover: false,
				singleMarkerMode: true, // if true, single marker icon is a single size cluster
				iconCreateFunction: function(cluster) {
					let childCount = cluster.getChildCount();

					let c = ' marker-cluster-';
					if (childCount == 1) {
						c += 'single';
						childCount = '';
					} else if (childCount < 10) {
						c += 'small';
					} else if (childCount < 100) {
						c += 'medium';
					} else {
						c += 'large';
					}
					return new L.DivIcon({ html: '<div><span>' + childCount + '</span></div>', className: 'marker-cluster' + c, iconSize: new L.Point(40, 40) });
				},
				maxClusterRadius: 80 // decreasing will make more, smaller clusters
			}),
			groupControl   = L.control.layers( null, null, { collapsed: false } );

			/**
			 * Setup GeoJSON layers.
			 *
			 * Loop through all Sound Map registered content types, and add a marker cluster layer
			 * with related controls for each content type. The layers are built with GeoJson responses
			 * from all REST Sound Map routes.
			 */
			Object.keys( Soundmap.rest_urls_content ).forEach(

				/**
				* Add a GeoJSON layer for each content type.
				*
				* @param {string} type Sound Map registered content type.
				*/
				function( type ) {

					let typeGroup, typeJSON, typeName;

					typeName = type.charAt(0).toUpperCase()
							 + type.slice(1).replace( /_([a-z])/g, function( g ) {
						return ' ' + g[1].toUpperCase();
					});
					typeGroup = type.replace( /_([a-z])/g, function( g ) {
						return g[1].toUpperCase();
					});

					typeName  = typeName + 's';
					typeGroup = typeGroup + 'Group';
					typeJSON  = typeGroup + 'GeoJson';

					// Set a cluster subgroup for each content type.
					typeGroup = L.featureGroup.subGroup( markerCluster );
					// Set a control checkbox for this group.
					groupControl.addOverlay( typeGroup, typeName );
					// Add a new geojson layer from each REST url.
					typeJSON = new L.GeoJSON.AJAX( Soundmap.rest_urls_content[type], {
						onEachFeature:popUp
					});
					// Add the cluster data after the geojson layer has loaded.
					typeJSON.on( 'data:loaded', function() {
						typeGroup.addLayer( typeJSON );
						mapPublic.addLayer( typeGroup );
					});
				}
			);
			groupControl.addTo( mapPublic );

			/**
			 * Add main cluster to the map.
			 */
			markerCluster.addTo( mapPublic );

			/**
			 * Add all tile layers to the map.
			 */
			L.control.layers( baseLayers ).addTo( mapPublic );

			/**
			 * Always fit bounds within each layer group
			 *
			 * It fires when a cluster layer becomes active or inactive.
			 */
			/*
			mapPublic.on( 'overlayadd overlayremove', function() {
				let bounds = markerCluster.getBounds();
				if ( bounds.isValid() ) {
					mapPublic.fitBounds( bounds );
				}
			});
			*/

			/**
			 * Zoom to full and switch to
			 * satellite view on single marker click.
			 *
			 * @param {[type]} event click to single marker
			 */
			/*
			markerCluster.on( 'click', function( event ) {
				let clickedMarker = event.layer;
				let latLngs = [ clickedMarker.getLatLng() ];
				let markerBounds = L.latLngBounds(latLngs);
				mapPublic.addLayer( EsriWorldImagery ).removeLayer( OSMBlackAndWhite );
				mapPublic.fitBounds( markerBounds );
			});
			*/

			/**
			 * Switch between satellite and black&white
			 * depending on zoom level
			 *
			 * @return {[type]} [description]
			 */
			mapPublic.on( 'zoomend', function() {
			  if ( mapPublic.getZoom() < 18 && mapPublic.hasLayer( EsriWorldImagery ) ) {
				mapPublic.addLayer( OSMBlackAndWhite ).removeLayer( EsriWorldImagery );
			  }
			});

			/**
			 * Disable zoom when scrolling on embedded Map
			 */
			mapPublic.once( 'focus', function() {
				mapPublic.scrollWheelZoom.enable();
			});

			// Load Google autocomplete if an API key is set
			if ( typeof google_key !== 'undefined' ) {

				/**
				* Add Google Maps API autocomplete features
				*/
				new L.Control.GPlaceAutocomplete( {
					callback: function( place ) {
						var _loc = place.geometry.location;
						mapPublic.setView( [_loc.lat(), _loc.lng()], mapPublic.zoom );
					}
				}).addTo( mapPublic );

			} else {

				// use OSM if no Google API key provided
				let osmGeocoder = new L.Control.OSMGeocoder();
				mapPublic.addControl( osmGeocoder );

			}

		}

	});

})( jQuery );
