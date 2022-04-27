<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
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
	'UCP_MOT_TZV_TOURZIEL'			=> 'Tourzielverwaltung',
	'UCP_MOT_TZV_SUMMARY'			=> 'Übersicht',
	'UCP_MOT_TZV_SUMMARY_TITLE'		=> 'Meine Tourziele',
	'UCP_MOT_TZV_NAME'				=> 'Name',
	'UCP_MOT_TZV_CATEGORY'			=> 'Kategorie',
	'UCP_MOT_TZV_COUNTRY'			=> 'Land / Region',
	'UCP_MOT_TZV_NO_ENTRIES'		=> 'Keine Einträge',
	'EDIT'							=> 'Ändern',
	'UCP_MOT_TZV_NOT_AUTHORISED'	=> 'Du bist zu dieser Operation nicht berechtigt',
	'UCP_MOT_TZV_BACK_TO_UCP'		=> 'Zurück zum persönlichen Bereich',
	// Notification
	'UCP_MOT_TZV_NOTIFY_MOD'		=> 'Benachrichtigungen zur Adressverwaltung Tourziele',
	'UCP_MOT_TZV_NOTIFY_NEW_TZ'		=> 'Ein neues Tourziel wurde erstellt',
	'UCP_MOT_TZV_NOTIFY_TZ_EDITED'	=> 'Ein Tourziel wurde geändert',
	'UCP_MOT_TZV_NOTIFY_TZ_DELETED'	=> 'Ein Tourziel wurde gelöscht',
]);
