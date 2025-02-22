<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* @language file (British English)
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » „ “ — …
//

$lang = array_merge($lang, [

	// Titel
	'MOT_TZV_TOURZIEL'					=> 'Tour destinations',

	// Tabs
	'MOT_TZV_TAB_INDEX'					=> 'Summary',
	'MOT_TZV_TAB_LIST'					=> 'List',
	'MOT_TZV_TAB_MAP'					=> 'Map',
	'MOT_TZV_TAB_SEARCH'				=> 'Search',
	'MOT_TZV_TAB_CREATE'				=> 'Create',

	// Forum Index
	'MOT_TZV_NEW_EVENT'					=> 'Latest/last edited Tour destination item',
	'MOT_TZV_NEW_FLAG'					=> 'Flag',
	'MOT_TZV_NEW_LAND'					=> 'Country',
	'MOT_TZV_NEW_REGION'				=> 'Region',
	'MOT_TZV_NEW_TIME'					=> 'Created on',
	'MOT_TZV_NEW_AUTHOR'				=> 'Created by',
	'MOT_TZV_NO_NEW_EVENT'				=> 'No item found.',
	'MOT_TZV_TOURZIEL_TOTAL'			=> '&bull;&nbsp;Total number of tour destinations',

	// Buttons - Links / Titel Texte
	'MOT_TZV_MAIN_ADD'					=> 'New item',
	'MOT_TZV_MAIN_VIEW'					=> 'Tour destination list',
	'MOT_TZV_MAIN_VIEW_NEW'				=> 'Latest/last edited item',
	'MOT_TZV_MAIN_MAP'					=> 'Tour destination map',
	'MOT_TZV_MAIN_SEARCH'				=> 'Tour destination search',
	'MOT_TZV_MAIN_SUPPORT'				=> 'Tour destination support',
	'MOT_TZV_SUPPORT_FORUM'				=> 'Support forum',
	'MOT_TZV_MAIN_GPSTIPP'				=> 'On the opening site please enter the destination, e.g. <strong>Goat Ledge</strong>',
	'MOT_TZV_MAIN_ADDGPS'				=> 'Find coordinates',

	'MOT_TZV_RETURN_TOURZIEL'			=> 'Back to index',

	// Pagination
	'MOT_TZV_POSTS_COUNT'				=> [
		0	=> 'no items',
		1	=> '%d item',
		2	=> '%d items',
	],

	// Index tab
	'MOT_TZV_TOURZIEL_MAIN'				=> 'Tour destinations database',
	'MOT_TZV_COUNT_TOTAL_DEST'			=> [
		0	=> 'Currently <strong>no</strong> items are listed in the database.',
		1	=> 'Currently <strong>%1$d</strong> item is listed in the database.',
		2	=> 'Currently <strong>%1$d</strong> items are listed in the database.',
	],
	'MOT_TZV_COUNTRY_EINTRAG'			=> 'Registered countries',
	'MOT_TZV_NEWS_TITLE'				=> 'What is new in this version',
	'MOT_TZV_MAINNEWS_INFO'				=> '&bull;&nbsp;&nbsp;Complete revision with transition to tabs in the frontend<br>
											&bull;&nbsp;&nbsp;Substantial code improvements<br>
											&bull;&nbsp;&nbsp;Unification of displaying the destinations<br>
											&bull;&nbsp;&nbsp;Changing the maximum possible versions (phpBB < 3.4.0@dev und PHP < 8.5.0@dev)<br>',
	'MOT_TZV_NUTZUNG_MAPS'				=> 'Google Maps Terms of Use',

	// Detailansicht
	'MOT_TZV_TOURZIEL_DETAIL'			=> 'Detailed view',
	'MOT_TZV_TOURZIEL_DETAIL_CLICK'		=> 'Click for detailed view',
	'MOT_TZV_TOURZIEL_STRASSE_NR'		=> 'Street address',
	'MOT_TZV_DATE_ADD_EDIT'				=> 'Created on',
	'MOT_TZV_KURVIGER_INFO'				=> 'Hand over coordinatesto motor cycle route planner https://kurviger.com',
	'MOT_TZV_KOMOOT_INFO'				=> 'Hand over coordinates to hiker and biker route planner https://www.komoot.com',
	'MOT_TZV_DATA_HANDOVER'				=> 'Data transfer',

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

	// Eintragen / Ändern
	'MOT_TZV_HWTEXT_ADD'				=> '&bull;&nbsp;&nbsp;Please use the search function to check whether an identical item is already in the database.<br>
											&bull;&nbsp;&nbsp;You do not need <strong>to complete</strong> this form. You can come back later in order to complete or edit your input.',

	'MOT_TZV_HWTEXT_EDIT'				=> '&bull;&nbsp;&nbsp; <strong>Please note:</strong><br>
											&bull;&nbsp;&nbsp; While editing <strong>you might want to make another choice</strong> from the select boxes!',

	'MOT_TZV_HWTEXT_MANDATORY_FIELDS'	=> '&bull;&nbsp;&nbsp;<strong>Mandatory input fields</strong> are marked with a <span style="color: red;">*</span> , they
											<strong>must not</strong> be left without a selection/input!',

	'MOT_TZV_HWTEXT_GPS'				=> '&bull;&nbsp;&nbsp; Please enter coordinates with a <strong>decimal point</strong>, not with a <strong>comma</strong>!<br>
											&bull;&nbsp;&nbsp; Correct: <strong>51.055257</strong> | Incorrect: <strong>51,055257</strong><br>
											&bull;&nbsp;&nbsp; The item can only be displayed on the map if correct coordinates are entered.',

	'MOT_TZV_HWTEXT_SEND'				=> '&bull;&nbsp;&nbsp; <strong>Please check your input before submitting.</strong>',
	'MOT_TZV_TOURZIEL_INVALID'			=> 'An item with this name already exists! Your input was rejected!',
	'MOT_TZV_GENERAL_ERROR'				=> 'An error occurred while saving an item:<br><strong>%1$s</strong><br>',

	// Select-Boxen
	'MOT_TZV_AUSWAHL'					=> 'Please select',
	'MOT_TZV_EDIT_AUSWAHL'				=> 'Please select if necessary!',

	// Error eintragen / ändern
	'MOT_TZV_ERROR'						=> 'Error!',
	'MOT_TZV_ERROR_NAME'				=> 'You must enter a name for this item!',
	'MOT_TZV_ERROR_COUNTRY'				=> 'You must select a country!',
	'MOT_TZV_ERROR_REGION'				=> 'You must select a region!',
	'MOT_TZV_ERROR_CATEGORY'			=> 'You must select a category!',
	'MOT_TZV_ERROR_POSTALCODEZ'			=> 'You must enter a postal code for this item!',
	'MOT_TZV_ERROR_CITY'				=> 'You must enter a city for this item!',
	'MOT_TZV_ERROR_STREET'				=> 'You must enter a street address!',
	'MOT_TZV_ERROR_TELEPHONE'			=> 'You must enter a telephone number!',
	'MOT_TZV_ERROR_EMAIL'				=> 'You must enter a e-mail address!',
	'MOT_TZV_ERROR_HOMEPAGE'			=> 'You must enter a homepage!',
	'MOT_TZV_ERROR_LAT'					=> 'Latitude must not be empty or zero!',
	'MOT_TZV_ERROR_LON'					=> 'Longitude must not be empty or zero!',
	'MOT_TZV_ERROR_WLAN'				=> 'You must select a WLAN description!',
	'MOT_TZV_ERROR_MESSAGE'				=> 'You must enter a description for this item!',

	'MOT_TZV_MAIN_EDIT'					=> 'Edit item',
	'MOT_TZV_LISTEN_MANDATORY'			=> ' <span style="color: red;">*</span>',
	'MOT_TZV_LISTEN_ID'					=> '<strong>ID</strong>',
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
	'MOT_TZV_LISTEN_GPS'				=> 'Coordinates',
	'MOT_TZV_LISTEN_MAPS_LAT'			=> 'Latitude',
	'MOT_TZV_LISTEN_MAPS_LON'			=> 'Longitude',
	'MOT_TZV_LISTEN_USER'				=> 'Created by',
	'MOT_TZV_MESSAGE_INFO'				=> 'Item description',

	// Messages
	'MOT_TZV_EVENT_ADD_SUCCESSFUL'		=> 'Successfully stored the new item in the database.',
	'MOT_TZV_EVENT_EDIT_SUCCESSFUL'		=> 'Successfully edited the item.',
	'MOT_TZV_EVENT_DELETE_SUCCESSFUL'	=> 'Successfully deleted the item.',
	'MOT_TZV_EVENT_NOT_DELETED'			=> 'Item was not deleted.',

	'MOT_TZV_RETURN_EVENT'				=> 'Back to this item',
	'MOT_TZV_VIEW_EVENT'				=> 'To the detailed view',
	'MOT_TZV_DETAIL_VIEW_LINK'			=> 'Please click on the item name for a detailed view',
	'MOT_TZV_EVENT_DELETE_CONFIRM'		=> 'Do you really want to delete the item named <strong>%s</strong>?',

	// Suchfunktion
	'MOT_TZV_SEARCH_EXPLANATION'		=> 'Select in one or more select boxes and/or enter the destination`s name and/or location and then click the button `Start search`.<br>
											A text input could be the destination`s name or location in full length or just a part of it, you can make the input regardless of upper
											or lower case letters.<br>
											Please note that using more than one parameter combines those parameters with an Or-operation, which means that the search result will
											contain all destinations matching these criteria! Searching for destinations within a certain country and belonging to a certain category
											will result in a list of destinations meeting both criteria.',
	'MOT_TZV_SEARCH_TOURZIEL'			=> 'Search for item name',
	'MOT_TZV_SEARCH_COUNTRY'			=> 'Search for country',
	'MOT_TZV_SEARCH_REGION'				=> 'Search for region',
	'MOT_TZV_SEARCH_CATEGORY'			=> 'Search for category',
	'MOT_TZV_SEARCH_ORT'				=> 'Search for city',
	'MOT_TZV_BUTTON_SEARCH'				=> 'Start search',
	'MOT_TZV_SEARCH_FOUND'				=> '<strong>Number of found items</strong>',

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
]);
