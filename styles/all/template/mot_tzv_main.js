/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

(function($) {  // Avoid conflicts with other libraries

'use strict';

/*
* Select the tab as active and the corresponding content box after a tab was selected
*
* @params	string		index		the descriptor of the selected tab
*
* @return	none
*/
motTzv.selectTab = function(index) {
	let elementId = "";

	// Hide all boxes
	$("div.inner").each(function() {
		elementId = $(this).attr('id');
		if ((typeof elementId !== 'undefined') && (elementId.substr(0,12) == 'mot_tzv_box_')) {
			$(this).attr("hidden", true);
		}
	});

	// Set all tabs to inactive
	$("li.tab").each(function() {
		elementId = $(this).attr('id');
		if ((typeof elementId !== 'undefined') && (elementId.substr(0,12) == 'mot_tzv_tab_')) {
			$(this).attr("class", 'tab');
		}
	});

	// Set selected tab to active
	$("#mot_tzv_tab_" + index).attr("class", 'tab activetab');

	// Show selected box
	$("#mot_tzv_box_" + index).attr("hidden", false);
}

motTzv.selectTab(motTzv.tab);

})(jQuery); // Avoid conflicts with other libraries
