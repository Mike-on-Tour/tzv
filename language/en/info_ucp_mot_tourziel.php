<?php
/**
*
* @package phpBB Extension [Tour destinations]
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
	// UCP tab
	'UCP_MOT_TZV_TOURZIEL'			=> 'Tour destinations',
	'UCP_MOT_TZV_SUMMARY'			=> 'Summary	',
	'UCP_MOT_TZV_SUMMARY_TITLE'		=> 'My tour destinations',
	'UCP_MOT_TZV_NAME'				=> 'Name',
	'UCP_MOT_TZV_CATEGORY'			=> 'Category',
	'UCP_MOT_TZV_COUNTRY'			=> 'Country / Region',
	'UCP_MOT_TZV_NO_ENTRIES'		=> 'No Entries',
	'EDIT'							=> 'Edit',
	'UCP_MOT_TZV_NOT_AUTHORISED'	=> 'You are not authorised to execute this operation',
	'UCP_MOT_TZV_BACK_TO_UCP'		=> 'Back to the UCP',
	// Notification
	'UCP_MOT_TZV_NOTIFY_MOD'		=> 'Notifications for Tour destinations',
	'UCP_MOT_TZV_NOTIFY_NEW_TZ'		=> 'A new tour destination was created',
	'UCP_MOT_TZV_NOTIFY_TZ_EDITED'	=> 'A tour destination has been edited',
	'UCP_MOT_TZV_NOTIFY_TZ_DELETED'	=> 'A tour destination was deleted',
]);
