<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* @language file (Deutsch / Sie)
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » „ “ — …
//

$lang = array_merge($lang, [

	// Titel
	'MOT_TZV_TOURZIEL'					=> 'Tourziele',

	// Tabs
	'MOT_TZV_TAB_INDEX'					=> 'Übersicht',
	'MOT_TZV_TAB_LIST'					=> 'Liste',
	'MOT_TZV_TAB_MAP'					=> 'Karte',
	'MOT_TZV_TAB_SEARCH'				=> 'Suche',
	'MOT_TZV_TAB_CREATE'				=> 'Erstellen',

	// Forum Index
	'MOT_TZV_NEW_EVENT'					=> 'Neuester/zuletzt geänderter Tourziel Eintrag',
	'MOT_TZV_NEW_FLAG'					=> 'Flagge',
	'MOT_TZV_NEW_LAND'					=> 'Land',
	'MOT_TZV_NEW_REGION'				=> 'Region',
	'MOT_TZV_NEW_TIME'					=> 'Eingetragen am',
	'MOT_TZV_NEW_AUTHOR'				=> 'Eintrag von',
	'MOT_TZV_NO_NEW_EVENT'				=> 'Kein Eintrag gefunden.',
	'MOT_TZV_TOURZIEL_TOTAL'			=> '&bull;&nbsp;Eingetragene Tourziele insgesamt',

	// Buttons - Links / Titel Texte
	'MOT_TZV_MAIN_ADD'					=> 'Neuer Eintrag',
	'MOT_TZV_MAIN_VIEW'					=> 'Tourziel Liste',
	'MOT_TZV_MAIN_VIEW_NEW'				=> 'Neuester/zuletzt geänderter Eintrag',
	'MOT_TZV_MAIN_MAP'					=> 'Tourziel Karte',
	'MOT_TZV_MAIN_SEARCH'				=> 'Tourziel Suche',
	'MOT_TZV_MAIN_SUPPORT'				=> 'Tourziel Support',
	'MOT_TZV_SUPPORT_FORUM'				=> 'Support Forum',
	'MOT_TZV_MAIN_GPSTIPP'				=> 'Gebe auf der folgenden Seite das Tourziel ein. z.B. <strong>Landgasthof Börnchen</strong>',
	'MOT_TZV_MAIN_ADDGPS'				=> 'Koordinaten finden',

	'MOT_TZV_RETURN_TOURZIEL'			=> 'Zurück zum Index',

	// Pagination
	'MOT_TZV_POSTS_COUNT'				=> [
		0	=> 'kein Eintrag',
		1	=> '%d Eintrag',
		2	=> '%d Einträge',
	],

	// Index tab
	'MOT_TZV_TOURZIEL_MAIN'				=> 'Tourziele Datenbank',
	'MOT_TZV_COUNT_TOTAL_DEST'			=> [
		0	=> 'Es ist <strong>kein</strong> Tourziel in der Datenbank.',
		1	=> 'Insgesamt <strong>%1$d</strong> Tourziel in der Datenbank.',
		2	=> 'Insgesamt <strong>%1$d</strong> Tourziele in der Datenbank.',
	],
	'MOT_TZV_COUNTRY_EINTRAG'			=> 'Eingetragene Länder',
	'MOT_TZV_NEWS_TITLE'				=> 'Neu in dieser Version',
	'MOT_TZV_MAINNEWS_INFO'				=> '&bull;&nbsp;&nbsp;Komplettüberarbeitung mit Umstellung auf Tabs im Frontend<br>
											&bull;&nbsp;&nbsp;Umfangreiche Code-Verbesserungen<br>
											&bull;&nbsp;&nbsp;Vereinheitlichung der verschiedenen Darstellungen der Tourziele<br>
											&bull;&nbsp;&nbsp;Ändern der maximal möglichen Versionen (phpBB < 3.4.0@dev und PHP < 8.5.0@dev)<br>',
	'MOT_TZV_NUTZUNG_MAPS'				=> 'Nutzungsbedingungen Google Maps',

	// Detailansicht
	'MOT_TZV_TOURZIEL_DETAIL'			=> 'Detailansicht',
	'MOT_TZV_TOURZIEL_DETAIL_CLICK'		=> 'Klicken Sie hier für Detailansicht',
	'MOT_TZV_TOURZIEL_STRASSE_NR'		=> 'Straße / Nr',
	'MOT_TZV_DATE_ADD_EDIT'				=> 'Eingetragen am',
	'MOT_TZV_KURVIGER_INFO'				=> 'Übergabe der Koordinaten an den Motorradtourenplaner https://kurviger.com',
	'MOT_TZV_KOMOOT_INFO'				=> 'Übergabe der Koordinaten an den Routenplaner für Wanderer und Radfahrer https://www.komoot.com',
	'MOT_TZV_DATA_HANDOVER'				=> 'Datenübergabe',

	// Map
	'MOT_TZV_STREET_DESC'				=> 'Straßenkarte',
	'MOT_TZV_TOPO_DESC'					=> 'Topografische Karte',
	'MOT_TZV_SAT_DESC'					=> 'Satellitenbild',
	'MOT_TZV_MAP_LEGEND_TEXT'			=> 'Zoomen der Karte mit dem Mausrad mit einem Links-Klick in die Karte ein- und ausschalten.<br>
											Erstellen eines neuen Tourzieles mit Rechts-Klick auf dessen Kartenposition starten.<br>
											<i>Klicken auf einen Marker öffnet die Detailansicht in einem neuen Browser-Tab.</i>',
	'MOT_TZV_MARKER_COUNT'				=> [
		0	=> 'Von den vorhandenen Tourzielen wird kein Tourziel in der Karte angezeigt.',
		1	=> 'Von den vorhandenen Tourzielen wird %1$d Tourziel in der Karte angezeigt.',
		2	=> 'Von den vorhandenen Tourzielen werden %1$d Tourziele in der Karte angezeigt.',
	],
	'MOT_TZV_MAP_LANG'					=> 'de',	// set according to this language, USE LOWERCASE LETTERS ONLY!!!
	'MOT_TZV_OSM_LARGER_MAP'			=> 'Größere Karte anzeigen',

	// Eintragen / Ändern
	'MOT_TZV_HWTEXT_ADD'				=> '&bull;&nbsp;&nbsp;Mit der Suchfunktion können Sie vor einem neuen Eintrag prüfen, ob das Ziel schon in der Datenbank vorhanden ist.<br>
											&bull;&nbsp;&nbsp;Ihre Eingaben <strong>müssen nicht vollständig sein.</strong> Sie können <strong>Ihre Eingaben</strong> jederzeit ändern oder ergänzen.',

	'MOT_TZV_HWTEXT_EDIT'				=> '&bull;&nbsp;&nbsp;<strong>Hinweis Daten ändern.</strong><br>
											&bull;&nbsp;&nbsp;Bei Daten ändern <strong>müssen ggf. auch die Select-Boxen neu</strong> ausgewählt werden!',

	'MOT_TZV_HWTEXT_MANDATORY_FIELDS'	=> '&bull;&nbsp;&nbsp;<strong>Pflichtfelder</strong> sind mit einem <span style="color: red;">*</span> gekennzeichnet, in ihnen
											<strong>muss immer</strong> etwas eingetragen/ausgewählt werden!',

	'MOT_TZV_HWTEXT_GPS'				=> '&bull;&nbsp;&nbsp; Koordinaten immer mit <strong>Punkt</strong>, nicht mit <strong>Komma</strong> eintragen!<br>
											&bull;&nbsp;&nbsp; Beispiel richtig: <strong>51.055257</strong> | Beispiel falsch: <strong>51,055257</strong><br>
											&bull;&nbsp;&nbsp; Das Tourziel kann nur mit korrekten Koordinaten auf der Karte angezeigt werden.',

	'MOT_TZV_HWTEXT_SEND'				=> '&bull;&nbsp;&nbsp; <strong>Prüfen Sie vor Absenden noch einmal alle Eingaben.</strong>',
	'MOT_TZV_TOURZIEL_INVALID'			=> 'Ein Tourziel-Eintrag mit diesem Namen existiert bereits! Alle Eingaben wurden verworfen!',
	'MOT_TZV_GENERAL_ERROR'				=> 'Fehler beim Speichern des Tourzieles:<br><strong>%1$s</strong><br>',

	// Select-Boxen
	'MOT_TZV_AUSWAHL'					=> 'Bitte auswählen',
	'MOT_TZV_EDIT_AUSWAHL'				=> 'Bei Ändern ggf. neu auswählen !',

	// Error eintragen / ändern
	'MOT_TZV_ERROR'						=> 'Fehler!',
	'MOT_TZV_ERROR_NAME'				=> 'Kein Tourziel-Name eingetragen!',
	'MOT_TZV_ERROR_COUNTRY'				=> 'Kein Land ausgewählt!',
	'MOT_TZV_ERROR_REGION'				=> 'Keine Region ausgewählt!',
	'MOT_TZV_ERROR_CATEGORY'			=> 'Keine Kategorie ausgewählt!',
	'MOT_TZV_ERROR_POSTALCODEZ'			=> 'Keine PLZ eingetragen!',
	'MOT_TZV_ERROR_CITY'				=> 'Kein Ort eingetragen!',
	'MOT_TZV_ERROR_STREET'				=> 'Keine Straße/Nr. eingetragen!',
	'MOT_TZV_ERROR_TELEPHONE'			=> 'Keine Telefon-Nr. eingetragen!',
	'MOT_TZV_ERROR_EMAIL'				=> 'Keine E-Mail-Adresse eingetragen!',
	'MOT_TZV_ERROR_HOMEPAGE'			=> 'Keine Homepage eingetragen!',
	'MOT_TZV_ERROR_LAT'					=> 'Breitengrad darf nicht leer oder Null sein!',
	'MOT_TZV_ERROR_LON'					=> 'Längengrad darf nicht leer oder Null sein!',
	'MOT_TZV_ERROR_WLAN'				=> 'Keine WLAN-Angabe ausgewählt!',
	'MOT_TZV_ERROR_MESSAGE'				=> 'Kein Text zum Tourziel eingetragen!',

	'MOT_TZV_MAIN_EDIT'					=> 'Eintrag ändern',
	'MOT_TZV_LISTEN_MANDATORY'			=> ' <span style="color: red;">*</span>',
	'MOT_TZV_LISTEN_ID'					=> '<strong>ID</strong>',
	'MOT_TZV_LISTEN_NAME'				=> 'Tourziel',
	'MOT_TZV_LISTEN_LAND'				=> 'Land',
	'MOT_TZV_LISTEN_REGION'				=> 'Region',
	'MOT_TZV_LISTEN_CATEGORY'			=> 'Kategorie',
	'MOT_TZV_LISTEN_WLAN'				=> 'WLAN',
	'MOT_TZV_LISTEN_PLZ'				=> 'PLZ',
	'MOT_TZV_LISTEN_ORT'				=> 'Ort',
	'MOT_TZV_LISTEN_STRASSE'			=> 'Straße / Nr',
	'MOT_TZV_LISTEN_TELEFON'			=> 'Telefon',
	'MOT_TZV_LISTEN_EMAIL'				=> 'E-Mail',
	'MOT_TZV_LISTEN_HOMEPAGE'			=> 'Homepage',
	'MOT_TZV_LISTEN_HOMEPAGE_HINT'		=> 'Bitte komplette Internet-Adresse eintragen (z.B.: http(s)://www.seite.de)',
	'MOT_TZV_LISTEN_GPS'				=> 'Koordinaten',
	'MOT_TZV_LISTEN_MAPS_LAT'			=> 'Breitengrad',
	'MOT_TZV_LISTEN_MAPS_LON'			=> 'Längengrad',
	'MOT_TZV_LISTEN_USER'				=> 'Eintrag von',
	'MOT_TZV_MESSAGE_INFO'				=> 'Text zum Tourziel',

	// Messages
	'MOT_TZV_EVENT_ADD_SUCCESSFUL'		=> 'Neues Tourziel erfolgreich eingetragen.',
	'MOT_TZV_EVENT_EDIT_SUCCESSFUL'		=> 'Tourziel erfolgreich geändert.',
	'MOT_TZV_EVENT_DELETE_SUCCESSFUL'	=> 'Tourziel erfolgreich gelöscht.',
	'MOT_TZV_EVENT_NOT_DELETED'			=> 'Tourziel nicht gelöscht.',

	'MOT_TZV_RETURN_EVENT'				=> 'Zurück zum Tourziel',
	'MOT_TZV_VIEW_EVENT'				=> 'Zur Detailanzeige',
	'MOT_TZV_DETAIL_VIEW_LINK'			=> 'Für Detailansicht auf den Namen des Tourzieles klicken',
	'MOT_TZV_EVENT_DELETE_CONFIRM'		=> 'Möchten Sie den Eintrag mit dem Namen <strong>%s</strong> wirklich löschen?',

	// Suchfunktion
	'MOT_TZV_SEARCH_EXPLANATION'		=> 'Wählen Sie in einem oder mehreren Feldern aus und/oder geben den Namen des Tourzieles und/oder des Ortes ein und klicken dann den Button
											`Suche starten`.<br>
											Die Texteingabe kann den Namen des Tourzieles bzw. des Ortes komplett oder nur teilweise enthalten, die Eingabe kann komplett in
											Kleinbuchstaben erfolgen.<br>
											Bitte beachten Sie, dass die Suche mit mehreren Suchparametern Oder-verknüpft ist, d.h. dass das Ergebnis die übereinstimmenden Funde für
											alle Kriterien beinhaltet! So wird z.B. eine Suche nach Tourzielen, die in einem bestimmten Land liegen bzw. zu einer bestimmten
											Kategorie gehören, alle Tourziele finden, die diese Bedingungen erfüllen.',
	'MOT_TZV_SEARCH_TOURZIEL'			=> 'Suche nach Tourziel',
	'MOT_TZV_SEARCH_COUNTRY'			=> 'Suche nach Land',
	'MOT_TZV_SEARCH_REGION'				=> 'Suche nach Region',
	'MOT_TZV_SEARCH_CATEGORY'			=> 'Suche nach Kategorie',
	'MOT_TZV_SEARCH_ORT'				=> 'Suche nach Ort',
	'MOT_TZV_BUTTON_SEARCH'				=> 'Suche starten',
	'MOT_TZV_SEARCH_FOUND'				=> '<strong>Anzahl Einträge gefunden</strong>',

	// Meldungen Berechtigung
	'MOT_TZV_TOURZIEL_NO_ADD'			=> 'Sie sind nicht berechtigt Tourziele einzutragen!',
	'MOT_TZV_TOURZIEL_NO_EDIT'			=> 'Sie sind nicht berechtigt Tourziele zu ändern!',
	'MOT_TZV_TOURZIEL_NO_VIEW'			=> 'Sie sind nicht berechtigt Tourziele zu sehen!',
	'MOT_TZV_NO_ENTRIES'				=> 'Keine Einträge',
	'MOT_TZV_NO_SUCH_ITEM'				=> 'Das gewünschte Tourziel existiert nicht!',

	// Notifications
	'MOT_TZV_NOTIFY_NEW_TZ'				=> '<strong>Neues Tourziel erstellt</strong><br>Das Mitglied „%2$s“ hat ein neues Tourziel mit dem Namen „<strong>%1$s</strong>“ erstellt.',
	'MOT_TZV_NOTIFY_TZ_EDITED'			=> '<strong>Tourziel geändert</strong><br>Das Mitglied „%2$s“ hat das Tourziel mit dem Namen „<strong>%1$s</strong>“ geändert.',
	'MOT_TZV_NOTIFY_TZ_DELETED'			=> '<strong>Tourziel gelöscht</strong><br>Das Mitglied „%2$s“ hat das Tourziel mit dem Namen „<strong>%1$s</strong>“ gelöscht.',
]);
