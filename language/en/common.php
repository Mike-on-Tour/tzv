<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* @language file [deutsch / Du]
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [

	// Titel
	'MOT_TZV_TOURZIEL'					=> 'Tour destinations',
	'MOT_TZV_TOURZIEL_MAIN'				=> 'Motorcycle &bull;&nbsp;Tour destinations database',

	// Forum Index
	'MOT_TZV_NEW_EVENT'					=> 'Latest/last edited Tour destination item',
	'MOT_TZV_NEW_FLAG'					=> 'Flag',
	'MOT_TZV_NEW_LAND'					=> 'Country',
	'MOT_TZV_NEW_REGION'				=> 'Region',
	'MOT_TZV_NEW_TIME'					=> 'Created on',
	'MOT_TZV_NEW_AUTHOR'				=> 'Created by',
	'MOT_TZV_NO_NEW_EVENT'				=> 'No item found.',
	'MOT_TZV_TOURZIEL_TOTAL'			=> '&bull;&nbsp;Total number of tour destinations',

	// [Buttons - Links / Titel Texte]
	'MOT_TZV_MAIN_INDEX'				=> 'Tour destination index',
	'MOT_TZV_MAIN_ADD'					=> 'New item',
	'MOT_TZV_MAIN_VIEW'					=> 'Tour destination list',
	'MOT_TZV_MAIN_VIEW_NEW'				=> 'Latest/last edited item',
	'MOT_TZV_MAIN_MAP'					=> 'Tour destination map',
	'MOT_TZV_MAIN_SEARCH'				=> 'Tour destination search',
	'MOT_TZV_MAIN_SUPPORT'				=> 'Tour destination support',
	'MOT_TZV_SUPPORT_FORUM'				=> 'Support forum',

	'MOT_TZV_RETURN_TOURZIEL'			=> 'Back to index',

	// PAGINATION
	'MOT_TZV_POSTS_COUNT'				=> [
		0	=> 'no items',
		1	=> '%d item',
		2	=> '%d items',
	],

	// [Detailansicht]
	'MOT_TZV_TOURZIEL_DETAIL'			=> 'Detailed view',
	'MOT_TZV_TOURZIEL_DETAIL_CLICK'		=> 'Click for detailed view',
	'MOT_TZV_TOURZIEL_USER_TIPP'		=> 'Item description',
	'MOT_TZV_TOURZIEL_PLZ_ORT'			=> 'Postal code / City',
	'MOT_TZV_TOURZIEL_STRASSE_NR'		=> 'Street address',
	'MOT_TZV_TOURZIEL_GPS_DAT'			=> 'GPS',
	'MOT_TZV_DATE_ADD_EDIT'				=> '<br><b>Created on</b>',
	'MOT_TZV_OPENSTREETMAP_INFO'		=> 'Click on <strong>View larger map</strong> underneath the map.<br><br>',
	'MOT_TZV_KURVIGER_INFO'				=> 'Hand GPS data over to motor cycle route planner https://kurviger.de',
	'MOT_TZV_KOMOOT_INFO'				=> 'Hand GPS data over to hiker and biker route planner https://www.komoot.de',
	'MOT_TZV_DATA_HANDOVER'				=> 'Data transfer',

	// [Moderator-Funktion]
	'MOT_TZV_TOURZIEL_MODERATE'			=> 'Tour destination moderator',
	'MOT_TZV_LAST_5_EVENTS'				=> '5 latest items',
	'MOT_TZV_NO_EVENTS'					=> 'No items found',

	// [Index]
	'MOT_TZV_COUNT_TOTAL_DEST'			=> [
		0	=> 'Currently <strong>no</strong> items are listed in the database.',
		1	=> 'Currently <strong>%1$d</strong> item is listed in the database.',
		2	=> 'Currently <strong>%1$d</strong> items are listed in the database.',
	],
	'MOT_TZV_COUNTRY_EINTRAG'			=> 'Registered countries',
	'MOT_TZV_MAINNEWS_INFO'				=> '&bull;&nbsp;&nbsp;Added an OSM search (Nominatim) to the map.<br>
											&bull;&nbsp;&nbsp;Added a data transfer to komoot.de to enable route planning for hikers and bikers (opens in a new tab or window).<br>
											&bull;&nbsp;&nbsp;komoot.de will be called with a localisation variable to set the users language if this language is supported (e.g. if user uses English komoot.de will open in English).<br>
											&bull;&nbsp;&nbsp;Added the ability to create a new item by right-clicking onto the desired map location and then be redirected to the input form with this location`s coordinates.<br>',
	'MOT_TZV_NUTZUNG_MAPS'				=> 'Google Maps Terms of Use',

	// Map
	'MOT_TZV_STREET_DESC'				=> 'Street map',
	'MOT_TZV_TOPO_DESC'					=> 'Topographical map',
	'MOT_TZV_SAT_DESC'					=> 'Satellite image',
	'MOT_TZV_MAP_LEGEND_TEXT'			=> 'Toggle mousewheel zoom by left-clicking on the map.<br>
											Start creating a new item by right-clicking on its map location.<br>
											<i>Click on a marker to show the detailed view in a new browser tab.</i>',
	'MOT_TZV_MARKER_COUNT'				=> [
		0	=> 'Of the existing items none are shown on the map.',
		1	=> 'Of the existing items %1$d item is shown on the map.',
		2	=> 'Of the existing items %1$d items are shown on the map.',
	],
	'MOT_TZV_MAP_LANG'					=> 'en',	// set according to this language, USE LOWERCASE LETTERS ONLY!!!
	'MOT_TZV_OSM_LARGER_MAP'			=> 'View larger map',

	// [Eintragen / Ändern]
	'MOT_TZV_HWTEXT_ADD'				=> '&bull;&nbsp;&nbsp;Please use the search function to check whether an identical item is already in the database.<br>
											&bull;&nbsp;&nbsp;You do not need <b>to complete</b> this form. You can come back later in order to complete or edit your input.<br>
											&bull;&nbsp;&nbsp;But: <b>Item name / Postal code  / City / Item description</b> are <b>mandatory</b> in any case!',

	'MOT_TZV_HWTEXT_EDIT'				=> '&bull;&nbsp;&nbsp; <b>Please note:</b><br>
											&bull;&nbsp;&nbsp; While editing <b>you might want to make another choice</b> from the select boxes!<br>
											&bull;&nbsp;&nbsp;<b>Item name / Postal code  / City / Item description</b> are <b>mandatory</b> in any case!',

	'MOT_TZV_HWTEXT_GPS'				=> 'Map functions are enabled, <strong>input of coordinates is mandatory!</strong><br>
											&bull;&nbsp;&nbsp; Please enter coordinates with a <b>decimal point</b>, not with a <b>comma</b>! <br>
											&bull;&nbsp;&nbsp; Correct: <b>51.055257</b> | Incorrect: <b>51,055257</b><br>
											&bull;&nbsp;&nbsp; The item can only be displayed on the map if correct coordinates are entered.',

	'MOT_TZV_HWTEXT_SEND'				=> '&bull;&nbsp;&nbsp; <b>Please check your input before submitting.</b>',
	'MOT_TZV_TOURZIEL_INVALID'			=> 'An item with this name already exists! Your input was rejected!',
	'MOT_TZV_GENERAL_ERROR'				=> 'An error occurred while saving an item:<br><strong>%1$s</strong><br>',

	// Select-Boxen
	'MOT_TZV_AUSWAHL'					=> 'Please choose',
	'MOT_TZV_EDIT_AUSWAHL'				=> 'Please choose if necessary!',

	// Error eintragen / ändern
	'MOT_TZV_ERROR'						=> 'Error!',
	'MOT_TZV_ERROR_NAME'				=> 'You must enter a name for this item!',
	'MOT_TZV_ERROR_POSTALCODEZ'			=> 'You must enter a postal code for this item!',
	'MOT_TZV_ERROR_CITY'				=> 'You must enter a city for this item!',
	'MOT_TZV_ERROR_LAT'					=> 'Latitude must not be empty or zero!',
	'MOT_TZV_ERROR_LON'					=> 'Longitude must not be empty or zero!',
	'MOT_TZV_ERROR_MESSAGE'				=> 'You must enter a description for this item!',

	'MOT_TZV_MAIN_EDIT'					=> 'Edit item',
	'MOT_TZV_LISTEN_ID'					=> '<b>ID</b>',
	'MOT_TZV_LISTEN_NAME'				=> 'Item name',
	'MOT_TZV_LISTEN_LAND'				=> 'Country',
	'MOT_TZV_LISTEN_REGION'				=> 'Region',
	'MOT_TZV_LISTEN_CATEGORY'			=> 'Category',
	'MOT_TZV_LISTEN_WLAN'				=> 'WLAN',
	'MOT_TZV_LISTEN_PLZ'				=> 'Postal code',
	'MOT_TZV_LISTEN_ORT'				=> 'City',
	'MOT_TZV_LISTEN_STRASSE'			=> 'Street address',
	'MOT_TZV_LISTEN_TELEFON'			=> 'Telephone',
	'MOT_TZV_LISTEN_EMAIL'				=> 'E-mail',
	'MOT_TZV_LISTEN_HOMEPAGE'			=> 'Homepage',
	'MOT_TZV_LISTEN_HOMEPAGE_HINT'		=> 'Please enter the entire URL (e.g.: http(s)://www.website.com)',
	'MOT_TZV_LISTEN_GPS'				=> 'GPS',
	'MOT_TZV_LISTEN_MAPS_LAT'			=> 'GPS Latitude',
	'MOT_TZV_LISTEN_MAPS_LON'			=> 'GPS Longitude',
	'MOT_TZV_LISTEN_USER'				=> 'Created by',
	'MOT_TZV_MESSAGE_INFO'				=> '<br><b>Item description</b>',

	// Messages
	'MOT_TZV_EVENT_ADD_SUCCESSFUL'		=> 'Successfully stored the new item in the database.',
	'MOT_TZV_EVENT_EDIT_SUCCESSFUL'		=> 'Successfully edited the item.',
	'MOT_TZV_EVENT_DELETE_SUCCESSFUL'	=> 'Successfully deleted the item.',
	'MOT_TZV_EVENT_NOT_DELETED'			=> 'Item was not deleted.',

	'MOT_TZV_RETURN_EVENT'				=> 'Back to this item',
	'MOT_TZV_VIEW_EVENT'				=> 'To the detailed view',
	'MOT_TZV_DETAIL_VIEW_LINK'			=> 'Please click on the item name for a detailed view',
	'MOT_TZV_VIEW_EVENT_EDIT'			=> 'Display / edit item',
	'MOT_TZV_EVENT_DELETE_CONFIRM'		=> 'Do you really want to delete the item named <strong>%s</strong>?',

	// [Suchfunktion]
	'MOT_TZV_SEARCH_COUNTRY'			=> 'Search for country',
	'MOT_TZV_SEARCH_REGION'				=> 'Search for region',
	'MOT_TZV_SEARCH_CATEGORY'			=> 'Search for category',
	'MOT_TZV_SEARCH_AUSWAHL'			=> 'Choose from a select box and click on the respective `Search` button.',
	'MOT_TZV_SEARCH_TEXT'				=> 'Enter your search term into the respective field and click on the `Search` button.',
	'MOT_TZV_SEARCH_TOURZIEL'			=> 'Search for item name',
	'MOT_TZV_SEARCH_PLZ'				=> 'Search for postal code',
	'MOT_TZV_SEARCH_ORT'				=> 'Search for city',
	'MOT_TZV_BUTTON_SUCHEN'				=> 'Search',
	'MOT_TZV_SEARCH_FOUND'				=> '<b>Number of found items</b>',

	// Meldungen Berechtigung
	'MOT_TZV_TOURZIEL_NO_ADD'			=> 'You do not have the permission to create a new tour destination!',
	'MOT_TZV_TOURZIEL_NO_EDIT'			=> 'You do not have the permission to edit a tour destination!',
	'MOT_TZV_TOURZIEL_NO_VIEW'			=> 'You do not have the permission to view Tour destinations!',
	'MOT_TZV_NO_ENTRIES'				=> 'No items found',
	'MOT_TZV_NO_SUCH_ITEM'				=> 'The selected item does not exist!',

	// Notifications
	'MOT_TZV_NOTIFY_NEW_TZ'				=> '<strong>New tour destination</strong><br>The user „%2$s“ created a new POI named „<strong>%1$s</strong>“.',
	'MOT_TZV_NOTIFY_TZ_EDITED'			=> '<strong>Edited tour destionation</strong><br>The user „%2$s“ has edited the tour destination named „<strong>%1$s</strong>“.',
	'MOT_TZV_NOTIFY_TZ_DELETED'			=> '<strong>Deleted tour destination</strong><br>The user „%2$s“ deleted the tour destination named „<strong>%1$s</strong>“.',

	// TZV-FOOTER
	'MOT_TZV_FOOTER'					=> 'phpBB Extension <b>Tour destinations</b> &copy; <a href="https://www.mike-on-tour.com" target="_blank" rel="noopener">Mike-on-Tour</a> ',
]);
