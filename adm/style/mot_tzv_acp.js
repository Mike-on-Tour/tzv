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
* Checks the value of the latitude input element with a regular expression to make certain we get the value we want
*
* @return:	writes either the default value or - if it matches the pattern and is within the boundaries - the given value into the DOM element's value
*/
$("#mot_tzv_map_lat").blur(function() {
	var elementValue = $(this).val();
	elementValue = elementValue.replace(/[,]/g, ".");	// replace a comma with a fullstop in case some European hit the key on the num pad
	var result = elementValue.match(/-?\d{1,2}\.*\d*/);	// is this like (-)dd.d(ddd)?
	if ((result == null) || (result[0] < -90.0) || (result[0] > 90.0)) {
		elementValue = 0;
	} else {
		elementValue = result[0];
	}
	$(this).val(elementValue);
});

/*
* Checks the value of the longitude input element with a regular expression to make certain we get the value we want
*
* @return:	writes either the default value or - if it matches the pattern and is within the boundaries - the given value into the DOM element's value
*/
$("#mot_tzv_map_lon").blur(function() {
	var elementValue = $(this).val();
	elementValue = elementValue.replace(/[,]/g, ".");	// replace a comma with a fullstop in case some European hit the key on the num pad
	var result = elementValue.match(/-?\d{1,3}\.*\d*/);	// is this like (-)ddd.d(ddd)?
	if ((result == null) || (result[0] < -180.0) || (result[0] > 180.0)) {
		elementValue = 0;
	} else {
		elementValue = result[0];
	}
	$(this).val(elementValue);
});

})(jQuery); // Avoid conflicts with other libraries
