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
	'MOT_TZV_TOURZIEL'						=> 'Tourziel',
	'MOT_TZV_ERROR_EXTENSION_NOT_ENABLE'	=> 'Die Erweiterung „%1$s“ kann nicht aktiviert werden. Prüfe die Voraussetzungen, die für die Erweiterung notwendig sind.',
	'MOT_TZV_ERROR_MESSAGE_PHPBB_VERSION'	=> 'Minimum ist phpBB %2$s aber kleiner als %3$s',
]);
