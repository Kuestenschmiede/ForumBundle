<?php if (!defined('TL_ROOT')) {
    die('You cannot access this file directly!');
}

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */

    /**
     * Fields
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_type']              = array(
        'Forenart',
        'Die Forenart beeinflusst die Beschriftungen. Bei Informationen werden Themen zu Fragen und Beiträge zu Kommentaren. Ticketsystem verändert das Forum zu einem geschlossenem Ticketsystem.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_size']              = array(
        'Größe (Breite, Höhe)',
        'Größe des Bereiches, in dem das Forum dargestellt wird. Bei Eingabe von "0" oder nichts wird der Wert nicht gesetzt, und die Breite sowie die Höhe werden automatisch ermittelt.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_scroll']            = array(
        'Größe scrollbarer Bereich der Themenliste (Breite, Höhe)',
        'Geben Sie nichts oder 0 ein, wenn Sie keine Scrollbalken möchten.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_startforum']        = array(
        'Startpunkt des Forums',
        'Wählen Sie hier den Forenbereich bei dem das Forum starten soll. Wenn Sie keine Angabe machen, dann sind alle Forenbereiche verfügbar.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_navigation']   = array(
        'Navigation',
        'Wählen Sie die Art der Navigation für das Forum aus. Hinweis: die Baumstruktur wird nicht gepflegt und scheinbar auch nicht mehr verwendet (nicht empfohlen).'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_threadclick']       = array(
        'Aktion bei Klick auf Thema',
        'Wählen Sie, welche Aktion bei einem Klick auf ein Thema ausgeführt werden soll.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxlength'] = array(
        'Länge von Forumtiteln',
        'Wählen Sie die Anzahl von Zeichen, nach denen in Boxen umgebrochen werden soll.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_realname']     = array(
        'Verwende richtige Namen anstelle des Benutzernamens',
        'Wählen Sie ob und wie Sie die richtigen Namen der Benutzer anzeigen lassen wollen, anstelle ihrer Benutzernamen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_postsort']          = array(
        'Sortierung der Beiträge in der Beitragsliste',
        'Wählen Sie, in welcher Reihenfolge die Beiträge in der Beitragsliste eines Themas angezeigt werden sollen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_collapsible_posts'] = array(
        'Beiträge klappbar machen',
        'Wählen Sie ob und wie Sie die Beiträge des Forums klappbar machen möchten.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb']        = array(
        'Navigationspfad anzeigen',
        'Wählen Sie, ob der Navigationspfad generiert werden soll oder nicht.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_hide_intropages']   = array(
        'Einstiegsseiten verstecken',
        'Setzen Sie dieses Häkchen, falls Sie im Forenbereich definierte Einstiegsseiten nicht anzeigen möchten. Das kann sinnvoll sein, wenn Sie verschiedene Sichten auf Ihr Forum mit Hilfe von mehreren Frontend-Modulen realisieren möchten.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jumpTo']            = array(
        'Weiterleitungsseite bei fehlender Berechtigung',
        'Geben Sie hier eine Seite ein, auf die bei fehlender Berechtigung automatisch weitergeleitet wird (z.B. eine Seite mit einem Login-Formular).'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_language']          = array(
        'Frontend-Sprache des Forums',
        'Leer=Automatisch ermitteln, de=Deutsch, en=Englisch.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual']      = array(
        'Mehrsprachigkeit ermöglichen',
        'Einige Felder (z.B. Themenname) werden mehrsprachig angeboten. D.h. die Eingabe erfolgt in der Forensprache. Mit dem Sprachwechsler-Recht kann die Eingabe im Frontend gewechselt werden (also mehrere Sprachen ausgefüllt werden). Wenn nur eine Sprache gefüllt ist, wird die bereits ausgefüllte Sprache dargestellt.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_languages'] = array(
        'Ausgewählte Sprachen für die Frontend-Mehrsprachigkeit',
        'Wählen Sie die gewünschten Sprachen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_language'] = array(
        'Frontend-Sprachen',
        'Wählen Sie die Sprachen für die Frontendeingabe. Achtung wählen Sie nur Sprachen die über die Sprachdateien definiert sind (Standard: de,en,fi)'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes']                       = array(
        'Editor verwenden',
        'Deaktivieren Sie diese Checkbox, falls Sie Bden Editor komplett deaktivieren möchten! Dies kann zu Formatfehlern führen, wenn der Editor in einem bereits laufenden Forum zuvor aktiviert waren. Ohne Editor sind Formatierungen nicht mehr möglich.'
    );
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor']                = array('Alten WYSIWYG-Editor verwenden', 'Achtung: Dieses Feature funktioniert nur, wenn das Forum eingebettet, also nicht im Dialog, verwendet wird!');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_toolbaritems']   = array('Editor Toolbar Schaltfächen', '');
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_ckeditor']                      = array('Neuen WYSIWYG-Editor verwenden', '');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor']                        = array('Editor', '');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_pn_button']                = array('Persönliche Nachrichten', 'Button für Persönliche Nachrichten im Profil anzeigen');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['ck']           = "CKEditor verwenden";
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['bb']           = "Alten WYSIWYG-Editor verwenden";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['no']           = "Keinen Editor verwenden";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imguploadpath']  = array('Bildupload-Verzeichnis', 'Wählen Sie das Verzeichnis aus, in dem die Bilder gespeichert werden sollen. Es wird je Tag ein extra Ordner innerhalb dieses Ordners angelegt.');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_fileuploadpath'] = array('Dateiupload-Verzeichnis', 'Wählen Sie das Verzeichnis aus, in dem die Dateien gespeichert werden sollen. Es wird je Tag ein extra Ordner innerhalb dieses Ordners angelegt.');
// $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_smileys'] 	= array('Smileys verwenden',
// 																		'Deaktivieren Sie diese Checkbox, falls Sie nicht wollen, dass das Forum Smileys automatisch erkennt und gegen entsprechende Icons austauscht.');
// $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_smileys_url'] 	= array('Pfad zu den Smiley-Icons',
// 																			'bsp= system/modules/con4gis_forum/html/images/smileys');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_autourl'] = array(
        'Automatische URL-Erkennung verwenden',
        'Deaktivieren Sie diese Checkbox, wenn Sie nicht wollen, dass URLs automatisch erkannt und in einen Link umgewandelt werden.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_text']     = array(
        'Navigationskacheln: Forenbereichsname anzeigen ',
        'Wählen Sie, ob auf den Navigationskacheln der Forenbereichsname angezeigt werden soll oder nicht.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_subtext']  = array(
        'Navigationskacheln: Detailinformationen anzeigen ',
        'Wählen Sie, ob auf den Navigationskacheln die Anzahl der Beiträge und Themen, bzw. die Anzahl der Unterforenbereiche angezeigt werden sollen oder nicht.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_lastpost'] = array(
        'Navigationskacheln: Informationen zum letzten Beitrag anzeigen ',
        'Wählen Sie, ob auf den Navigationskacheln Informationen zum letzten Beitrag im Forum angezeigt werden sollen oder nicht.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_center']   = array(
        'Navigationskacheln zentrieren ',
        'Wählen Sie diese Option, wenn die Navigationskacheln zentriert angezeigt werden sollen.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui']                   = array(
        'jQuery UI verwenden',
        'Deaktivieren Sie diese Checkbox, falls Sie jQuery UI komplett ausschalten möchten! Die Bibliothek wird dann nicht geladen und alle jQuery UI abhängigen Funktionen wie das jQuery UI Layout und Dialoge werden ausgeschaltet!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_lib']               = array(
        'jQuery UI Bibliothek laden',
        'Deaktivieren Sie diese Checkbox, falls Sie jQuery UI bereits anderweitig laden. Vorsicht beim Deaktivieren: achten Sie darauf, dass eine kompatible Version geladen wird!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_select']     = array(
        'jQuery UI ThemeRoller CSS Theme',
        'Wählen Sie hier eines der Standard UI-Themes aus. Sollten Sie im nächsten Schritt eine eigene Datei auswählen, wird die geladen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_src']        = array(
        'jQuery UI ThemeRoller CSS Datei',
        'Wählen Sie hier wenn gewünscht eine CSS Datei aus, die Sie mit dem jQuery UI ThemeRoller erstellt haben (https://jqueryui.com/themeroller/).'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogsize']             = array(
        'Größe von Dialogen (Breite, Höhe)',
        'Größe der Dialoge. Hat keine Bedeutung, wenn die Dialoge eingebettet sind. Wenn Sie nichts eingeben, werden Standardwerte für die Dialoggröße angenommen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_buttons_jqui_layout']    = array(
        'Buttonleiste im jQuery UI Layout',
        'Wählen Sie, ob die Buttonleiste aus jQuery-UI Buttons bestehen sollen, oder ob sie normale Links bleiben sollen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jqui_layout'] = array(
        'Navigationspfad im jQuery UI Layout',
        'Wählen Sie, ob der Navigationspfad aus jQuery-UI Buttons bestehen sollen, oder ob sie normale Links bleiben sollen.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_table_jqui_layout']      = array(
        'Themenliste im jQuery UI Layout',
        'Wählen Sie, ob die Themenliste das jQuery UI Layout bekommen soll oder nicht.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogs_embedded']       = array(
        'Dialoge einbetten',
        'Setzen Sie dieses Häkchen, damit Dialoge als eingebettete Elemente angezeigt werden.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_embdialogs_jqui']        = array(
        'Eingebettete Dialoge im jQuery UI Layout',
        'Deaktivieren Sie dieses Häkchen, damit eingebettete Dialoge nicht im jQuery UI Layout angezeigt werden.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_posts_jqui']             = array(
        'Beitragsliste und Beiträge im jQuery UI Layout',
        'Deaktivieren Sie dieses Häkchen, damit die Beiträge nicht im jQuery UI Layout angezeigt werden.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_jqui_layout']      = array(
        'Navigationskacheln im jQuery UI Layout',
        'Wählen Sie, ob den Navigationskacheln CSS-Klassen aus jQuery UI zugewiesen bekommen sollen, die für ein jQuery UI Layout sorgen, oder nicht.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jquery_lib']       = array(
        'jQuery Bibliothek laden',
        'Deaktivieren Sie diese Checkbox, falls Sie jQuery bereits anderweitig laden. Vorsicht beim Deaktivieren: achten Sie darauf, dass eine kompatible Version geladen wird!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtable_lib']      = array(
        'jQuery DataTables Bibliothek laden',
        'Deaktivieren Sie diese Checkbox, falls Sie jQuery DataTables bereits anderweitig laden, oder falls Sie innerhalb dieses Frontend-Moduls die Themenliste nicht nutzen, weil Sie z. B. nur die Kacheln zur Navigation nutzen möchten. Vorsicht beim Deaktivieren: achten Sie darauf, dass eine kompatible Version geladen wird!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqhistory_lib']    = array(
        'jQuery History Bibliothek laden',
        'Deaktivieren Sie diese Checkbox, falls Sie jQuery History.js nicht nutzen möchten. Achtung: nach dem Deaktivieren funktioniert der Vor-/Zurück-Button nicht mehr und eine URL, die eine Verlinkung auf Foren, Themen und Beiträge ermöglicht wird nicht mehr generiert.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtooltip_lib']    = array(
        'jQuery Tooltip Bibliothek laden',
        'Deaktivieren Sie diese Checkbox, falls Sie jQuery Tooltip Bibliothek nicht nutzen möchten. Tooltips werden dann nicht mehr angezeigt. '
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqscrollpane_lib'] = array(
        'jScrollPane Bibliothek laden',
        'Aktivieren Sie diese Checkbox wenn Sie in jQuery UI Dialogen stylebare Scrollbalken verwenden wollen.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_enable_maps'] = array(
        'Kartenanbindung aktivieren',
        'Bei Aktivierung dieses Schalters kann ein Mitglied mit entsprechenden Rechten Beiträge mit Geo-Koordinaten versehen, vorausgesetzt die Karten-Funktionalität wurde im entsprechenden Forenbereich aktiviert. Funktioniert nur, wenn die Contao-Erweiterung \'con4gis-Maps\' installiert ist! '
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_root'] = array("Zielseite für Sitemaplinks","");
    if (version_compare(VERSION, '3', '<')) {
        $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
            'Eine XML-Sitemap erstellen',
            'Eine Google XML-Sitemap im Wurzelverzeichnis erstellen.'
        );
    } else {
        $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
            'Eine XML-Sitemap erstellen',
            'Eine Google XML-Sitemap im Verzeichnis "share/" erstellen.'
        );
    }
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_filename'] = array(
        'Sitemap-Dateiname',
        'Geben Sie den Namen der Sitemap-Datei ohne die Dateiendung .xml ein.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_contents'] = array(
        'Inhalte der Sitemap',
        'Definieren Sie, für welche Inhalte Einträge in der Sitemap-Datei erstellt werden sollen.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jumpTo'] = array(
        'Referenzseite',
        'Wählen Sie hier die Seite aus, auf der sich das Frontend-Modul mit dem Forum befindet.'
    );

    $GLOBALS['TL_LANG']['tl_module']['ticketredirectsite'] = array(
        'Weiterleitungsseite Ticket',
        'Wählen Sie hier die Seite aus, auf die für die Ticketerstellung weitergeleitet werden soll.'
    );


    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_last_post_on_new'] = array("Beim Neuanlegen letzten Post anzeigen", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_enabled'] = array("Bewertungen aktivieren", "Zeigt ein fünf Sterne Bewertungssystem beim schreiben von Beiträgen an.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_color'] = array("Farbe der Bewertungssterne", "Hier kann die Farbe der Bewertungssterne angepasst werden. Standardmäßig entspricht sie der globalen Textfarbe");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_post_count'] = array("Beitragszahl anzeigen", "Zeige die Anzahl der Beiträge unter dem Autorennamen an.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_avatars'] = array("Avatare anzeigen", "Aktiviere die Mitglieder-Avatare.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_avatar_size'] = array("Avatar-Größe (Breite, Höhe)", "Die Breite und Höhe der User-Avatare.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_online_status'] = array("Online-Status anzeigen", "Zeige den Online-Status des Mitgliedes neben seinem Namen an.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_member_online_time'] = array("Online-Zeit (in Sekunden)", "Die Zeit, die ein Mitglied ohne Aktionen im Frontend als angemeldet dargestellt werden soll.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_ranks'] = array("Mitglieder-Ränge anzeigen", "Zeige für die Mitglieder deren Rang an.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_member_ranks']  = array("Mitglieder-Ränge", "Tragen Sie die Mitgliederränge in Abhängigkeit der Zahl an Mindestposts ein.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_min']      = array("Mindest-Posts", "Mindestanzahl an Posts für diesen Rang.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_name']     = array("Rang-Name", "Die Bezeichnung des Rangs.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_language'] = array("Sprache", "Die Sprache des Rangs.");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_uploadTypes'] = array("Erlaubte Dateitypen", "Kommasepariert, ohne Punkt");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_maxFileSize'] = array("Maximale Dateigröße", "Angabe in Byte: 1 kB = 1024 Byte, 1 MB = 1048576 Byte");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageWidth']  = array("Maximale Bildbreite", "Angabe in Pixel");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageHeight'] = array("Maximale Bildhöhe", "Angabe in Pixel");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_active'] = array("Pagination aktivieren", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_format'] = array("Paginator-Format", "Siehe <a onclick='window.open(this.href);return false;' href='http://www.xarg.org/2011/09/jquery-pagination-revised'>http://www.xarg.org/2011/09/jquery-pagination-revised</a>");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_perpage'] = array("Einträge pro Seite", "");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_onlythreads'] = array("Checkbox: nur nach Threads suchen aktivieren", "Dadurch können Sie Beiträge bzw. Kommentare von der Suche ausschließen.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_wholewords']  = array("Checkbox: nur ganze Wörter suchen aktivieren", "Dadurch können sie die Suche auf den genauen Wortlaut einschränken.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_use_tags_in_search'] = array("Schlagworte zur Auswahl in Suchmaske anzeigen", "Sie können die in den Forenbereichen festgelegten Schlagworte auswählen und damit die Suche einschränken. Hinweis: wenn Sie in den Forenbereichen keine Tags gesetzt haben. Wird die Auswahl im Frontend automatisch nicht angeboten.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_forums']      = array("Forenauswahl anzeigen", "So kann die Auswahl auf einen bestimmten Forenbereich eingeschränkt werden.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_displayonly'] = array("Suche (Eingabe) von Benutzernamen ermöglichen", "Sie können Benutzernamen eingeben und damit und über einen Zeitraum die Suche einschränken.");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_move_all'] = array("Themen auch in andere Forenmodule verschieben (nicht empfohlen!)", "Diese Funktion sollte mit Vorsicht und nur zwischen gleichartigen Forenmodulen verwendet werden! Es werden Forenbereiche aller Module als Ziel angeboten.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forumbox'] = array("forumbox Parameter umbenennen", "Sie können so den Browserparameter forumbox umbenennen. Achtung! Danach funktionieren bereits bestehende Links nicht mehr");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forum'] = array("forum Parameter umbenennen", "Sie können so den Browserparameter forum umbenennen. Achtung! Danach funktionieren bereits bestehende Links nicht mehr");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastperson'] = array("Feld -Letzter- ausblenden", "Mit dieser Funktion können Sie das Feld -Letzter- in der Liste ausblenden.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastdate'] = array("Feld -Letzter am- ausblenden", "Mit dieser Funktion können Sie das Feld -Letzter am- in der Liste ausblenden.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createperson'] = array("Feld -Verfasser- ausblenden", "Mit dieser Funktion können Sie das Feld -Verfasser- in der Liste ausblenden.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createdate'] = array("Feld -Erstellt am- ausblenden", "Mit dieser Funktion können Sie das Feld -Erstellt am- in der Liste ausblenden.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_count'] = array("Feld -#- ausblenden", "Mit dieser Funktion können Sie das Feld -#- in der Liste ausblenden.");

    /**
     * Legend
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_general_legend'] = 'Forum - Grundeinstellungen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_user_legend']    = 'Forum - Benutzereinstellungen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sizes_legend']   = 'Forum - Größeneinstellungen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_legend'] = 'Forum - Editoreinstellungen';
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pn_legend']      = 'Forum - Persönliche Nachrichten';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_legend']   = 'Forum - Einstellungen für Kachelsicht';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_legend']    = 'Forum - Styling (jQuery UI)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_maps_legend']    = 'Forum - Kartenanbindung (con4gis-Maps)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_lib_legend']     = 'Forum - jQuery Bibliotheken';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_legend'] = 'Forum - XML-Sitemap';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_legend']       = 'Forum - Sucheinstellungen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_legend']   = 'Forum - Seiten und Nummerierung';

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_legend'] = 'Navigationspfad';

    /**
     * References
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['DISCUSSIONS'] = 'Diskussionen (Themen & Beiträge)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['QUESTIONS']   = 'Informationen (Fragen & Kommentare)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['TICKET']   = 'Ticketsystem';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['TREE']  = 'Baum';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['BOXES'] = 'Kacheln';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREAD'] = 'Alle Beiträge des Themas anzeigen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FPOST']  = 'Ersten Beitrag anzeigen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LPOST']  = 'Letzten Beitrag anzeigen';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['UU'] = 'Keine richtigen Namen verwenden (verwende stattdessen Benutzernamen)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FF'] = 'Nur Vornamen anzeigen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LL'] = 'Nur Nachnamen anzeigen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FL'] = 'Vor- und Nachnamen anzeigen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LF'] = 'Nach- und Vornamen anzeigen';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['UP'] = 'Ältesten Beitrag zuerst anzeigen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['DN'] = 'Neuesten Beitrag zuerst anzeigen';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['NC'] = 'Keine klappbaren Beiträge verwenden';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CO'] = 'Alle Beiträge aufgeklappt';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CC'] = 'Alle Beiträge zugeklappt';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CF'] = 'Erster Beitrag aufgeklappt';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CL'] = 'Letzter Beitrag aufgeklappt';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREADS'] = 'Öffentliche Themen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FORUMS']  = 'Öffentliche Forenbereiche';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['INTROS']  = 'Öffentliche Forenbereiche - Einstiegsseiten';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['settings']  = 'con4gis Einstellungen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['base']      = 'base';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['black-tie'] = 'black-tie';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['blitzer']   = 'blitzer';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['cupertino'] = 'cupertino';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['dark-hive'] = 'dark-hive';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['dot-luv']   = 'dot-luv';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['eggplant']  = 'eggplant';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['excite-bike']   = 'excite-bike';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['flick']         = 'flick';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['hot-sneaks']    = 'hot-sneaks';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['humanity']      = 'humanity';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['le-frog']       = 'le-frog';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['mint-choc']     = 'mint-choc';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['overcast']      = 'overcast';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['pepper-grinder'] = 'pepper-grinder';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['redmond']       = 'redmond';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['smoothness']    = 'smoothness';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['south-street']  = 'south-street';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['start']         = 'start';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['sunny']         = 'sunny';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['swanky-purse']  = 'swanky-purse';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['trontastic']    = 'trontastic';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['ui-darkness']   = 'ui-darkness';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['ui-lightness']  = 'ui-lightness';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['vader']         = 'vader';

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip']                           = array("Tooltip für die Themenliste", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_first_post'] = "Titel des ersten Beitrags";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_last_post']  = "Titel des letzten Beitrags";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_first_post']  = "Inhalt des ersten Beitrags";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_last_post']   = "Inhalt des letzten Beitrags";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadtitle']      = "Titel des Themas";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadbody']       = "Beschreibung des Themas";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['disabled']         = "deaktiviert";

    $GLOBALS['TL_LANG']['tl_module']['c4g_appearance_themeroller_css'] = array('jQuery UI ThemeRoller CSS Datei', 'Optional: wählen Sie eine, mit dem jQuery UI ThemeRoller erstellte CSS-Datei aus, um den Stil des Moduls entsprechend anzupassen.');
?>