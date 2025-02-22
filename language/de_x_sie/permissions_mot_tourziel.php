<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* @language file (Deutsch / Sie)
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
	'ACL_MOT_CAT_TOURZIEL'		=> 'Tourziele',

	'ACL_U_MOT_TZV_MAINVIEW'	=> 'Kann Tourziele benutzen',
	'ACL_U_MOT_TZV_VIEW'		=> 'Kann Tourziel-Liste, -Karte und -Suche sehen',
	'ACL_U_MOT_TZV_ADD'		    => 'Kann neue Tourziele erstellen',
	'ACL_U_MOT_TZV_EDIT_OWN'	=> 'Kann eigene Tourziele ändern',
	'ACL_U_MOT_TZV_DELETE_OWN'	=> 'Kann eigene Tourziele löschen',

	'ACL_M_MOT_TZV_EDIT'		=> 'Kann jedes Tourziele ändern',
	'ACL_M_MOT_TZV_DELETE'		=> 'Kann jedes Tourziele löschen',
]);
