<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	// ACP Module
	'ACP_MOT_TZV_TOURZIEL'			=> 'Tour destinations',
	'ACP_MOT_TZV_SETTINGS'			=> 'Settings',
	'ACP_MOT_TZV_COUNTRY'			=> 'Attend to countries',
	'ACP_MOT_TZV_REGION'			=> 'Attend to regions',
	'ACP_MOT_TZV_CATEGORY'			=> 'Attend to categories',
	'ACP_MOT_TZV_WLAN'				=> 'Attend to WLAN options',
	'ACP_MOT_TZV_INFO'				=> 'Info / Support / Update',
	'ACP_MOT_TZV_VERSION'			=> '<img src="https://img.shields.io/badge/Version-%1$s-green.svg?style=plastic" />',
	'ACP_MOT_TZV_HELP'				=> 'Please refer to the help section at <b>Info / Support / Update</b> prior to changing any settings.',

	// Permissions
	'ACP_MOT_TZV_PERMISSION_EXPL'	=> 'Tour destination permissions',
	'ACP_MOT_TZV_PERMISSION'		=> 'Permissions for the user roles <strong>All Features</strong> and <strong>Standard Features</strong>
										are set during activation (installation) of the extension.<br>',
	'ACP_MOT_TZV_PERMISSION_GROUP'	=> 'You can adapt permissions for all user roles according to your preferences.<br> <b>ACP > PERMISSIONS > User roles > `user role` > Tour destinations</b>',

	// ACP settings
	'ACP_MOT_TZV_ENABLE_EXPLAIN'	=> 'Tour destinations main settings',
	'ACP_MOT_TZV_ENABLE'			=> 'Enable the extension',
	'ACP_MOT_TZV_ADMIN'				=> 'Enable administrator mode',
	'ACP_MOT_TZV_ADMIN_EXPL'		=> 'If enabled only administrators can see and use the extension.',
	'ACP_MOT_TZV_ENABLE_MESSAGE'	=> 'Extension is enabled, authorised groups can see and use it.',
	'ACP_MOT_TZV_DISABLE_MESSAGE'	=> 'Extension is disabled.',
	'ACP_MOT_TZV_ADMIN_MESSAGE'		=> 'Administrator mode enabled, only administrators can see and use the extension!',

	// Map settings
	'ACP_MOT_TZV_MAPSETTING_TITLE'	=> 'Settings for the general map',
	'ACP_MOT_TZV_MAPSETTING_TEXT'	=> 'Settings for initial map center and zoom.',
	'ACP_MOT_TZV_LAT'				=> 'Latitude of the map center',
	'ACP_MOT_TZV_LAT_EXP'			=> 'Values between 90.0° (North Pole) and -90.0° (South Pole)',
	'ACP_MOT_TZV_LON'				=> 'Longitude of the map center',
	'ACP_MOT_TZV_LON_EXP'			=> 'Values between 180.0° (East) and -180.0° (West)',
	'ACP_MOT_TZV_ZOOM'				=> 'Initial zoom of the map',
	'ACP_MOT_TZV_MAP_CLUSTERS'		=> 'Cluster markers',
	'ACP_MOT_TZV_MAP_CLUSTERS_EXP'	=> 'To avoid cluttering the map with a high number of markers you can activate this setting to build clusters of markers.
										These clusters vary with the zoom.',

	'ACP_MOT_TZV_GOOGLE_FUNCTIONS'	=> 'Settings for the maps of the detailed view',
	'ACP_MOT_TZV_MAPS_ENABLE'		=> 'Enable detail map(s) with detailed view',
	'ACP_MOT_TZV_MAPS_ENABLE_EXPL'	=> 'Switches ALL detail map function on or off',
	'ACP_MOT_TZV_MAPS_EXPLAIN'		=> 'Detail map(s) must be enabled as a prerequisite',
	'ACP_MOT_TZV_GOOGLEMAP_ENABLE'	=> 'Show Google Maps',
	'ACP_MOT_TZV_OSMMAP_ENABLE'		=> 'Show OpenStreetMap',
	'ACP_MOT_TZV_KURVIGER_ENABLE'	=> 'Hand GPS data over to www.kurviger.de',
	'ACP_MOT_TZV_KURVIGER_EXPL'		=> 'Hand tour destination`s GPS data over to route planner',
	'ACP_MOT_TZV_MSG_GOOGLE_ON'		=> 'Detail maps are enabled',
	'ACP_MOT_TZV_MSG_GOOGLE_OFF'	=> 'Detail maps are disabled',
	'ACP_MOT_TZV_MAPS_WIDTH'		=> 'Width of the detail map(s) (pixels)',
	'ACP_MOT_TZV_MAPS_HEIGHT'		=> 'Height of the detail map(s) (pixels)',
	'ACP_MOT_TZV_MAPS_ZOOM'			=> 'Initial zoom of the detail map(s)',

	// Additional settings
	'ACP_MOT_TZV_ADDIT_FUNCS'		=> 'Settings for additional functions',
	'ACP_MOT_TZV_STATS_ENABLE'		=> 'Display total number of tour destinations in the board index`s `STATISTICS` section',
	'ACP_MOT_TZV_NEWS_ADD_ENABLE'	=> 'Display latest tour destination underneath the forum list',
	'ACP_MOT_TZV_COUNTRY_ENABLE'	=> 'Display country flags on Tour destination`s index page',
	'ACP_MOT_TZV_MAIN_IMAGE'		=> 'Display images on Tour destination`s index page',
	'ACP_MOT_TZV_MAININFO'			=> 'List this version`s new features',
	'ACP_MOT_TZV_SUPPORT_ENABLE'	=> 'Show Tour destination`s support link',
	'ACP_MOT_TZV_SUPPORT_EXPL'		=> 'Please refer to `Info / Support / Update`',
	'ACP_MOT_TZV_SUPPORT_LINK'		=> 'Path to the support link',
	'ACP_MOT_TZV_LATEST_TZ_VIEW'	=> 'How to display the latest tour destination in the Tour destination list',
	'ACP_MOT_TZV_LIST_TZ_VIEW'		=> 'How to display tour destinations in the Tour destination list and the search result',
	'ACP_MOT_TZV_LIST_TZ_DETAIL'	=> 'Detailed',
	'ACP_MOT_TZV_LIST_TZ_SHORT'		=> 'As table',

	// Pagination settings
	'ACP_MOT_TZV_PAGINATION'		=> 'Pagination settings',
	'ACP_MOT_TZV_ROWS_ACP'			=> 'Number of lines per table page in the ACP',
	'ACP_MOT_TZV_ROWS_FRONT'		=> 'Number of lines per table page in the front-end',

	// ACP messages
	'ACP_MOT_TZV_CONFIG_SAVED'		=> 'Tour destination settings successfully saved',
	'ACP_MOT_TZV_MSG_DELETED'		=> 'Entry deleted.',
	'ACP_MOT_TZV_MSG_EDITED'		=> 'Entry edited.',
	'ACP_MOT_TZV_MSG_ADDED'			=> 'Newly created entry successfully saved.',
	'ACP_MOT_TZV_ERROR_NO_NAME'		=> 'You have not supplied any information.',
	'ACP_MOT_TZV_ERROR_NOT_EXIST'	=> 'The selected entry does not exist.',
	'ACP_MOT_TZV_NAME_EXISTS'		=> 'An entry with this name already exists!',
	'ACP_MOT_TZV_NO_ENTRIES'		=> 'No entries',

	// ACP country
	'ACP_MOT_TZV_DELETE'			=> '<b>Do not delete entries already used in a tour destination.<br>
										This may cause errors in retrieving data from the database and prevent tour destinations from being listed!</b>',
	'ACP_MOT_TZV_COUNTRIES'			=> 'Existing countries',
	'ACP_MOT_TZV_COUNTRY_NAME'		=> 'Country',
	'ACP_MOT_TZV_COUNTRY_IMG'		=> 'Flag',
	'ACP_MOT_TZV_COUNTRY_COUNT'		=> 'Number of tour destinations',
	'ACP_MOT_TZV_COUNTRY_CONFIRM_DELETE'	=> 'Do you really want to delete the country named <strong>%s</strong>?',
	'ACP_MOT_TZV_COUNTRY_ADD'		=> 'Create new country',
	'ACP_MOT_TZV_COUNTRY_EDIT'		=> 'Edit country',
	'ACP_MOT_TZV_COUNTRY_NAME_EXPL'	=> 'e.g. <b>Germany</b>',
	'ACP_MOT_TZV_COUNTRY_IMG_EXPL'	=> 'e.g. <b>de.png</b>',
	'ACP_MOT_TZV_COUNTRY_FLAGADDL'	=> 'Use `png` format. Type `two-letter country code.png`, e.g. <b>ch.png</b> for Switzerland<br><br>',

	// ACP region
	'ACP_MOT_TZV_REGIONS'			=> 'Existing regions',
	'ACP_MOT_TZV_REGION_NAME'		=> 'Region',
	'ACP_MOT_TZV_REGION_CONFIRM_DELETE'		=> 'Do you really want to delete the region named <strong>%s</strong>?',
	'ACP_MOT_TZV_REGION_ADD'		=> 'Create new region',
	'ACP_MOT_TZV_REGION_EDIT'		=> 'Edit regions',
	'ACP_MOT_TZV_REGION_NAME_EXPL'	=> 'e.g. <b>Wales</b>',

	// ACP category
	'ACP_MOT_TZV_CATS'				=> 'Existing Categories',
	'ACP_MOT_TZV_CATS_NAME'			=> 'Category',
	'ACP_MOT_TZV_CAT_CONFIRM_DELETE'	=> 'Do you really want to delete the category named <strong>%s</strong>?',
	'ACP_MOT_TZV_CAT_ADD'			=> 'Create new category',
	'ACP_MOT_TZV_CAT_EDIT'			=> 'Edit category',
	'ACP_MOT_TZV_CAT_NAME_EXPL'		=> 'e.g. <b>Hotel</b>',

	// ACP WLAN options
	'ACP_MOT_TZV_WLANS'				=> 'Existing WLAN options',
	'ACP_MOT_TZV_WLAN_NAME'			=> 'WLAN options',
	'ACP_MOT_TZV_WLAN_CONFIRM_DELETE'	=> 'Do you really want to delete the WLAN option named <strong>%s</strong>?',
	'ACP_MOT_TZV_WLAN_ADD'			=> 'Create new WLAN option',
	'ACP_MOT_TZV_WLAN_EDIT'			=> 'Edit WLAN option',
	'ACP_MOT_TZV_WLAN_NAME_EXPL'	=> 'e.g. <b>free WLAN</b>',

]);
