<?php
/**
*
* This file is part of the initial Adressverwaltung Tourziele Software package (ver 0.2.0)
*
* @name	transfer.php
* @version	0.2.0
* @copyright (c) 2022 Mike-on-Tour
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.' . $phpEx);

global $auth, $db, $user, $phpbb_container, $table_prefix;

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

$db_tools = $phpbb_container->get('dbal.tools');
if (!$db_tools->sql_table_exists($table_prefix . 'tourziel'))
{
	trigger_error("Keine Tabellen der Erweiterung <b>waldkatze/tzv</b> gefunden; Programm wird beendet!");
}

/*
* Get COUNTRY_TABLE; REGION_TABLE; CATS_TABLE and WLAN_TABLE and write them into our own tables in order to preserve the correct values
*
* We start each process with truncating our table and then fill it again with the values of the old table
*
*/
// Correctly handle empty COUNTRY_TABLE
switch ($db->get_sql_layer())
{
	case 'sqlite3':
		$db->sql_query('DELETE FROM ' . $table_prefix . 'mot_tourziel_country');
	break;

	default:
		$db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'mot_tourziel_country');
	break;
}
// Read the old table
$sql = 'SELECT * FROM ' . $table_prefix . 'tourziel_country';
$result = $db->sql_query($sql);
$countries = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
// Fill the new table
foreach ($countries as $row)
{
	$sql = 'INSERT INTO ' . $table_prefix . 'mot_tourziel_country ' . $db->sql_build_array('INSERT', $row);
	$db->sql_query($sql);
}


// Correctly handle empty REGION_TABLE
switch ($db->get_sql_layer())
{
	case 'sqlite3':
		$db->sql_query('DELETE FROM ' . $table_prefix . 'mot_tourziel_region');
	break;

	default:
		$db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'mot_tourziel_region');
	break;
}
// Read the old table
$sql = 'SELECT * FROM ' . $table_prefix . 'tourziel_region';
$result = $db->sql_query($sql);
$countries = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
// Fill the new table
foreach ($countries as $row)
{
	$sql = 'INSERT INTO ' . $table_prefix . 'mot_tourziel_region ' . $db->sql_build_array('INSERT', $row);
	$db->sql_query($sql);
}


// Correctly handle empty CATS_TABLE
switch ($db->get_sql_layer())
{
	case 'sqlite3':
		$db->sql_query('DELETE FROM ' . $table_prefix . 'mot_tourziel_cats');
	break;

	default:
		$db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'mot_tourziel_cats');
	break;
}
// Read the old table
$sql = 'SELECT * FROM ' . $table_prefix . 'tourziel_cats';
$result = $db->sql_query($sql);
$countries = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
// Fill the new table
foreach ($countries as $row)
{
	$sql = 'INSERT INTO ' . $table_prefix . 'mot_tourziel_cats ' . $db->sql_build_array('INSERT', $row);
	$db->sql_query($sql);
}


// Correctly handle empty WLAN_TABLE
switch ($db->get_sql_layer())
{
	case 'sqlite3':
		$db->sql_query('DELETE FROM ' . $table_prefix . 'mot_tourziel_wlan');
	break;

	default:
		$db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'mot_tourziel_wlan');
	break;
}
// Read the old table
$sql = 'SELECT * FROM ' . $table_prefix . 'tourziel_wlan';
$result = $db->sql_query($sql);
$countries = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
// Fill the new table
foreach ($countries as $row)
{
	$sql = 'INSERT INTO ' . $table_prefix . 'mot_tourziel_wlan ' . $db->sql_build_array('INSERT', $row);
	$db->sql_query($sql);
}


// Get total number of old Tourziele
$sql = "SELECT COUNT(id) AS 'total_tz' FROM " . $table_prefix . "tourziel";
$result = $db->sql_query($sql);
$total_tz = $db->sql_fetchrow($result);
$db->sql_freeresult($result);
$total_tz = $total_tz['total_tz'];

// Since the new TOURZIEL_TABLE either is still empty or we don't want to delete any items already in it we can start with reading the old table
$sql = 'SELECT tz.*,
			rt.region_id, rt.region_name,
			kt.cat_id, kt.cat_name,
			wt.wlan_id, wt.wlan_name
		FROM ' . $table_prefix . 'tourziel tz
		JOIN ' . $table_prefix . 'tourziel_region rt
			ON tz.region = rt.region_name
		JOIN ' . $table_prefix . 'tourziel_cats kt
			ON tz.kategorie = kt.cat_name
		JOIN ' . $table_prefix . 'tourziel_wlan wt
			ON tz.wlan = wt.wlan_name
		ORDER BY tz.id ASC';

$result = $db->sql_query($sql);

$tz_transferred = 0;
$tz_names = [];

while ($row = $db->sql_fetchrow($result))
{
	$sql_array = [
		'name'				=> $row['name'],
		'street'			=> $row['strasse'],
		'postalcode'		=> $row['plz'],
		'city'				=> $row['ort'],
		'country'			=> $row['land'],
		'region'			=> $row['region_id'],
		'category'			=> $row['cat_id'],
		'wlan'				=> $row['wlan_id'],
		'telephone'			=> $row['telefon'],
		'email'				=> $row['email'],
		'homepage'			=> $row['homepage'],
		'maps_lat'			=> $row['maps_b'],
		'maps_lon'			=> $row['maps_l'],
		'user_id'			=> $row['user_id'],
		'message'			=> $row['message'],
		'bbcode_uid'		=> $row['bbcode_uid'],
		'bbcode_bitfield'	=> $row['bbcode_bitfield'],
		'bbcode_options'	=> $row['bbcode_options'],
		'enable_magic_url'	=> $row['enable_magic_url'],
		'enable_smilies'	=> $row['enable_smilies'],
		'enable_bbcode'		=> $row['enable_bbcode'],
		'post_time'			=> $row['post_time'],
	];
	$sql = 'INSERT INTO ' . $table_prefix . 'mot_tourziel ' . $db->sql_build_array('INSERT', $sql_array);
	$db->sql_query($sql);
	$tz_transferred++;
	$tz_names[] = $row['name'];
}
$db->sql_freeresult($result);

$msg = 'Alle Tabellen zu Ländern, Regionen, Kategorien und WLAN erfolgreich in die neue Version transferiert.<br><br>';
$msg .= 'Die alte Tabelle >Tourziele< enthielt <b>' . $total_tz . '</b> Einträge.<br>';
$msg .= 'In die neue Tabelle wurden davon <b>' . $tz_transferred . '</b> Tourziele übertragen.<br>';
$msg .= 'Die übertragenen Tourziele haben folgende Namen:<br>';
$msg .= implode(', ', $tz_names);
trigger_error($msg);
