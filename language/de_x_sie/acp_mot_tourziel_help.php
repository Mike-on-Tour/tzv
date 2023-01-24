<?php
/**
*
* @package phpBB Extension [Tour destinations]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* @ acp language file (Deutsch / Sie)*
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
	// ACP TZV HILFE
	'ACP_MOT_TZV_INFO_TITLE'	=> 'Info Tourziele',
	'ACP_MOT_TZV_UPDATE'		=> '<strong>Vor Änderungen Einstellungen und Update grundsätzlich Datensicherung nicht vergessen !</strong>',
	'ACP_MOT_TZV_COPYRIGHT'		=> '&bull;&nbsp;copyright (c) 2014 - 2021 Autor: <b>waldkatze</b> (Kein Support mehr)<br>
									<span style="color: transparent;">&bull;</span>&nbsp;copyright (c) 2022 Autor: <b>Mike-on-Tour</b> (Kein Support per E-Mail oder PN)<br>',
	'ACP_MOT_TZV_SUPPORT'		=> '&bull;&nbsp;EXTENSION Demo / Download / Update <a href="https://www.mike-on-tour.com/" target="_blank">https://www.mike-on-tour.com/</a><br><br>
									&bull;&nbsp;License <a href="http://opensource.org/licenses/gpl-license.php"> http://opensource.org/licenses/gpl-license.php</a> GNU Public License<br>',

	'ACP_MOT_TZV_HISTORY'		=> 'Tourziele History',
	'ACP_MOT_TZV_HUPDATE'		=> '&bull;&nbsp;2022 Version 1.1.0<br>
									- Einbau eines Buttons, der zu einer Seite zum Suchen von Koordinaten anhand des Namns eines
										Zieles führt, um die Eingabe der Koordinaten beim Erstellen/Ändern eines Tourzieles zu erleichtern<br>
									- Ersatz der Radio-Buttons durch Slider auf der Einstellungsseite im Admin-Bereich<br>
									&bull;&nbsp;2022 Version 1.0.0<br>
									- Neues Sprachpaket Deutsch (Sie)<br>
									- Nutzung der Kategorie-Ebenen auch in der Karte mit den Suchergebnissen<br>
									&bull;&nbsp;2022 Version 0.6.0<br>
									- Anzeige der Tourziele in der Hauptkarte in verschiedenen Overlays, die nach den Kategorien sortiert sind<br>
									- Die Overlays können im Kontrollelement rechts oben in der Karte individuell aus- und angeschaltet werden<br>
									- Anzeige der Entfernungsskala auf den Karten in metrischen und imperialischen (Meilen) Maßeinheiten<br>
									&bull;&nbsp;2022 Version 0.5.0<br>
									- Suche in OSM (Nominatim) in die Karte eingefügt<br>
									- Datenübergabe an komoot.de zur Routenplanung für Wanderer und Radfahrer (öffnet in einem neuen Tab oder Fenster)<br>
									- komoot.de wird im Falle einer dort unterstützten Sprache in dieser aufgerufen (z.B. bei Nutzersprache Englisch in Englisch)<br>
									- Rechtsklick in die Karte öffnet Eingabefenster für neues Tourziel und übernimmt gleich die Koodinaten, auf die die Maus beim Rechtsklick zeigte<br>
									&bull;&nbsp;2022 Version 0.4.0<br>
									- Anzeige der Suchergebnisse zusätzlich zur Tabelle auf einer Karte<br>
									- Datenübergabe an kurviger.de öffnet jetzt in einem neuen Tab oder Fenster<br>
									- kurviger.de wird im Falle einer dort unterstützten Sprache in dieser aufgerufen (z.B. bei Nutzersprache Englisch in Englisch)<br>
									- Die Bilder auf der Tourziel Hauptseite sind nicht mehr fest vorgegeben, der Admin kann eigene Bilder verwenden<br>
									&bull;&nbsp;2022 Version 0.3.0<br>
									- Übersichtskarte eingefügt<br>
									- Reiter im UCP eingefügt, um dem jeweiligen Nutzer seine Tourziele zu präsentieren<br>
									- Anzeige der Tourziele in der Tourziel Liste und im Suchergebnis detailliert wie bisher oder kurz als Tabelle<br>
									- Benachrichtigung der Tourziel-Moderatoren bei neu erstellten, geänderten oder gelöschten Tourzielen<br>
									- Anzeige der OSM-Karte in der Detailanzeige korrigiert<br>
									&bull;&nbsp;2022 Version 0.2.1<br>
									- Verschiedene Bugfixes<br>
									- Löschen der Grafik-Dateien für die Länderflaggen<br>
									- Länderflaggen werden jetzt aus dem Internet bezogen<br>
									&bull;&nbsp;2022 Version 0.2.0<br>
									- Umfangreiche Anpassungen an Coding Guidelines<br>
									- Notwendige Anpassungen für PHP 8<br>
									- Umwandlung ACP Module in Controller<br>
									&bull;&nbsp;2021 Version 0.1.1<br>
									- Es kann jetzt zwischen Kartenansicht Google Maps und/oder OpenStreetMap gewählt werden. Einstellung im ACP.<br>
									- Neue Funktion: GPS-Daten können mit einem Klick in Tourenplaner www.kuviger.de übertragen werden.<br>
									- Responsive Ansicht für Handy.<br>
									- Im ACP wird jetzt bei Eingabe automatisch geprüft ob Land / Region / Kategorie mit gleichen Namen vorhanden ist.<br>
									- Bug Flaggenanzeige im ACP beseitigt.<br>
									&bull;&nbsp;2021 Version 0.1.0 als Extension für phpBB 3.3.x<br>
									&bull;&nbsp;2014 Erste Version als MOD für phpBB 3.0.x<br>',

	'ACP_MOT_TZV_HELP'			=> 'Tourziele - Hinweise zu Einstellungen<br><br>',
	'ACP_MOT_TZV_HELPLINE'		=> '<b>Einstellung Berechtigung</b><br>
									Die Berechtigungen für die Benutzerrollen ´<strong>Volle Funktionalität</strong>´ und ´<strong>Standard-Funktionalität</strong>´
									werden bei Installation auf vollen Zugriff voreingestellt. Für alle anderen Rollen setzen Sie die Rechte nach Ihren Wünschen.<br>
									Die Moderator-Berechtigungen werden bei der Installation der Rolle ´<strong>Umfassender Moderator</strong>´ zugewiesen. Alle anderen
									Moderator-Rollen können Sie nach Ihren Wünschen änpassen.<br><br>
									<b>Administrator-Modus</b><br>
									Wenn aktiviert, haben nur Admins Zugriff auf Tourziele. (Gedacht für Testzwecke)<br><br>
									<b>Einstellungen für die Übersichtskarte</b><br>
									Hier können Sie den Breiten- und Längengrad des Punktes eingeben, der im Zentrum der Übersichtskarte liegt.<br>
									Zusammen mit dem beim Aufruf der Karte anzuwendenden Zoom-Faktor können Sie bestimmen, welches Areal die Karte
									abdecken soll.<br>
									Wenn viele Marker auf der Karte gesetzt sind, können Sie diese abhängig vom Zoom in Gruppen zusammenfassen lassen.<br><br>
									<b>Einstellungen für Karten der Detailanzeige</b><br>
									Die Funktion ist auch komplett abschaltbar.<br>
									Auswahl zwischen Google-Maps und/oder Openstreetmap.<br>
									Größe und Zoom der Karten.<br><br>
									<b>Tourziele Supportlink anzeigen</b><br>
									Es wird Fragen von Ihren Usern zur Bedienung geben.<br>
									Richten Sie am besten ein eigenes Support-Thema in Ihrem Forum ein. Den Link dazu können Sie hier setzen (z.B. ´viewtopic.php?f=1&t=69´).<br><br>
									<b>Länder / Kategorie / Region / WLAN</b><br>
									Passen Sie diese Einstellungen an Ihre Wünsche an.<br>
									Die mitgelieferten Daten sind zum Testen gedacht, Sie können sie beliebig ändern und löschen (solange sie noch nicht für Tourziele verwendet wurden).<br>
									Beachten Sie ausdrücklich den Hinweis im roten Feld der Einstellungen !  ',
]);
