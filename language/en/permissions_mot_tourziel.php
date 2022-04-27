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
	'ACL_MOT_CAT_TOURZIEL'		=> 'Tour destinations',

	'ACL_U_MOT_TZV_MAINVIEW'	=> 'Can see the index page of Tour destinations',
	'ACL_U_MOT_TZV_VIEW'		=> 'Can see tour destinations',
	'ACL_U_MOT_TZV_ADD'		    => 'Can create tour destinations',
	'ACL_U_MOT_TZV_EDIT_OWN'	=> 'Can edit own tour destinations',
	'ACL_U_MOT_TZV_DELETE_OWN'	=> 'Can delete own tour destinations',

	'ACL_M_MOT_TZV_EDIT'		=> 'Can edit tour destinations',
	'ACL_M_MOT_TZV_DELETE'		=> 'Can delete tour destinations',
]);
