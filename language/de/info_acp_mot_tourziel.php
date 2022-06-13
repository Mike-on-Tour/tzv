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
	// ACP Module
	'ACP_MOT_TZV_TOURZIEL'			=> 'Tourziele',
	'ACP_MOT_TZV_SETTINGS'			=> 'Einstellungen',
	'ACP_MOT_TZV_COUNTRY'			=> 'Länder bearbeiten',
	'ACP_MOT_TZV_REGION'			=> 'Region bearbeiten',
	'ACP_MOT_TZV_CATEGORY'			=> 'Kategorie bearbeiten',
	'ACP_MOT_TZV_WLAN'				=> 'WLAN bearbeiten',
	'ACP_MOT_TZV_INFO'				=> 'Info / Support / Update',
	'ACP_MOT_TZV_VERSION'			=> '<img src="https://img.shields.io/badge/Version-%1$s-green.svg?style=plastic">',
	'ACP_MOT_TZV_HELP'				=> 'Hilfe zu Einstellungen findest Du unter: <b>Info / Support / Update</b> Bitte vor Änderungen lesen.',

	// Einstellungen Berechtigung
	'ACP_MOT_TZV_PERMISSION_EXPL'	=> 'Tourziele Berechtigungen',
	'ACP_MOT_TZV_PERMISSION'		=> 'Berechtigungen für die Benutzerrollen <strong>Volle Funktionalität</strong> und <strong>Standard-Funktionaität</strong>
										werden bei Installation voreingestellt.<br>',
	'ACP_MOT_TZV_PERMISSION_GROUP'	=> 'Die Berechtigungen kannst du an deine Wünsche anpassen.<br> <b>ACP / BERECHTIGUNGEN / Benutzer-Rollen / Tourziele.</b>',

	// ACP Einstellungen
	'ACP_MOT_TZV_ENABLE_EXPLAIN'	=> 'Tourziele Hauptschalter',
	'ACP_MOT_TZV_ENABLE'			=> 'Tourziele aktivieren',
	'ACP_MOT_TZV_ADMIN'				=> 'Tourziele Administrator-Modus',
	'ACP_MOT_TZV_ADMIN_EXPL'		=> 'Nur Administrator kann Tourziele sehen.',
	'ACP_MOT_TZV_ENABLE_MESSAGE'	=> 'Tourziele ist eingeschaltet. Berechtigte Gruppen können Tourziele sehen.',
	'ACP_MOT_TZV_DISABLE_MESSAGE'	=> 'Tourziele ist ausgeschaltet.',
	'ACP_MOT_TZV_ADMIN_MESSAGE'		=> 'Administrator-Modus aktiv. Nur Administratoren können Tourziele sehen !',

	// Kartenfunktionen
	'ACP_MOT_TZV_MAPSETTING_TITLE'	=> 'Einstellungen für die Übersichtskarte',
	'ACP_MOT_TZV_MAPSETTING_TEXT'	=> 'Einstellungen für das Kartenzentrum und die Vergrößerung beim Start.',
	'ACP_MOT_TZV_LAT'				=> 'Geogr. Breite des Kartenzentrums',
	'ACP_MOT_TZV_LAT_EXP'			=> 'Werte zwischen 90.0° (Nordpol) und -90.0° (Südpol)',
	'ACP_MOT_TZV_LON'				=> 'Geogr. Länge des Kartenzentrums',
	'ACP_MOT_TZV_LON_EXP'			=> 'Werte zwischen 180.0° (Osten) und -180.0° (Westen)',
	'ACP_MOT_TZV_ZOOM'				=> 'Zoom-Faktor der Karte beim Aufruf',
	'ACP_MOT_TZV_MAP_CLUSTERS'		=> 'Marker bündeln',
	'ACP_MOT_TZV_MAP_CLUSTERS_EXP'	=> 'Wenn du diese Einstellung aktivierst, werden die Marker zur Darstellung auf der Karte gebündelt.
										Damit kann eine Überfrachtung der Karte mit Markern verhindert werden.',
	'ACP_MOT_TZV_MULTI_LAYERS'		=> 'Marker auf verschiedenen Overlays darstellen',
	'ACP_MOT_TZV_MULTI_LAYERS_EXP'	=> 'Wenn du diese Einstellung aktivierst, werden die Marker auf der Karte auf verschiedenen Overlays dargestellt.
										Die Anzahl der Overlays richtet sich nach der Anzahl der verwendeten Kategorien, sie sind nach diesen benannt.',

	'ACP_MOT_TZV_GOOGLE_FUNCTIONS'	=> 'Einstellungen für Karten der Detailanzeige',
	'ACP_MOT_TZV_MAPS_ENABLE'		=> 'Detailansicht Karte anzeigen',
	'ACP_MOT_TZV_MAPS_ENABLE_EXPL'	=> 'Schaltet alle Kartenfunktionen ein / aus',
	'ACP_MOT_TZV_MAPS_EXPLAIN'		=> 'Detailansicht Karte muss aktiviert sein',
	'ACP_MOT_TZV_KURVIGER_ENABLE'	=> 'GPS-Datenübergabe an www.kurviger.de',
	'ACP_MOT_TZV_KURVIGER_EXPL'		=> 'Übergibt eingetragene GPS-Daten direkt an Motorradroutenplaner',
	'ACP_MOT_TZV_KOMOOT_ENABLE'		=> 'GPS-Datenübergabe an www.komoot.de',
	'ACP_MOT_TZV_KOMOOT_EXPL'		=> 'Übergibt eingetragene GPS-Daten direkt an Routenplaner für Wanderer und Radfahrer',
	'ACP_MOT_TZV_GOOGLEMAP_ENABLE'	=> 'Karte Google Maps anzeigen',
	'ACP_MOT_TZV_OSMMAP_ENABLE'		=> 'Karte OpenStreetMap anzeigen',
	'ACP_MOT_TZV_MSG_GOOGLE_ON'		=> 'Kartenfunktion ist eingeschaltet',
	'ACP_MOT_TZV_MSG_GOOGLE_OFF'	=> 'Kartenfunktionen ist ausgeschaltet',
	'ACP_MOT_TZV_MAPS_WIDTH'		=> 'Detailkarte Karte Breite (Pixel)',
	'ACP_MOT_TZV_MAPS_HEIGHT'		=> 'Detailkarte Karte Höhe (Pixel)',
	'ACP_MOT_TZV_MAPS_ZOOM'			=> 'Detailkarte Karten Zoom',

	// Zusatzfunktionen
	'ACP_MOT_TZV_ADDIT_FUNCS'		=> 'Zusatzfunktionen Schalter',
	'ACP_MOT_TZV_STATS_ENABLE'		=> 'Anzahl Tourziele in Forum-Index (Statistik] anzeigen',
	'ACP_MOT_TZV_NEWS_ADD_ENABLE'	=> 'Neuester Eintrag in Forum-Index anzeigen',
	'ACP_MOT_TZV_COUNTRY_ENABLE'	=> 'Länder / Flaggen im Tourziele-Index anzeigen',
	'ACP_MOT_TZV_MAIN_IMAGE'		=> 'Bilder im Tourziele-Index anzeigen',
	'ACP_MOT_TZV_MAININFO'			=> 'Neue Features dieser Version anzeigen',
	'ACP_MOT_TZV_SUPPORT_ENABLE'	=> 'Tourziele Supportlink anzeigen',
	'ACP_MOT_TZV_SUPPORT_EXPL'		=> 'Siehe Hinweis Info / Support / Update',
	'ACP_MOT_TZV_SUPPORT_LINK'		=> 'Pfad zum Supportlink',
	'ACP_MOT_TZV_LATEST_TZ_VIEW'	=> 'Anzeige des neuesten Tourziels in der Tourziel Liste',
	'ACP_MOT_TZV_LIST_TZ_VIEW'		=> 'Anzeige der Tourziele in Tourziel Liste und Suche',
	'ACP_MOT_TZV_LIST_TZ_DETAIL'	=> 'detailliert',
	'ACP_MOT_TZV_LIST_TZ_SHORT'		=> 'als Tabelle',

	// Pagination-Einstellungen
	'ACP_MOT_TZV_PAGINATION'		=> 'Einstellungen für Paginierung',
	'ACP_MOT_TZV_ROWS_ACP'			=> 'Anzahl Zeilen pro Tabellenseite im Adm.-Bereich',
	'ACP_MOT_TZV_ROWS_FRONT'		=> 'Anzahl Zeilen pro Tabellenseite im Front-End',

	// ACP-Meldungen
	'ACP_MOT_TZV_CONFIG_SAVED'		=> 'Tourziele Einstellungen aktualisiert',
	'ACP_MOT_TZV_MSG_DELETED'		=> 'Dieser Eintrag wurde gelöscht.',
	'ACP_MOT_TZV_MSG_EDITED'		=> 'Eintrag wurde bearbeitet.',
	'ACP_MOT_TZV_MSG_ADDED'			=> 'Neuer Eintrag wurde hinzugefügt.',
	'ACP_MOT_TZV_ERROR_NO_NAME'		=> 'Du hast nichts eingegeben.',
	'ACP_MOT_TZV_ERROR_NOT_EXIST'	=> 'Der ausgewählte Eintrag existiert nicht.',
	'ACP_MOT_TZV_NAME_EXISTS'		=> 'Es gibt bereits einen Eintrag mit diesem Namen!',
	'ACP_MOT_TZV_NO_ENTRIES'		=> 'Keine Einträge',

	// ACP COUNTRY
	'ACP_MOT_TZV_DELETE'			=> '<b>Lösche hier keine Einträge wenn schon Tourziele mit diesen Daten eingetragen sind.<br>
										Das kann zu Fehlern in der Anzeige führen.<br>Tourziel wird dann nicht in der Liste angezeigt!</b>',
	'ACP_MOT_TZV_COUNTRIES'			=> 'Eingetragene Länder',
	'ACP_MOT_TZV_COUNTRY_NAME'		=> 'Land',
	'ACP_MOT_TZV_COUNTRY_IMG'		=> 'Flagge',
	'ACP_MOT_TZV_COUNTRY_COUNT'		=> 'Anzahl Tourziele',
	'ACP_MOT_TZV_COUNTRY_CONFIRM_DELETE'	=> 'Willst du das Land mit dem Namen <strong>%s</strong> wirklich löschen?',
	'ACP_MOT_TZV_COUNTRY_ADD'		=> 'Neues Land einfügen',
	'ACP_MOT_TZV_COUNTRY_EDIT'		=> 'Land bearbeiten',
	'ACP_MOT_TZV_COUNTRY_NAME_EXPL'	=> 'z.B. <b>Deutschland</b>',
	'ACP_MOT_TZV_COUNTRY_IMG_EXPL'	=> 'z.B. <b>de.png</b>',
	'ACP_MOT_TZV_COUNTRY_FLAGADDL'	=> 'Alle Flaggen im "png-Format". Gebe Ländercode.png ein, z.B. für Schweiz <b>ch.png</b> <br><br>',

	// ACP REGION
	'ACP_MOT_TZV_REGIONS'			=> 'Eingetragene Regionen',
	'ACP_MOT_TZV_REGION_NAME'		=> 'Region',
	'ACP_MOT_TZV_REGION_CONFIRM_DELETE'		=> 'Willst du die Region mit dem Namen <strong>%s</strong> wirklich löschen?',
	'ACP_MOT_TZV_REGION_ADD'		=> 'Neue Region einfügen',
	'ACP_MOT_TZV_REGION_EDIT'		=> 'Region bearbeiten',
	'ACP_MOT_TZV_REGION_NAME_EXPL'	=> 'z.B. <b>Freistaat Sachsen</b>',

	// ACP KATEGORIE
	'ACP_MOT_TZV_CATS'				=> 'Eingetragene Kategorien',
	'ACP_MOT_TZV_CATS_NAME'			=> 'Kategorie',
	'ACP_MOT_TZV_CAT_CONFIRM_DELETE'	=> 'Willst du die Kategorie mit dem Namen <strong>%s</strong> wirklich löschen?',
	'ACP_MOT_TZV_CAT_ADD'			=> 'Neue Kategorie einfügen',
	'ACP_MOT_TZV_CAT_EDIT'			=> 'Kategorie bearbeiten',
	'ACP_MOT_TZV_CAT_NAME_EXPL'		=> 'z.B. <b>Gaststätte</b>',

	// ACP WLAN
	'ACP_MOT_TZV_WLANS'				=> 'Eingetragene WLAN Optionen',
	'ACP_MOT_TZV_WLAN_NAME'			=> 'WLAN Option',
	'ACP_MOT_TZV_WLAN_CONFIRM_DELETE'	=> 'Willst du die WLAN Option mit dem Namen <strong>%s</strong> wirklich löschen?',
	'ACP_MOT_TZV_WLAN_ADD'			=> 'Neue WLAN Option einfügen',
	'ACP_MOT_TZV_WLAN_EDIT'			=> 'WLAN Option bearbeiten',
	'ACP_MOT_TZV_WLAN_NAME_EXPL'	=> 'z.B. <b>WLAN für Gäste offen</b>',

]);
