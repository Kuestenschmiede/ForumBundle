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
$GLOBALS['TL_LANG']['tl_c4g_forum']['name'] 			= array('Nome',
																'Nome del forum');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_names']           = array('Titoli opzionali', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_name']            = array('Titolo', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language']        = array('Lingua', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['headline'] 		= array('Intestazione',
																"Qui puoi aggiungere un'intestazione al .");
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headlines']       = array('Intestazioni opzionali', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headline']        = array('Intestazione', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['description'] 		= array('Descrizione',
																'La descrizione viene mostrata con un tooltip.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_descriptions']    = array('Descrizioni opzionali', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_description']     = array('Descrizioni', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['published'] 		= array('Pubblicato',
																'Attivare per pubblicare il forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['box_imagesrc'] 	= array('Immagine del riquadro',
																'Immagine da mostrare in un riquadro nel modulo frontend.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['use_intropage'] 	= array('Usa pagina introduttiva',
																'Una pagina introduttiva è una pagina in cui puoi mostrare informazioni. Contiene un link alla lista dei thread del forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage'] 		= array('Contenuto della pagina ntroduttiva',
																'Il contenuto della pagina introduttiva. Può anche contenere immagini e links.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn'] 	= array('Testo in fondo al forum',
																	'Se inserisci un testo qui viene generato un pulsante che collegato alla lista dei threads del forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn_jqui'] 	= array('Usa jQuery UI Style per i pulsanti del forum',
																		'Attiva questo per ottenere dei pulsanti in stile jQuery UI, o disattivalo per ottenere dei semplici link.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['pretext'] 			= array('Testo prima della lista threads / lista subforums',
																'Questo testo viene mostrato appena prima della lista dei threads o dei subforums.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['posttext'] 		= array('Testo dopo della lista threads / lista subforums',
																'Questo testo viene mostrato appena dopo la lista dei threads o dei subforums.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_groups'] 	= array('Definisci i gruppi di utenti',
																'Seleziona qui per assegnare gruppi di utenti a questo forum. Altrimenti questi verranno ereditati dal forum genitore.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_groups'] 	= array('Utenti del forum',
																'Agli utenti appartenenti ai gruppi selezionati verranno assegnati i permessi di utenti del forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_groups'] 	= array('Amministratori del forum',
																'Agli utenti appartenenti ai gruppi selezionati verranno assegnati i permessi di amministratori del forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_rights'] 	= array('Definizione permessi',
																'Seleziona qui per assegnare i permessi agli ospiti, utenti ed amministratori del forum. Altrimenti questi verranno ereditati dal forum genitore. Se non ci sono permessi impostati ne verranno assegnati di default.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['guest_rights'] 	= array('Permessi per gli ospiti',
																'Definizione delle azioni permesse agli ospiti.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_rights'] 	= array('Permessi per gli utenti',
																'Definizione delle azioni permesse agli utenti.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_rights'] 	= array('Permessi per gli amministratori',
																'Definizione delle azioni permesse agli amministratori.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['enable_maps'] 		= array('Abilita mappe (richiede con4gis-Maps)',
																'Seleziona qui per attivare tutte le funzionalità sulle mappe per questo forum. Nota che devi anche configurare le funzionalità relative alle mappe nel modulo del frontend ed assegnare diritti sufficienti agli utenti. Richiede l\'installazione dell\'estensione di Contao \'c4g_maps\'! ');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_type'] 		= array('Tipo di localizzazione',
																'Definizione del tipo di localizzazioni che possono essere create all\'interno del forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locationstyle']  	= array('Permette di sostituire lo stile delle localizzazioni delle mappe',
																			'Selezionando quest\'opzione permette agli utenti di sostituire lo stile delle localizzazioni con una sua estensione popup.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locstyles']  	= array('stili di localizzazione permessi',
																		'Imposta gli stili disponibili per gli utenti. Predefinito: tutti');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_id'] 			= array('Mappa base',
																'Seleziona una mappa che è definita come mappa base in con4gis-Maps per l\'editor selezionato nei posts, e per mostrare le mappe.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_location_label'] 	= array('Etichetta di localizzazione',
																	'Definisce un\'etichetta in sostituzione della frase "luogo mappa" nel .');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_label'] 		= array('Etichetta',
																'Definisce la fonte di un\'etichetta che sarà mostrata nel luogo.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_tooltip'] 		= array('Tooltip',
																'Definisce la fonte di un tooltip , che sarà mostrato nel luogo.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_popup'] 		= array('Popup',
																'Definisce la fonte di un popup, che sarà mostrato cliccando in un simbolo di luogo.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_link'] 		= array('Link',
																'Definisce la fonte di un link, al quale si verrà indirizzati cliccando su un simbolo di luogo.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['linkurl'] 			= array('Link ad un\'altra pagina',
																'Introdurre un link se si vuole venire indirizzati ad un\'altra pagina cliccando sul forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['link_newwindow'] 	= array('Apri link in una nuova finestra',
																'Non apre il link nella finestra corrente ma mostra la pagina collegata in una nuova finestra.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['sitemap_exclude'] 	= array('Esclude dalla mappa XML del sito',
																'Esclude questo forum ed i suoi threads dalla mappa XML sel sito di Google (La mappa XML del sito è attivata nel modulo del frontend)');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['general_legend']	 	= 'Generale';
$GLOBALS['TL_LANG']['tl_c4g_forum']['language_legend'] 		= 'Impostazioni lingue';
$GLOBALS['TL_LANG']['tl_c4g_forum']['comfort_legend']	 	= 'Riquadri';
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_legend']		= 'Pagina introduttiva';
$GLOBALS['TL_LANG']['tl_c4g_forum']['infotext_legend'] 		= 'Informazioni';
$GLOBALS['TL_LANG']['tl_c4g_forum']['groups_legend'] 		= 'Gruppi utenti';
$GLOBALS['TL_LANG']['tl_c4g_forum']['rights_legend'] 		= 'Permessi';
$GLOBALS['TL_LANG']['tl_c4g_forum']['maps_legend'] 			= 'Maps (con4gis)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['expert_legend']  		= 'Impostazioni avanzate';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['new']    		= array('Nuovo forum', 'crea un nuovo forum');
$GLOBALS['TL_LANG']['tl_c4g_forum']['edit']   		= array('Modifica forum', 'Modifica il forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copy']   		= array('Duplica forum', 'Duplica il forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copyChilds']   = array('Duplica forum con discendenti', 'Duplica il forum ID %s con i suoi discendenti');
$GLOBALS['TL_LANG']['tl_c4g_forum']['cut']    		= array('Sposta forum', 'Sposta il forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['delete'] 		= array('Elimina forum', 'Elimina il forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['toggle'] 		= array('Pubblica/nascondi il forum', 'Pubblica/nascondi il forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['show']   		= array('Dettagli', 'Mostra i dettagli del forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['index']   		= array('Crea indice', 'Crea l\'indice per tutto il forum');

/**
 * Links
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['build_index'] 				= array('Indicizzazione', 'Configurazione dell\'indicizzazione');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OFF']    	= 'Spento';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUBJ']   	= 'Oggetto';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['CUST']   	= 'Definibil dall\'utente nel post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['POST']   	= 'Post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUPO']   	= 'Oggetto + post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['THREA']  	= 'Thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PLINK']  	= 'Collegamento al post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['LINK']   	= 'Nome del Colletamento';
// $GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  	= 'Estendi Popup (ID(OSM)-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  	= 'Estendi Popup (BETA)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PICK']   	= 'Punto singolo (GEO-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['EDIT']   	= 'Punti, linee, poligoni (Editor)';

/**
 * Forum Rights
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_visible']        	= 'Forum visibile';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadlist'] 		= 'Lista threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_readpost'] 			= 'Leggi posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newpost'] 			= 'Crea un nuovo post nel thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newthread'] 			= 'Crea un nuovo thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_postlink']			= 'Crea collegamenti nei posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadsort']			= 'Campo di ordinamento del Thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threaddesc']			= 'Descrizione del thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownpost']		= 'Modifica dei propri posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editpost']			= 'Modifica dei posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownthread']		= 'Modifica dei propri threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editthread']			= 'Modifica dei threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delownpost']			= 'Cancellazione dei propri posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delpost']			= 'Cancellazione dei posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delthread'] 			= 'Cancellazione dei threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_movethread'] 		= 'Spostamento dei threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribethread']	= 'Iscrizione ai threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribeforum'] 	= 'Iscrizione al forum';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_addmember'] 			= 'Aggiunta utenti al forum';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapview'] 			= 'Visualizzazione mappe (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 			= 'Modifica mappe (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapextend'] 			= 'Estensione dati mappe (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 			= 'Modifica mappe: Stile luoghi';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_search'] 			= 'Cerca';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_latestthreads']		= 'Ultimi threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_alllanguages']	    = 'Modifica tutte le lingue (multilingue)';

/**
 * Fulltext Indexing Configuration Texts
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['headline_index']  	= array('Indicizzazione',
																'Informazioni sull\'indicizzazione sulla creazione degli indici.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['info_headline']   	= "Informazioni sull\'indicizzazione";
$GLOBALS['TL_LANG']['tl_c4g_forum']['info']   	 	  	= array('Primo indice',
																'Ultimo indice totale',
																'Ultimo indice',
																'Numero di parole indicizzate');
$GLOBALS['TL_LANG']['tl_c4g_forum']['noIndex']    	    = "Non è stato trovato alcun indice! La tua ricerca non funzionerà senza un indice!";
$GLOBALS['TL_LANG']['tl_c4g_forum']['warning']     		= array("L'inidicizzazone completa richiede un po' di tempo. Questo dipende fortemente dalla quantità dei contenuti nel forum.",
																"Attendere e non uscire dal sito durante la creazione dell'indice!");
$GLOBALS['TL_LANG']['tl_c4g_forum']['success']			= "L'indice è stato creato correttamente.";
$GLOBALS['TL_LANG']['tl_c4g_forum']['fail']				= array("ERRORE: ",
																"Tempo scaduto nella creazione dell'indice!");





$GLOBALS['TL_LANG']['tl_c4g_forum']['default_subscription_text'] = <<<TEXT
Hello ##USERNAME##,

member '##RESPONSIBLE_USERNAME##' ##ACTION_PRE## ##ACTION_NAME_WITH_SUBJECT## to your subscribed thread '##THREADNAME##' in froum '##FORUMNAME##'


##POST_CONTENT##


To open the thread use the following link:
##DETAILS_LINK##

__________________________________________________________________________________________

To unsubscribe from the forum use this link:
##UNSUBSCRIBE_LINK##

To cancel all subscriptions please use this link:
##UNSUBSCRIBE_ALL_LINK##
TEXT;
?>
