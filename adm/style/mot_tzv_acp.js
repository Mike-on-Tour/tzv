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
	* Check the 'mot_tzv_enable' setting and hide or show the according settings
	*/
	$("input[name='mot_tzv_enable']").click(function() {
		// Check radio buttons
		if ($(this).attr('type') == 'radio') {
			if ($(this).val() == 1) {
				$("#acp_mot_tzv_member_success").show();
				$("#acp_mot_tzv_member_error").hide();
				$("#acp_mot_tzv_admin_enable").show();
				if ($("input[name='tzv_admin']:checked").val() == 1) {
					$("#acp_mot_tzv_member_success").hide();
					$("#acp_mot_tzv_admin_error").show();
				}
			} else {
				$("#acp_mot_tzv_member_success").hide();
				$("#acp_mot_tzv_member_error").show();
				$("#acp_mot_tzv_admin_enable").hide();
				if ($("input[name='tzv_admin']:checked").val() == 1) {
					$("#acp_mot_tzv_admin_error").hide();
				}
			}
		}
		// Check checkbox
		if ($(this).attr('type') == 'checkbox') {
			if ($(this).is(":checked")) {
				$("#acp_mot_tzv_member_success").show();
				$("#acp_mot_tzv_member_error").hide();
				$("#acp_mot_tzv_admin_enable").show();
				if ($("input[name='tzv_admin']:checked").val() == 1) {
					$("#acp_mot_tzv_member_success").hide();
					$("#acp_mot_tzv_admin_error").show();
				}
			} else {
				$("#acp_mot_tzv_member_success").hide();
				$("#acp_mot_tzv_member_error").show();
				$("#acp_mot_tzv_admin_enable").hide();
				if ($("input[name='tzv_admin']:checked").val() == 1) {
					$("#acp_mot_tzv_admin_error").hide();
				}
			}
		}
	});

	// Show this div at the start if it is checked
	if ($("input[name='mot_tzv_enable']:checked").val() == 1) {
		if ($("input[name='tzv_admin']:checked").val() != 1) {
			$("#acp_mot_tzv_member_success").show();
		}
		else {
			$("#acp_mot_tzv_admin_error").show();
		}
		$("#acp_mot_tzv_admin_enable").show();
	}
	else {
		$("#acp_mot_tzv_member_error").show();
	}

	/*
	* Check the 'tzv_admin' setting and hide or show the according settings
	*/
	$("input[name='tzv_admin']").click(function() {
		// Check radio buttons
		if ($(this).attr('type') == 'radio') {
			if ($(this).val() == 1) {
				$("#acp_mot_tzv_admin_error").show();
				$("#acp_mot_tzv_member_success").hide();
			} else {
				$("#acp_mot_tzv_admin_error").hide();
				$("#acp_mot_tzv_member_success").show();
			}
		}
		// Check checkbox
		if ($(this).attr('type') == 'checkbox') {
			if ($(this).is(":checked")) {
				$("#acp_mot_tzv_admin_error").show();
				$("#acp_mot_tzv_member_success").hide();
			} else {
				$("#acp_mot_tzv_admin_error").hide();
				$("#acp_mot_tzv_member_success").show();
			}
		}
	});

	// Show this div at the start if it is checked
	if ($("input[name='tzv_admin']:checked").val() == 1 && $("input[name='tzv_enable']:checked").val() == 1) {
		$("#acp_mot_tzv_admin_error").show();
		$("#acp_mot_tzv_member_success").hide();
	}

	/*
	* Check the 'tzv_maps_enable' setting and hide or show the according settings
	*/
	$("input[name='tzv_maps_enable']").click(function() {
		// Check radio buttons
		if ($(this).attr('type') == 'radio') {
			if ($(this).val() == 1) {
				$("#mot_tzv_detail_maps").show();
				$("#mot_tzv_show_detail_maps").show();
				$("#mot_tzv_no_detail_maps").hide();
			} else {
				$("#mot_tzv_detail_maps").hide();
				$("#mot_tzv_show_detail_maps").hide();
				$("#mot_tzv_no_detail_maps").show();
			}
		}
		// Check checkbox
		if ($(this).attr('type') == 'checkbox') {
			if ($(this).is(":checked")) {
				$("#mot_tzv_detail_maps").show();
				$("#mot_tzv_show_detail_maps").show();
				$("#mot_tzv_no_detail_maps").hide();
			} else {
				$("#mot_tzv_detail_maps").hide();
				$("#mot_tzv_show_detail_maps").hide();
				$("#mot_tzv_no_detail_maps").show();
			}
		}
	});

	// Show this div at the start if it is checked
	if ($("input[name='tzv_maps_enable']:checked").val() == 1) {
		$("#mot_tzv_detail_maps").show();
		$("#mot_tzv_show_detail_maps").show();
		$("#mot_tzv_no_detail_maps").hide();
	}
	else {
		$("#mot_tzv_detail_maps").hide();
		$("#mot_tzv_show_detail_maps").hide();
		$("#mot_tzv_no_detail_maps").show();
	}

	/*
	* Check the 'mot_tzv_support_input' setting and hide or show the according setting
	*/
	$("input[name='tzv_support_enable']").click(function() {
		// Check radio buttons
		if ($(this).attr('type') == 'radio') {
			if ($(this).val() == 1) {
				$("#tzv_support_input").show();
			} else {
				$("#tzv_support_input").hide();
			}
		}
		// Check checkbox
		if ($(this).attr('type') == 'checkbox') {
			if ($(this).is(":checked")) {
				$("#tzv_support_input").show();
			} else {
				$("#tzv_support_input").hide();
			}
		}
	});

	// Show this div at the start if it is checked
	if ($("input[name='tzv_support_enable']:checked").val() == 1) {
		$("#tzv_support_input").show();
	}

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
