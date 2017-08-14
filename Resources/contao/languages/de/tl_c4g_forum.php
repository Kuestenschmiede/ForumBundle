<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * con4gis - the gis-kit
 *
 * @version   php 5
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['name'] 					= array('Name', 'Name des Forenbereiches');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_names']           = array('Optionale Bezeichnungen', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_name']            = array('Bezeichnung', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language']        = array('Sprache', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['tags'] 					= array('Tags', 'Schlagwort-Vorgabe zur Auswahl bei der Thread- / Post-Erstellung');
$GLOBALS['TL_LANG']['tl_c4g_forum']['mail_subscription_text'] 					= array('E-Mail-Vorlage für Benachrichtigung bei Änderungen', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['headline'] 				= array('Überschrift',
																		'Hier können Sie dem Forenbereich eine Überschrift hinzufügen.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headlines']       = array('Optionale Überschriften', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headline']        = array('Überschrift', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['description'] 				= array('Beschreibung',
																		'Die Beschreibung wird als Tooltip angezeigt.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_descriptions']    = array('Optionale Beschreibungen', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_description']     = array('Beschreibung', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['published'] 				= array('Veröffentlicht',
																		'Legt fest, ob der Forenbereich veröffentlicht wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['box_imagesrc'] 			= array('Bild für Kachel',
																		'Legt ein Bild fest, das auf der Kachel in der Kachelsicht angezeigt wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['use_intropage'] 			= array('Einstiegsseite verwenden',
																		'Wenn Sie eine Einstiegsseite verwenden, dann wird bevor der Forenbereich aufgerufen wird eine frei definierbare Seite unterhalb des Navigationspfades angezeigt.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage'] 				= array('Inhalt Einstiegsseite',
																		'Der Inhalt der Einstiegsseite in den Forenbereich.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn'] 		= array('Text für Button zum Forum',
																		'Wenn Sie hier einen Text eingeben, so wird unterhalb des Inhalts der Einstiegsseite ein Button erzeugt, der in den eigentlichen Forenbereich verzweigt.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn_jqui'] 	= array('Button zum Forum im jQuery UI Stil',
																		'Wählen Sie diese Option, damit kein einfacher Link, sondern ein Button im jQuery UI Stil angezeigt wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['pretext'] 					= array('Informationstext über der Themenliste bzw. Forenliste',
																		'Dieser Text wird der Themenliste bzw. Forenliste des aktuellen Forenbereiches vorangestellt.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['posttext'] 				= array('Informationstext unter der Themenliste bzw. Forenliste',
																		'Dieser Text wird unter der Themenliste bzw. Forenliste des aktuellen Forenbereiches angezeigt.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_groups'] 			= array('Mitgliedergruppen festlegen',
																		'Wählen Sie dieses Auswahlfeld, um dem Forenbereich Mitgliedergruppen zuzuweisen. Wenn Sie keine Mitgliedergruppen festlegen, dann gelten die Zuweisungen aus dem übergeordneten Forenbereich');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_groups'] 			= array('Mitglieder des Forenbereichs',
																		'Mitgliedergruppen des Forenbereichs, deren Mitglieder Berechtigungen als Forenmitglieder erhalten sollen.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_groups'] 			= array('Moderatoren des Forenbereichs',
																		'Mitgliedergruppen des Forenbereichs, deren Mitglieder Berechtigungen als Moderatoren erhalten sollen.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_rights'] 			= array('Berechtigungen festlegen',
																		'Wählen Sie dieses Auswahlfeld, um Gästen, Forenmitgliedern und Moderatoren Berechtigungen zuzuweisen. Wenn Sie keine Berechtigungen festlegen, dann gelten die Berechtigungen aus dem übergeordneten Forenbereich. Sind gar keine Berechtigungen definiert, dann greifen Standardberechtigungen.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['guest_rights'] 			= array('Berechtigungen für Gäste',
																		'Legen Sie fest, welche Aktionen ein Gast durchführen darf.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_rights'] 			= array('Berechtigungen für Forenmitglieder',
																		'Legen Sie fest, welche Aktionen ein Forenmitglied durchführen darf.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_rights'] 			= array('Berechtigungen für Moderatoren',
																		'Legen Sie fest, welche Aktionen ein Moderator durchführen darf.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['enable_maps'] 				= array('Kartenanbindung aktivieren (erfordert con4gis-Maps)',
																		'Bei Aktivierung dieses Schalters kann ein Mitglied mit entsprechenden Rechten Beiträge mit Geo-Koordinaten versehen, vorausgesetzt die Karten-Funktionalität wurde auch im Frontend-Modul aktiviert. Funktioniert nur, wenn die Contao-Erweiterung \'c4g_maps\' installiert ist! ');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_type'] 				= array('Lokationstyp',
																		'Legen Sie fest, welcher Typ von Lokationen im Forum angelegt werden kann.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locationstyle']  	= array('Überschreiben der Karten-Lokationsstile erlauben',
																		'Mit dieser Option erlauben sie die dem Benutzer mit der Erweiterung der PopUp-Information auch den Lokationsstil zu überschreiben.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locstyles']  	= array('Erlaubte Lokationsstile',
																		'Wählen Sie die Lokationsstile, die dem Benutzer zur Auswahl stehen. Standard: alle');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_id'] 					= array('Basiskarte',
																		'Legt eine zuvor in con4gis-Maps definierte Karte fest, die als Basis für den gewählten Editor in den Beiträgen und die Anzeige der Karte verwendet wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_location_label'] 		= array('Label Lokation',
																		'Wenn Sie hier ein Label festlegen, so ersetzt dieses den Text "Kartenlokation" im Frontend des Forums.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_label'] 				= array('Label',
																		'Definiert woher ein eventuell bei einer Lokation anzuzeigendes Label genommen wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_tooltip'] 				= array('Tooltip',
																		'Definiert woher ein eventuell bei einer Lokation anzuzeigendes Tooltip genommen wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_popup'] 				= array('Popup',
																		'Definiert woher der Inhalt eines eventuell bei Klick auf eine Lokation anzuzeigendes Popup genommen wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_link'] 				= array('Link',
																		'Definiert woher der Link kommt, der bei Klick auf eine Lokation angesprungen wird.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['linkurl'] 					= array('Direktlink auf andere Seite',
																		'Legen Sie hier einen Link fest, der bei der Auswahl des Forenbereichs angesprungen wird (Absprung aus dem Forum).');
$GLOBALS['TL_LANG']['tl_c4g_forum']['link_newwindow'] 			= array('Links in neuem Fenster öffnen',
																		'Links nicht im selben Fenster, sondern in einem neuen Fenster öffnen.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['sitemap_exclude'] 			= array('Nicht in XML-Sitemap aufnehmen',
																		'Den Forenbereich und die Themen nicht in die Google XML-Sitemap aufnehmen (die Sitemap wird im Frontend-Modul aktiviert)');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['general_legend'] 		= 'Allgemein';
$GLOBALS['TL_LANG']['tl_c4g_forum']['language_legend'] 		= 'Spracheinstellungen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['comfort_legend'] 		= 'Kachelsicht';
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_legend'] 	= 'Einstiegsseite für Kachelsicht';
$GLOBALS['TL_LANG']['tl_c4g_forum']['infotext_legend'] 		= 'Informationstexte';
$GLOBALS['TL_LANG']['tl_c4g_forum']['groups_legend']  		= 'Mitgliedergruppen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['rights_legend']  		= 'Berechtigungen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['expert_legend']  		= 'Experteneinstellungen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['maps_legend'] 			= 'Kartenanbindung (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['additional_legend'] 			= 'Zusätzliche Informationen';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['new']    		= array('Neuer Forenbereich', 'Einen neuen Forenbereich erstellen');
$GLOBALS['TL_LANG']['tl_c4g_forum']['edit']   		= array('Forenbereich bearbeiten', 'Forenbereich ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copy']   		= array('Forenbereich duplizieren', 'Forenbereich ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copyChilds']   = array('Forenbereich inklusive Unterbereiche duplizieren', 'Forenbereich ID %s inklusive Unterbereiche duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_forum']['cut']    		= array('Forenbereich verschieben', 'Forenbereich ID %s verschieben');
$GLOBALS['TL_LANG']['tl_c4g_forum']['delete'] 		= array('Forenbereich löschen', 'Forenbereich ID %s löschen');
$GLOBALS['TL_LANG']['tl_c4g_forum']['toggle'] 		= array('Forenbereich veröffentlichen/unveröffentlichen', 'Forenbereich ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_c4g_forum']['show']   		= array('Details', 'Die Details des Forenbereichs ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_c4g_forum']['index']   		= array('Indizieren', 'Indiziert das komplette Forum');

/**
 * Links
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['build_index'] 	= array('Volltext-Indizierung', 'Einstellungen zum Volltextindex');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OFF']    = 'Aus';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUBJ']   = 'Betreff';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['CUST']   = 'Definierbar am Beitrag';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['POST']   = 'Beitrag';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUPO']   = 'Betreff + Beitrag';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['THREA']  = 'Thema';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PLINK']  = 'Beitragslink';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['LINK']   = 'Bezeichnung Beitragslink';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  = 'Popup erweitern (BETA)';
// $GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  = 'Popup erweitern (mit ID(OSM)-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PICK']   = 'Einzelner Punkt (mit GEO-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['EDIT']   = 'Punkte, Linien, Flächen (mit Editor)';

/**
 * Forum Rights
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_visible']        = 'Forenbereich sichtbar';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadlist'] 	= 'Themenliste';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_readpost'] 		= 'Beiträge lesen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newpost'] 		= 'Neuen Beitrag im Thema erstellen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newthread'] 		= 'Neues Thema anlegen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_postlink']		= 'Link in Beiträgen erzeugen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadsort']		= 'Themensortierung';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threaddesc']		= 'Themenbeschreibung';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownpost']	= 'Eigenen Beitrag bearbeiten';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editpost']		= 'Beitrag bearbeiten';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownthread']	= 'Eigenes Thema bearbeiten';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editthread']		= 'Thema bearbeiten';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delownpost']		= 'Eigenen Beitrag löschen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delpost']		= 'Beitrag löschen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delthread'] 		= 'Thema löschen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_movethread'] 	= 'Thema verschieben';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribethread']= 'Thema abonnieren';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribeforum'] = 'Forenbereich abonnieren';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_addmember'] 		= 'Forenmitglieder hinzufügen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapview'] 		= 'Karten anzeigen (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 		= 'Kartendaten editieren (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapextend'] 		= 'Kartendaten erweitern (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit_style'] 	= 'Kartendaten editieren: Lokationsstil';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_search'] 		= 'Suchen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_latestthreads']	= 'Neue Themen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_alllanguages']	= 'Alle Sprachen editieren (Mehrsprachigkeit)';

/**
 * Fulltext Indexing Configuration Texts
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['headline_index']  	= array('Volltext-Indizierung',
																'Informationen zur Volltextindizierung und Erstellung des Index.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['info_headline']   	= "Informationen zur Volltext-Indizierung";
$GLOBALS['TL_LANG']['tl_c4g_forum']['info']   	 	  	= array('Erste Indizierung',
																'Letzte Totalindizierung',
																'Letzte Indizierung',
																'Anzahl indizierter Wörter');
$GLOBALS['TL_LANG']['tl_c4g_forum']['noIndex']    	    = "Es wurde noch keine Indizierung durchgeführt! Ohne eine Indizierung funktioniert Ihre Suche nicht!";
$GLOBALS['TL_LANG']['tl_c4g_forum']['warning']     		= array("Eine komplette Indizierung kann einige Zeit in Anspruch nehmen. Dies ist stark vom Inhalt des Forums abhängig.",
																"Bitte haben Sie etwas Geduld und verlassen Sie diese Seite während des Vorgangs nicht!");
$GLOBALS['TL_LANG']['tl_c4g_forum']['success']			= "Ihr Forum wurde erfolgreich indiziert.";
$GLOBALS['TL_LANG']['tl_c4g_forum']['fail']				= array("FEHLER: ",
																"Bei der Indizierung kam es zu einer Zeitüberschreitung!");




    $GLOBALS['TL_LANG']['tl_c4g_forum']['default_subscription_text'] = <<<TEXT
Hallo ##USERNAME##,

das Mitglied '##RESPONSIBLE_USERNAME##' hat in Ihrem abonnierten Forenbereich '##FORUMNAME##' im Thema '##THREADNAME##' ##ACTION_PRE## ##ACTION_NAME_WITH_SUBJECT##


##POST_CONTENT##


Öffnen Sie den Forenbereich über den folgenden Link:
##DETAILS_LINK##

__________________________________________________________________________________________

Um das Forenbereichs-Abonnement abzubestellen verwenden Sie bitte diesen Link:
##UNSUBSCRIBE_LINK##

Um alle Abonnements abzubestellen verwenden Sie bitte diesen Link:
##UNSUBSCRIBE_ALL_LINK##
TEXT;

?>