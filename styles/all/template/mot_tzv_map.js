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
*	@params	array		item		one of the arrays within the array holding all the Tourziele
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

motTzv.addLayers = function(item) {
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

motTzv.scale = new L.control.scale({imperial: false}).addTo(motTzv.map);

motTzv.baseMap = {
	[motTzv.jsStreetDesc]	: motTzv.streetLayer,
	[motTzv.jsTopoDesc]		: motTzv.topoLayer,
	[motTzv.jsSatDesc]		: motTzv.satLayer,
}

motTzv.layerControl = new L.control.layers(motTzv.baseMap).addTo(motTzv.map);

// Add the search element to the map
L.Control.geocoder().addTo(motTzv.map);
motTzv.jsMultipleLayers = false;
if (motTzv.jsMultipleLayers) {
	alert(JSON.stringify(motTzv.jsTourziele));
	motTzv.jsTourziele.forEach(motTzv.addLayers);
} else {
	motTzv.markerLayer = (motTzv.jsMapConfig['Cluster'] == 1) ? new L.markerClusterGroup() : new L.layerGroup();
	motTzv.markerLayer.addTo(motTzv.map);
	motTzv.jsTourziele.forEach(motTzv.addMarker);
}

// Call the create function at right click into the map with the coordinates the mouse points to at this moment
motTzv.map.addEventListener('contextmenu', function(evt) {
	window.location.replace(motTzv.jsAjaxCreate + '?lat=' + evt.latlng.lat + '&lng=' + evt.latlng.lng);
});

})(jQuery); // Avoid conflicts with other libraries
