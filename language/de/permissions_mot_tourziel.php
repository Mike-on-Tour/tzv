<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
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
	'ACL_MOT_CAT_TOURZIEL'		=> 'Tourziele',

	'ACL_U_MOT_TZV_MAINVIEW'	=> 'Kann Tourziele Startseite sehen',
	'ACL_U_MOT_TZV_VIEW'		=> 'Kann Tourziele sehen',
	'ACL_U_MOT_TZV_ADD'		    => 'Kann Tourziele eintragen',
	'ACL_U_MOT_TZV_EDIT_OWN'	=> 'Kann eigene Tourziele ändern',
	'ACL_U_MOT_TZV_DELETE_OWN'	=> 'Kann eigene Tourziele löschen',

	'ACL_M_MOT_TZV_EDIT'		=> 'Kann jedes Tourziele ändern',
	'ACL_M_MOT_TZV_DELETE'		=> 'Kann jedes Tourziele löschen',
]);
