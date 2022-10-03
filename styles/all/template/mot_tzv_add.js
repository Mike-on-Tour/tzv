/**
*
* @package phpBB Extension [Tour destinations]
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
$("#mot_tzv_maps_lat").blur(function() {
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
$("#mot_tzv_maps_lon").blur(function() {
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

/*
* Check whether all requested input elements have a value and if not, go back to the input
*/
$("#mot_tzv_add_form").submit(function() {
	// Check the name input field
	if ($("#mot_tzv_name").val() == '') {
		phpbb.alert(motTzv.jsError, motTzv.jsErrorName);
		$("#mot_tzv_name").focus();
		return (false);
	}

	// Check the postalcode input field
	if ($("#mot_tzv_postalcode").val() == '') {
		phpbb.alert(motTzv.jsError, motTzv.jsErrorPostalcode);
		$("#mot_tzv_postalcode").focus();
		return (false);
	}

	// Check the city input field
	if ($("#mot_tzv_city").val() == '') {
		phpbb.alert(motTzv.jsError, motTzv.jsErrorCity);
		$("#mot_tzv_city").focus();
		return (false);
	}

	// Check for coordinates only if map is enabled
	if (motTzv.jsCoordMandatory) {
		// Check the latidute input field
		var latValue = $("#mot_tzv_maps_lat").val();
		if (latValue == '' || latValue == '0') {
			phpbb.alert(motTzv.jsError, motTzv.jsErrorLat);
			$("#mot_tzv_maps_lat").focus();
			return (false);
		}

		// Check the longitude input field
		var lonValue = $("#mot_tzv_maps_lon").val();
		if (lonValue == '' || lonValue == '0') {
			phpbb.alert(motTzv.jsError, motTzv.jsErrorLon);
			$("#mot_tzv_maps_lon").focus();
			return (false);
		}
	}

	// Check the message input field
	if ($("#mot_tzv_message").val() == '') {
		phpbb.alert(motTzv.jsError, motTzv.jsErrorMessage);
		$("#mot_tzv_message").focus();
		return (false);
	}

});

/*
* Give the focus to the name input after loading
*/
$("#mot_tzv_name").focus();

})(jQuery); // Avoid conflicts with other libraries
