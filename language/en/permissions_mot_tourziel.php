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

$lang = array_merge($lang, [
	'ACL_MOT_CAT_TOURZIEL'		=> 'Tour destinations',

	'ACL_U_MOT_TZV_MAINVIEW'	=> 'Can use Tour destinations',
	'ACL_U_MOT_TZV_VIEW'		=> 'Can see tour destinations list, map and search',
	'ACL_U_MOT_TZV_ADD'		    => 'Can create new tour destinations',
	'ACL_U_MOT_TZV_EDIT_OWN'	=> 'Can edit own tour destinations',
	'ACL_U_MOT_TZV_DELETE_OWN'	=> 'Can delete own tour destinations',

	'ACL_M_MOT_TZV_EDIT'		=> 'Can edit tour destinations',
	'ACL_M_MOT_TZV_DELETE'		=> 'Can delete tour destinations',
]);
