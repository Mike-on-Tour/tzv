<?php
/**
*
* @package phpBB Extension [Tour destinations]
* @copyright (c) 2014 - 2021 waldkatze
* @copyright (c) 2022 - 2023 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* @ acp language file (British English)
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
	// ACP TZV HILFE
	'ACP_MOT_TZV_INFO_TITLE'	=> 'Info Tour destinations',
	'ACP_MOT_TZV_UPDATE'		=> '<strong>A backup of your database is recommended prior to updates and settings changes!</strong>',
	'ACP_MOT_TZV_COPYRIGHT'		=> '&bull;&nbsp;copyright (c) 2014 - 2021 Author: <b>waldkatze</b> (no more support)<br>
									<span style="color: transparent;">&bull;</span>&nbsp;copyright (c) 2022 - %1$s Author: <b>Mike-on-Tour</b> (no support via e-mail or PM)<br>',
	'ACP_MOT_TZV_SUPPORT'		=> '&bull;&nbsp;EXTENSION Demo / Download / Update <a href="https://www.mike-on-tour.com/" target="_blank">https://www.mike-on-tour.com/</a><br><br>
									&bull;&nbsp;License <a href="http://opensource.org/licenses/gpl-license.php"> http://opensource.org/licenses/gpl-license.php</a> GNU Public License<br>',

	'ACP_MOT_TZV_HISTORY'		=> 'Tour destinations history',
	'ACP_MOT_TZV_HUPDATE'		=> '&bull;&nbsp;2023 Version 1.2.0<br>
									- Changes to the ACP (Toggle Control, changes in the ACP settings tab to make it more precise)
									- Changed the required minimal versions (phpBB 3.2.3 -> 3.3.1 and PHP 7.2.0 -> 7.4.0)<br>
									&bull;&nbsp;2022 Version 1.1.0<br>
									- Added a button leading to a website to find coordinates for named locations to the add/edit item tab
									- Changed the radio buttons in the ACP settings tab into sliders with the "activated" state on the
										left and the "deactivated" state on the right side (according to the "Yes" and "No" radio buttons)
									&bull;&nbsp;2022 Version 1.0.0<br>
									- Additional language pack: Formal German (de_x_sie)<br>
									- Category map overlays used in the map displaying search results, too<br>
									&bull;&nbsp;2022 Version 0.6.0<br>
									- Displaying the items in different map overlays named after the used categories<br>
									- Overlays can be switched on and off individually with the layer control element in the map`s upright corner<br>
									- Displaying the map`s distance scale in metric and imperial units<br>
									&bull;&nbsp;2022 Version 0.5.0<br>
									- Added an OSM search (Nominatim) to the map<br>
									- Added a data transfer to komoot.de to enable route planning for hikers and bikers (opens in a new tab or window)<br>
									- komoot.de will be called with a localisation variable to set the users language if this language is supported (e.g. if user uses English komoot.de will open in English)<br>
									- Added the ability to create a new item by right-clicking onto the desired map location and then be redirected to the input form with this location`s coordinates<br>
									&bull;&nbsp;2022 Version 0.4.0<br>
									- Search results are now shown on a map in addition to the table<br>
									- Changed the call to kurviger.de to open a new tab or window<br>
									- kurviger.de will be called with a localisation variable to set the users language if this language is supported (e.g. if user uses English kurviger.de will open in English)<br>
									- The images on the Tour destinations index page are no longer hard coded, the admin can use his own preferred images<br>
									&bull;&nbsp;2022 Version 0.3.0<br>
									- Added a general map to display all tour destinations<br>
									- Added a new UCP tab to list all tour destinations of the respective user<br>
									- Choice of a detailed or more general (table) display of items within the Tour destination list and the search result<br>
									- Notification of moderators in case of newly created, edited or deleted items<br>
									- Corrected the display of the OSM detail map<br>
									&bull;&nbsp;2022 Version 0.2.1<br>
									- Some bug fixes<br>
									- Removed country flag image files<br>
									- Country flag images are now retrieved from the internet<br>
									&bull;&nbsp;2022 Version 0.2.0<br>
									- Largely modified to adhere to phpBB`s Coding Guidelines<br>
									- Necessary code modifications for PHP 8<br>
									- Transformation of ACP modules into a controller<br>
									&bull;&nbsp;2021 Version 0.1.1<br>
									- Admin now can choose between display of Google-Maps and/or OpenStreetMap in the detailed view. Settings in the ACP.<br>
									- New feature: GPS data can be handed over to route planner www.kuviger.de with a mouse click.<br>
									- Responsiveness for mobile devices added.<br>
									- After creating a country / region / category in the ACP it is checked whether it already exists with the given name.<br>
									- Fixed a bug in displaying country flags in the ACP.<br>
									&bull;&nbsp;2021 Version 0.1.0 as an extension for phpBB 3.3.x <br>
									&bull;&nbsp;2014 Initial version as MOD for phpBB 3.0.x <br>',

	'ACP_MOT_TZV_HELP'			=> 'Tour destinations - some remarks to the settings<br><br>',
	'ACP_MOT_TZV_HELPLINE'		=> '<b>Permission settings</b><br>
									Permissions for the user roles `<strong>All Features</strong>` and `<strong>Standard Features</strong>`
									are set during activation (installation) of the extension. You can adapt permissions for all user roles according to your preferences.<br>
									Moderator permissions are set for the moderator role ´<strong>Full Moderator</strong>´ during activation of the extension.
									You can adapt the permissions for all other moderator roles as it suits your needs.<br><br>
									<b>Administrator mode</b><br>
									If enabled only administrators can access Tour destinations. (Meant for e.g. testing purposes)<br><br>
									<b>General map settings</b><br>
									You can define latitud and longitud of the initial map center.<br>
									In addition with the initial map zoom you can define what area is covered when the map display starts.<br>
									If there are many markers on the map it might be a good idea to cluster them to avoid cluttering the map, you can select this
									option here.<br><br>
									<b>Detailed view map settings</b><br>
									You can completely disable this feature with the first setting.<br>
									You can select whether Google Maps and/or OpenStreetMap should be displayed.<br>
									Size of the map boxes and the initial map zoom is selectable.<br><br>
									<b>Tour destinations support link anzeigen</b><br>
									If your users have questions about this extension it might be a good idea to start a support forum.<br>
									You can set the link to this forum here (e.g. ´viewtopic.php?f=1&t=69´).<br><br>
									<b>Attend to countries / regions / categories / WLAN options</b><br>
									Adjust these settings according to your needs..<br>
									This data is meant for testing purposes, you can edit or delete (as long as it has not been used in creating a tour
									destination) the pre-set data as you like and/or add your own data.<br>
									Please adhere to the note in the red box in those tabs!',
]);
