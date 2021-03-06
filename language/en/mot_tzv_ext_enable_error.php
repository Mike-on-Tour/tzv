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
	'MOT_TZV_TOURZIEL'						=> 'Tour destinations',
	'MOT_TZV_ERROR_EXTENSION_NOT_ENABLE'	=> 'The extension ā%1$sā can not be enabled. Please check whether the necessary requirements for this extension are satisfied.',
	'MOT_TZV_ERROR_MESSAGE_PHPBB_VERSION'	=> 'Minimum version of phpBB required is %2$s but less than %3$s',
]);
