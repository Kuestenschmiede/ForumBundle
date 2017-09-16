<?php if (!defined('TL_ROOT')) {
    die('You cannot access this file directly!');
}

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
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_type']              = array(
        'Tipo di forum',
        'Cambia tipo per cambiare le definizioni. Nelle informazioni i threads sono domande ed i posts commenti.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_size']              = array(
        'Dimensioni (larghezza, altezza)',
        'Dimensione degli elementi DIV nei quali è visualizzato il forum. La dimensione viene calcolata automaticamente se non si introduce alcun valore qui.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_scroll']            = array(
        'Dimensione dell\'area di scorrimento della lista threads (larghezza, altezza)',
        'Lasciare vuoto se non si vogliono le scrollbars.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_startforum']        = array(
        'Origine',
        'Selezionare il forum genitore da cui partire. Lasciare vuoto per visualizzare tutti i forums.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_navigation']   = array(
        'Navigazione',
        'Scegli la modalità di navigazione per il forum.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_threadclick']       = array(
        'Azione per i clicks sui threads',
        'Seleziona l\'azione da eseguire quando un thread viene cliccato.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_realname']     = array(
        'Usa i nomi reali al posto dei nomi utente',
        'Scegliere se e come si vogliono mostrare i nomi reali degli utenti al posto dei nomi utenta'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_postsort']          = array(
        'Ordinamento dei post',
        'Selezionare l\'ordinamento della lista di posts dentro ai threads.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_collapsible_posts'] = array(
        'Rendi i posts collassabili',
        'Selezionare se e come si vogliono rendere collassabili i posts.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb']        = array(
        'Mostra breadcrumb',
        'Selezionare se si vuole visualizzare la breadcrumb.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_hide_intropages']   = array(
        'Nascondere le pagine introduttive',
        'Selezionare se si vogliono nascondere le pagine introduttive anche se sono state definite. Può servire se si vogliono realizzare viste differenti del forum con diversi moduli del frontend.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jumpTo']            = array(
        'Pagina di redirezione in caso di accesso negato',
        'Selezionare la pagina alla quale i visitatori verranno rediretti quando il permesso per una certa azione viene negato.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_language']          = array(
        'Linguaggio frontend',
        'Vuoto=determina automaticamente, de=German, en=English, it=Italian.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual']      = array(
        'campi multilinguaggio',
        'è possibile inserire alcuni campi nella propria lingua. Con l\'apposito selettore della lingua è possibile cambiarla.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_languages'] = array(
        'Lingue del frontend per tutti i campi multilinguaggio',
        'Selezionare le proprie lingue.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_language'] = array(
        'Lingue del frontend',
        ''
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes']                       = array(
        'Utilizzo BBCodes',
        'Disattivare questo checkbox se non si vogliono utilizzare i BBCodes nel forum. Notare che disattivando i BBCodes quando sono utilizzati nei posts pu causare brutti errori nei formati visualizzati.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_last_post_on_new'] = array("Mostra l'ultimo post quando ne viene creato uno nuovo", "");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_enabled'] = array("Enable rating", "Abilita la valutazione a 5 stelle quando si scrivono i post.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_color'] = array("Colore delle stelle di valutazione", "Cambia il colore delle stelle di valutazione. Predefinito: textcolor globale");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_pn_button']                = array('Messaggi privati','Mostra un pulsante per i messaggi privati');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor']                = array('Usa editor WYSIWYG', 'ATTENZIONE: Questa possibilità funziona solo con i forums incorporati!');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_toolbaritems']   = array('Pulsanti della toolbar dell\'editor WYSIWYG', '');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imguploadpath']  = array('Cartella di upload per immagini', 'Imposta dove verranno memorizzate le immagini caricate. Una sottocartella aggiuntiva con la data verrà creata automaticamente dentro questa');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_fileuploadpath'] = array('Cartella di upload per i file', 'Imposta dove verranno memorizzati i files caricati. Una sottocartella aggiuntiva con la data verrà creata automaticamente dentro questa');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_autourl'] = array(
        'Rconoscimento automatico URLs',
        'Questa funzionalità riconosce automaticamente le URLs digitate e le converte in links.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_text']     = array(
        'Box navigation: display forum name',
        'Check this to show the forum name in the box navigation.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_subtext']  = array(
        'Riquadro di navigazione: mostra dettagli',
        'Selezionare per mostrare il numero dei forum discendenti, il numero dei threads ed il numero dei posts nel riquadro di navigazione.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_lastpost'] = array(
        'Riquadro di navigazione: mostra informazioni sull\'ultimo post',
        'Selezionare per mostrare le informazioni sull\'ultimo post nel forum.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_center']   = array(
        'Centra il riquadro di navigazione',
        'Selezionare per centrare il blocco contenente i riquadri di navigazione.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui']                   = array(
        'Usa jQuery UI',
        'Deselezionare per disattivare la jQuery UI completamente. La libreria non verrà caricata e tutte le funzioni che dipendono da essa saranno disattivate!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_lib']               = array(
        'Carica la libreria jQuery UI',
        'Deselezionare se si sta già caricando la libreria jQuery UI per conto proprio: controllare che sia una versione compatibile della libreria!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_select']     = array(
        'Selettore tema CSS del jQuery UI ThemeRoller',
        'Selezionare un tema standard.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_src']        = array(
        'File CSS per il jQuery UI ThemeRoller',
        'Opzionale: selezionare un proprio file CSS per il jQuery UI ThemeRoller.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogsize']             = array(
        'Dimensione dei dialoghi (larghezza, altezza)',
        'Lasciare vuoto per utilizzare i valori predefiniti. Senza significato se si usano i dialoghi incorporati.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_buttons_jqui_layout']    = array(
        'Usa la jQuery UI Layout per i pulsanti della toolbar',
        'Selezionare se si vogliono utilizzare i pulsanti della jQuery-UI, altrimenti verranno creati dei collegamenti.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jqui_layout'] = array(
        'Usa la jQuery UI Layout per il bottoni del breadcrumb',
        'Selezionare se si vogliono utilizzare i pulsanti della jQuery-UI, altrimenti verranno creati dei collegamenti.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_table_jqui_layout']      = array(
        'Usa la jQuery UI Layout per la lista dei thread',
        'Selezionare se si vuole usare la jQuery-UI per la lista dei thread.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogs_embedded']       = array(
        'Dialoghi incorporati',
        'Selezionare se si vogliono i dialoghi incorporati nella pagina e non scollegati.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_embdialogs_jqui']        = array(
        'Usa la jQuery UI Layout per i dialoghi incorporati',
        'Selezionare se si vuole usare la jQuery-UI per i dialoghi incorporati. '
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_posts_jqui']             = array(
        'Usa la jQuery UI Layout per i posts',
        'Selezionare se si vuole usare la jQuery-UI per la visualizzazione dei posts.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_jqui_layout']      = array(
        'Usa la jQuery UI Layout per i riquadri di navigazione',
        'Selezionare se si vogliono usare le classi CSS della jQuery-UI per stilizzare i riquadri di navigazione.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jquery_lib']       = array(
        'Carica la libreria jQuery',
        'Selezionare qui se si sta già caricando da sè la libreria jQuery. Attenzione: controllare che sia una versione compatibile!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtable_lib']      = array(
        'Carica la libreria jQuery DataTables',
        'Deselezionare se non si vuole usare la libreria jQuery DataTables! Attenzione: non è possibile usare la lista threads se la libreria jQuery DataTables non è disponibile!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqhistory_lib']    = array(
        'Carica la libreria jQuery History',
        'Deselezionare se non si vogliono utilizzare le funzioni della libreria jQuery History.js. Attenzione: deselezionando il pulsante \'indietro\' non funzionerà all\'interno dei forum. Inoltre non verrà aggiornata l\'URL del browser mentre si usano i forums, quindi non ci sarà un un funzionamento semplice di collegamento tra forum, threads e posts.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtooltip_lib']    = array(
        'Carica la libreria jQuery Tooltip',
        'Deselezionare se non si vogliono usare le funzioni della libreria jQuery Tooltip.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqscrollpane_lib'] = array(
        'Carica la libreria jScrollPane',
        'Selezionare se si vogliono le barre di scorrimento stilizzabili nei dialoghi della jQuery UI.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_enable_maps'] = array(
        'Abilita mappe (richiede con4gis-Maps)',
        'Selezionare per attivare le funzionalità per la mappe in generale. Nota che occorre configurarle anche nella parte di manutenzione del forum. Richiede l\'installazione dell\'estensione di Contao extension \'con4gis-Maps\'! '
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_root'] = array("Pagina di destinazione per i collegamenti alla mappa del sito","");


    if (version_compare(VERSION, '3', '<')) {
        $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
            'Crea una mappa XML del sito',
            'Crea una mappaGoogle XML del sito nella directory radice.'
        );
    } else {
        $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
            'Crea una mappa XML del sito',
            'Crea una mappaGoogle XML del sito nella directory "share/".'
        );
    }
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_filename'] = array(
        'Nome del file della mappa del sito',
        'Inserire un nome per la mappa del sito, senza l\'estensione .xml.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_contents'] = array(
        'Contenuto della mappa del sito',
        'Selezionare i contenuti che si vogliono scrivere nella mappa del sito.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jumpTo'] = array(
        'Ridirigi a',
        'Selezionare la pagina che contiene il modulo frontend del forum.'
    );


    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_uploadTypes'] = array("Tipi di files permessi", "separati da virgole, senza punto ");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_maxFileSize'] = array("DImensione massima dei files", "Dichiarazione in Byte: 1 kB = 1024 Byte, 1 MB = 1048576 Byte");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageWidth']  = array("Massima larghezza immagine", "Dichiarazione in pixel");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageHeight'] = array("Massima altezza immagine", "Dichiarazione in pixel");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_onlythreads'] = array("Casella cercare solo nei threads (non nei posts)", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_wholewords']  = array("Casella per ricerca per parole intere", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_use_tags_in_search'] = array("Selezionare per i tags definiti nel forum", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_forums']      = array("Selezionare i forum speciali", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_displayonly'] = array("Campo per ricerca per nomi utente e date", "");
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_period']      = array("Periodsearch", "");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_move_all'] = array("Permetti spostamento dei threads anche negli altri forums (non consigliato!)", "Muovere i threads solo tra forums con impostazioni identiche o simili!");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forumbox'] = array("rinomina il parametro forumbox", "rinomina il parametro del browser forumbox (non consigliato!)");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forum'] = array("rinomina il parametro forum", "rinomina il parametro del browser forum (non consigliato!)");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastperson'] = array("rimuovi campo -Ultimo-", "Rimuove il campo -Last- dalla tabella.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastdate'] = array("rimuovi campo -Ultimo di-", "Rimuove il campo -Ultimo di- dalla tabella.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createperson'] = array("rimuovi campo -AUTORE-", "Rimuove il campo -Author- dalla tabella.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createdate'] = array("rrimuovi campo -Creato il-", "Rimuove il campo -Created on- dalla tabella.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_count'] = array("rimuovi campo  -#-", "Rimuove il campo -#- dalla tabella.");

    /**
     * Legend
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_general_legend'] = 'Forum - Generali';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_user_legend']         = 'Forum - Impostazioni utente';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sizes_legend']        = 'Forum - Dimensioni';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_legend'] = 'Forum - Editor (BBCodes)';
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pn_legend']      = 'Forum - Personal Messaging';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_legend']   = 'Forum - Impostazioni dei riquadri di navigazione';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_legend']    = 'Forum - Styling (jQuery UI)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_maps_legend']    = 'Forum - Mappe (con4gis-Maps)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_lib_legend']     = 'Forum - librerie jQuery';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_legend'] = 'Forum - mappa XML del sito';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_legend']       = 'Forum - Impostazioni di ricerca';

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_legend'] = 'Breadcrumb';

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_active'] = array("Attiva impaginazione", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_format'] = array("Formato impaginatore", "Vedere <a onclick='window.open(this.href);return false;' href='http://www.xarg.org/2011/09/jquery-pagination-revised'>http://www.xarg.org/2011/09/jquery-pagination-revised</a>");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_perpage'] = array("Campi per pagina", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_legend'] = 'Forum - Impaginazione';


    /**
     * References
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['DISCUSSIONS'] = 'Discussioni (threads & posts)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['QUESTIONS']   = 'Informazioni (domande & commenti)';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['TREE']  = 'Albero';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['BOXES'] = 'Riquadri';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREAD'] = 'Mostra tutti i posts del thread';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FPOST']  = 'Mostra il primo post';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LPOST']  = 'Mostra l\'ultimo post';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['UU'] = 'Non utilizzare i nomi reali (usa i nomi utenti al loro posto)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FF'] = 'Usa solo il nome di battesimo';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LL'] = 'Usa solo il cognome';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FL'] = 'Use nome e cognome';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LF'] = 'Use cognome e nome';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['UP'] = 'Prima il post più vecchio';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['DN'] = 'Prima il post più recente';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['NC'] = 'Non usare posts collassabili';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CO'] = 'Tutti i posts non collassati';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CC'] = 'Tutti i posts collassati';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CF'] = 'Il primo post non collassato';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CL'] = 'L\'ultimo post non collassato';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREADS'] = 'Threads pubblici';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FORUMS']  = 'Forums pubblici';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['INTROS']  = 'Forums pubblici - Pagine introduttive';

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

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip']                           = array("Tooltip per la lista dei threads", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_first_post'] = "Titolo del primo post";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_last_post']  = "Titolo dell\'ultimo post";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_first_post']  = "Contenuto del primo post";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_last_post']   = "Contenuto dell\'ultimo post";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadtitle']      = "Titolo del thread";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadbody']       = "Descrizione del thread";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['disabled']         = "disattivato";

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor']                        = array('Editor WYSIWYG', '');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['ck']           = "Nuovo Editor WYSIWYG";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['bb']           = "Vecchio Editor WYSIWYG";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['no']           = "Nessun Editor WYSIWYG";

    $GLOBALS['TL_LANG']['tl_module']['c4g_appearance_themeroller_css'] = array('File CSS per il jQuery UI ThemeRoller', 'selezionare un file CSS per la jQuery UI.');
?>
