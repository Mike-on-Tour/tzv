/**
*
* package phpBB Extension [Adressverwaltung - Tourziele]
* copyright (c) 2022 Mike-on-Tour
* license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

(function($) {  // Avoid conflicts with other libraries

'use strict';

/*
*	Adds a new marker to the map
*
*	@params	array		item		one of the arrays within the array holding all the tour destinations
*
*	@return	none
*/
motTzv.addMarker = function(item) {
	var markerOptions = {
		title:		item['name'],
		clickable:	true,
		draggable:	false,
	}
	var marker = new L.Marker([parseFloat(item['maps_lat']), parseFloat(item['maps_lon'])], markerOptions);

	marker.url = item['url'];
	marker.on('click', function(){
		var newTab = window.open(this.url, '_blank');
	});

	marker.addTo(motTzv.markerLayer);
}

/*
*	Adds markers to map overlays named after the used item categories
*
*	@params	array		item		one of the arrays within the array holding all the items
*
*	@return	none
*/
motTzv.addLayers = function(item) {
	// Create the marker
	var markerOptions = {
		title:		item['name'],
		clickable:	true,
		draggable:	false,
	}
	var marker = new L.Marker([parseFloat(item['maps_lat']), parseFloat(item['maps_lon'])], markerOptions);

	marker.url = item['url'];
	marker.on('click', function(){
		var newTab = window.open(this.url, '_blank');
	});

	// Check whether an overlay with this name already exists and if not, create it and add it to the map
	if (item['cat_name'] in motTzv.userOverlays === false) {
		motTzv.userOverlays[item['cat_name']] = (motTzv.jsMapConfig['Cluster'] == 1) ? new L.markerClusterGroup(motTzv.groupOptions) : new L.layerGroup(motTzv.groupOptions);
		motTzv.userOverlays[item['cat_name']].addTo(motTzv.map);
	}
	// Add the marker to the correct overlay
	marker.addTo(motTzv.userOverlays[item['cat_name']]);
}

/* ---------------------------------------------------------------------------------------	main functions	---------------------------------------------------------------------------------------  */

motTzv.mapOptions = {
	center: [motTzv.jsMapConfig['Lat'], motTzv.jsMapConfig['Lon']],
	zoom: motTzv.jsMapConfig['Zoom'],
	attributionControl: false,
	scrollWheelZoom: false,
	tab: false,	// To prevent errors on Mac with Safari
}

motTzv.map = new L.map('mot_tzv_map', motTzv.mapOptions);

motTzv.map.on('click', function() {
	if (motTzv.map.scrollWheelZoom.enabled()) {
		motTzv.map.scrollWheelZoom.disable();
	}
	else {
		motTzv.map.scrollWheelZoom.enable();
	}
});

motTzv.streetLayer = new L.TileLayer('https://\{s\}.tile.openstreetmap.org/\{z\}/\{x\}/\{y\}.png');	// International map colors
//	motTzv.streetLayer = new L.TileLayer('https://\{s\}.tile.openstreetmap.de/\{z\}/\{x\}/\{y\}.png');	// German map colors
motTzv.topoLayer = new L.TileLayer('https://\{s\}.tile.opentopomap.org/\{z\}/\{x\}/\{y\}.png');	// Topo map
motTzv.satLayer = new L.TileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/\{z\}/\{y\}/\{x\}');

motTzv.map.addLayer(motTzv.streetLayer);

motTzv.attribution = new L.control.attribution().addAttribution('Map Data &copy; <a href="https://www.openstreetmap.org/copyright" target=_blank">OpenStreetMap</a>').addTo(motTzv.map);

motTzv.scale = new L.control.scale().addTo(motTzv.map);

// Define the basic map layers
motTzv.baseMap = {
	[motTzv.jsStreetDesc]	: motTzv.streetLayer,
	[motTzv.jsTopoDesc]		: motTzv.topoLayer,
	[motTzv.jsSatDesc]		: motTzv.satLayer,
}
// Define the additional map layers
motTzv.userOverlays = {};

if (motTzv.jsMapConfig['MultipleLayers'] == 1) {
	motTzv.groupOptions = {
		tab: false,	// To prevent errors on Mac with Safari
	}
	motTzv.jsTourziele.forEach(motTzv.addLayers);
} else {
	motTzv.markerLayer = (motTzv.jsMapConfig['Cluster'] == 1) ? new L.markerClusterGroup() : new L.layerGroup();
	motTzv.markerLayer.addTo(motTzv.map);
	motTzv.jsTourziele.forEach(motTzv.addMarker);
}

// Add the layer control to the map
motTzv.layerControl = new L.control.layers(motTzv.baseMap, motTzv.userOverlays).addTo(motTzv.map);

// Add the search element to the map
L.Control.geocoder().addTo(motTzv.map);

// Call the create function at right click into the map with the coordinates the mouse points to at this moment
motTzv.map.addEventListener('contextmenu', function(evt) {
	window.location.replace(motTzv.jsAjaxCreate + '?lat=' + evt.latlng.lat + '&lng=' + evt.latlng.lng);
});

})(jQuery); // Avoid conflicts with other libraries
