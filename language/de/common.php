<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze - http://www.polarbiker-oberlausitz.de/mod_demo/
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

	// Titel
	'MOT_TZV_TOURZIEL'					=> 'Tourziele',
	'MOT_TZV_TOURZIEL_MAIN'				=> 'Motorrad &bull;&nbsp;Tourziele Datenbank',

	// Forum Index
	'MOT_TZV_NEW_EVENT'					=> 'Neuester Tourziel Eintrag',
	'MOT_TZV_NEW_FLAG'					=> 'Flagge',
	'MOT_TZV_NEW_LAND'					=> 'Land',
	'MOT_TZV_NEW_REGION'				=> 'Region',
	'MOT_TZV_NEW_TIME'					=> 'Eingetragen am',
	'MOT_TZV_NEW_AUTHOR'				=> 'Eintrag von',
	'MOT_TZV_NO_NEW_EVENT'				=> 'Kein Eintrag gefunden.',
	'MOT_TZV_TOURZIEL_TOTAL'			=> '&bull;&nbsp;Eingetragene Tourziele insgesamt',

	// [Buttons - Links / Titel Texte]
	'MOT_TZV_MAIN_INDEX'				=> 'Tourziel Home',
	'MOT_TZV_MAIN_ADD'					=> 'Neuer Eintrag',
	'MOT_TZV_MAIN_VIEW'					=> 'Tourziel Liste',
	'MOT_TZV_MAIN_VIEW_NEW'				=> 'Neuester Eintrag',
	'MOT_TZV_MAIN_MAPS'					=> 'Tourziel Karte',	// aktuell unbenutzt
	'MOT_TZV_MAIN_SEARCH'				=> 'Tourziel Suche',
	'MOT_TZV_MAIN_SUPPORT'				=> 'Tourziel Support',
	'MOT_TZV_SUPPORT_FORUM'				=> 'Support Forum',

	'MOT_TZV_RETURN_TOURZIEL'			=> 'Zurück zum Index',

	// PAGINATION
	'MOT_TZV_POSTS_COUNT'				=> [
		0	=> 'kein Eintrag',
		1	=> '%d Eintrag',
		2	=> '%d Einträge',
	],

	// [Detailansicht]
	'MOT_TZV_TOURZIEL_DETAIL'			=> 'Detailansicht',
	'MOT_TZV_TOURZIEL_DETAIL_CLICK'		=> 'Klicke hier für Detailansicht',
	'MOT_TZV_TOURZIEL_USER_TIPP'		=> 'Hinweis zum Tourziel',
	'MOT_TZV_TOURZIEL_PLZ_ORT'			=> 'Ort',
	'MOT_TZV_TOURZIEL_STRASSE_NR'		=> 'Straße',
	'MOT_TZV_TOURZIEL_GPS_DAT'			=> 'GPS',
	'MOT_TZV_DATE_ADD_EDIT'				=> '<br><b>Eingetragen am</b>',
	'MOT_TZV_OPENSTREETMAP_INFO'		=> 'Klick unten auf <strong>Größere Karte anzeigen</strong>.<br><br>',
	'MOT_TZV_KURVIGER_INFO'				=> 'Übergabe der GPS-Daten an den Motorradtourenplaner https://kurviger.de',
	'MOT_TZV_KURVIGER_ADD'				=> 'Datenübergabe',

	// [Moderator-Funktion]
	'MOT_TZV_TOURZIEL_MODERATE'			=> 'Tourziel Moderator',
	'MOT_TZV_LAST_5_EVENTS'				=> 'Neueste 5 Einträge',
	'MOT_TZV_NO_EVENTS'					=> 'Kein Eintrag gefunden',

	// [Index]
	'MOT_TZV_COUNT_TOTAL_DEST'			=> [
		0	=> 'Es ist <strong>kein</strong> Tourziel in der Datenbank.',
		1	=> 'Insgesamt <strong>%1$d</strong> Tourziel in der Datenbank.',
		2	=> 'Insgesamt <strong>%1$d</strong> Tourziele in der Datenbank.',
	],
	'MOT_TZV_COUNTRY_EINTRAG'			=> 'Eingetragene Länder',
	'MOT_TZV_MAINNEWS_INFO'				=> '&bull;&nbsp;&nbsp;Erweiterung komplett überarbeitet.<br>
											&bull;&nbsp;&nbsp;Datenausgabe auch mit Karte.<br>
											&bull;&nbsp;&nbsp;Routenplanung zum Tourziel.<br>
											&bull;&nbsp;&nbsp;Ansicht mit GOOGLE-Streetview.<br>',
	'MOT_TZV_NUTZUNG_MAPS'				=> 'Nutzungsbedingungen Google Maps',

	// [Eintragen / Ändern]
	'MOT_TZV_HWTEXT_ADD'				=> '&bull;&nbsp;&nbsp;Mit der Suchfunktion kannst du vor neuen Eintrag prüfen, ob das Ziel schon in der Datenbank vorhanden ist.<br>
											&bull;&nbsp;&nbsp;Deine Eingaben <b>müssen nicht vollständig sein.</b> Du kannst <b>deine Eingaben</b> jederzeit ändern oder ergänzen.<br>
											&bull;&nbsp;&nbsp;<b>Tourziel / PLZ  / Ort / Text zum Tourziel</b> sind <b>immer Pflichtfelder !</b>',

	'MOT_TZV_HWTEXT_EDIT'				=> '&bull;&nbsp;&nbsp; <b>Hinweis Daten ändern.</b><br>
											&bull;&nbsp;&nbsp; Bei Daten ändern <b>müssen ggf. auch die Select-Boxen neu</b> ausgewählt werden !<br>
											&bull;&nbsp;&nbsp;<b>Tourziel / PLZ  / Ort / Text zum Tourziel</b> sind <b>immer Pflichtfelder !</b>',

	'MOT_TZV_HWTEXT_GPS'				=> 'Kartenfunktionen sind aktiviert, <strong>Koordinaten zum Tourziel sind ebenfalls Pflichtfelder !</strong><br>
											&bull;&nbsp;&nbsp; GPS-Daten immer mit <b>Punkt</b>, nicht mit <b>Komma</b> eintragen ! <br>
											&bull;&nbsp;&nbsp; Beispiel richtig: <b>51.055257</b> | Beispiel falsch: <b>51,055257</b><br>
											&bull;&nbsp;&nbsp; Die Karte kann nur mit korrekten GPS-Daten angezeigt werden.',

	'MOT_TZV_HWTEXT_SEND'				=> '&bull;&nbsp;&nbsp; <b>Prüfe vor Absenden noch einmal alle Eingaben.</b>',
	'MOT_TZV_TOURZIEL_INVALID'			=> 'Ein Tourziel-Eintrag mit diesem Namen existiert bereits! Alle Eingaben wurden verworfen!',

	// Select-Boxen
	'MOT_TZV_AUSWAHL'					=> 'Bitte auswählen',
	'MOT_TZV_EDIT_AUSWAHL'				=> 'Bei Ändern ggf. neu auswählen !',

	// Error eintragen / ändern
	'MOT_TZV_ERROR'						=> 'Fehler!',
	'MOT_TZV_ERROR_NAME'				=> 'Kein Tourziel-Name eingetragen!',
	'MOT_TZV_ERROR_POSTALCODEZ'			=> 'Keine PLZ eingetragen!',
	'MOT_TZV_ERROR_CITY'				=> 'Kein Ort eingetragen!',
	'MOT_TZV_ERROR_LAT'					=> 'Breitengrad darf nicht leer oder Null sein!',
	'MOT_TZV_ERROR_LON'					=> 'Längengrad darf nicht leer oder Null sein!',
	'MOT_TZV_ERROR_MESSAGE'				=> 'Kein Text zum Tourziel eingetragen!',

	'MOT_TZV_MAIN_EDIT'					=> 'Eintrag ändern',
	'MOT_TZV_LISTEN_ID'					=> '<b>ID</b>',
	'MOT_TZV_LISTEN_NAME'				=> 'Tourziel',
	'MOT_TZV_LISTEN_LAND'				=> 'Land',
	'MOT_TZV_LISTEN_CATEGORY'			=> 'Kategorie',
	'MOT_TZV_LISTEN_WLAN'				=> 'WLAN',
	'MOT_TZV_LISTEN_REGION'				=> 'Region',
	'MOT_TZV_LISTEN_PLZ'				=> 'PLZ',
	'MOT_TZV_LISTEN_ORT'				=> 'Ort',
	'MOT_TZV_LISTEN_STRASSE'			=> 'Straße / Nr',
	'MOT_TZV_LISTEN_TELEFON'			=> 'Telefon',
	'MOT_TZV_LISTEN_EMAIL'				=> 'E-Mail',
	'MOT_TZV_LISTEN_HOMEPAGE'			=> 'Homepage',
	'MOT_TZV_LISTEN_HOMEPAGE_HINT'		=> 'Bitte komplette Internet-Adresse eintragen (z.B.: http(s)://www.seite.de)',
	'MOT_TZV_LISTEN_GPS'				=> 'GPS',
	'MOT_TZV_LISTEN_MAPS_LAT'			=> 'GPS Breitengrad',
	'MOT_TZV_LISTEN_MAPS_LON'			=> 'GPS Längengrad',
	'MOT_TZV_LISTEN_USER'				=> 'Eintrag von',
	'MOT_TZV_MESSAGE_INFO'				=> '<br><b>Text zum Tourziel</b>',

	// Messages
	'MOT_TZV_EVENT_ADD_SUCCESSFUL'		=> 'Neues Tourziel erfolgreich eingetragen.',
	'MOT_TZV_EVENT_EDIT_SUCCESSFUL'		=> 'Tourziel erfolgreich geändert.',
	'MOT_TZV_EVENT_DELETE_SUCCESSFUL'	=> 'Tourziel erfolgreich gelöscht.',
	'MOT_TZV_EVENT_NOT_DELETED'			=> 'Tourziel nicht gelöscht.',

	'MOT_TZV_RETURN_EVENT'				=> 'Zurück zum Tourziel',
	'MOT_TZV_VIEW_EVENT'				=> 'Zur Detailanzeige',
	'MOT_TZV_VIEW_EVENT_EDIT'			=> 'Ereignis anzeigen / bearbeiten',
	'MOT_TZV_EVENT_DELETE_CONFIRM'		=> 'Möchtest du den Eintrag mit dem Namen <strong>%s</strong> wirklich löschen ?',

	// [Suchfunktion]
	'MOT_TZV_SEARCH_SELECT'				=> 'Suche nach',
	'MOT_TZV_SEARCH_AUSWAHL'			=> 'Wähle ein Feld aus und klicke Button Suchen.',
	'MOT_TZV_SEARCH_TEXT'				=> 'Suchbegriff, PLZ oder Ort eingeben. Klick Button Suchen.',
	'MOT_TZV_SEARCH_TOURZIEL'			=> 'Suche nach Tourziel',
	'MOT_TZV_SEARCH_PLZ'				=> 'Suche nach PLZ',
	'MOT_TZV_SEARCH_ORT'				=> 'Suche nach Ort',
	'MOT_TZV_BUTTON_SUCHEN'				=> 'Suchen',
	'MOT_TZV_SEARCH_FOUND'				=> '<b>Anzahl Einträge gefunden</b>',

	// Meldungen Berechtigung
	'MOT_TZV_TOURZIEL_NO_ADD'			=> 'Du bist nicht berechtigt Tourziele einzutragen !',
	'MOT_TZV_TOURZIEL_NO_EDIT'			=> 'Du bist nicht berechtigt Tourziele zu ändern !',
	'MOT_TZV_TOURZIEL_NO_VIEW'			=> 'Du bist nicht berechtigt Tourziele zu sehen !',
	'MOT_TZV_NO_ENTRIES'				=> 'Keine Einträge',

	// TZV-FOOTER
	'MOT_TZV_FOOTER'					=> 'phpBB Extension <b>Tourzielverwaltung</b> &copy; <a href="https://www.mike-on-tour.com" target="_blank" rel="noopener">Mike-on-Tour</a> ',
]);
