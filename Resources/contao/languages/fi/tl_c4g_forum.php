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
$GLOBALS['TL_LANG']['tl_c4g_forum']['name'] 			= array('Nimi',
																'Foorumin nimi');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_names']           = array('Optional captions', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_name']            = array('Caption', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language']        = array('Language', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['headline'] 		= array('Otsikko',
																'Tässä voit lisätä otsikon Foorumiin.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headlines']       = array('Optional headlines', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headline']        = array('Headline', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['description'] 		= array('Kuvaus',
																'Kuvaus näytetään tooltippinä.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['published'] 		= array('Julkaistu',
																'Aktivoi julkaistaksesti foorumin.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['box_imagesrc'] 	= array('Kuva boksia varten',
																'Kuva näytettäväksi boksissa etupää moduulissa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['use_intropage'] 	= array('Käytä introsivua',
																'Introsivu on sivu, johon voit laittaa tietoa. Sisältää linkin foorumin threadlistaan.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage'] 		= array('Introsivun sisältö',
																'Introsivun sisältö. Voi myös sisältää kuvia ja linkkejä.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn'] 	= array('Teksti painiketta foorumilla',
																	'Jos laitat tekstiä tähän nappi on luotu, mikä linkkaa foorumin threadlistaan.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn_jqui'] 	= array('Käytä jQueryn UI Stylea foorumi nappiin',
																		'Aktivoi tämä saadaksesti Jquery UI napin, tai deaktivoi se saadaksesti simppelin linkin.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['pretext'] 			= array('Tekstiä ennen threadlistaa / subforum listaa',
																'Tämä teksti näytetään juuri ennen threadlistaa / subfoorumilistaa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['posttext'] 		= array('Teksti threadlistan jälkeen / subfoorumilistan',
																'Tämä teksti näytetään threadlistan jälkeen /subfoorumilistan.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_groups'] 	= array('Määrittele jäsenryhmät',
																'Ruksita tämä määrittääksesi jäsenryhmät tähän foorumiin. Muuten toimeksiannot inheritetaan parent foorumista.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_groups'] 	= array('Foorumin jäsenet',
																'Valitut jäsenet jäsenryhmistä määritellään käyttöoikeudet foorumin jäsenet oikeuksina.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_groups'] 	= array('Foorumin ylläpitäjät',
																'Valitut jäsenet jäsenryhmistä määritellään käyttöoikeudet foorumin ylläpitäjät oikeuksina.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_rights'] 	= array('Määrittele käyttöoikeudet',
																'Ruksita tämä määrittääksesi oikeudet vieraille, foorumin jäsenille ja foorumin ylläpitäjille. Muuten toimeksiannot inheritetaan parent foorumista. Jos ei ole oikeuksia määritelty ollenkaan, niin oletusoikeudet otetaan käyttöön.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['guest_rights'] 	= array('Vieraiden käyttöoikeudet',
																'Määritä mitä toimintoja vieraat voivat suorittaa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_rights'] 	= array('Foorumin jäsenien käyttöoikeudet',
																'Määritä mitä toimintoja Foorumin jäsenet voivat suorittaa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_rights'] 	= array('Foorumin ylläpitäjien käyttöoikeudet',
																'Määritä mitä oikeuksia Foorumin ylläpitäjät voivat suorittaa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['enable_maps'] 		= array('Ota käyttöön kartat (tarvitsee con4gis-Mapsin)',
																'Ruksita tämä ottaaksesi käyttöön kartta funktionaalisuuden tähän foorumiin. huomioi että sinun pitää myös konfiguroida kartan funktionaalisuus etupää moduuliin, ja määritellä riittävät oikeudet jäsenille. Tarvitsee Contao extensionin \'c4g_mapsin\' asentuakseen! ');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_type'] 		= array('Sijainnin tyyppi',
																'Määrittele tyyppi paikoissa, jotka voidaan luoda foorumin sisään.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locationstyle']  	= array('Salli ohittaa kartat locationstylella',
																			'Ruksittamalla tämän vaihtoehdon avulla käyttäjä voi ohittaa karttojen locationstylet popup-laajennuksella.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locstyles']  	= array('sallittu Location stylet',
																		'Ruksita sijainti tyylit saatavaksi kaikille käyttäjille. Oletus: kaikki');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_id'] 			= array('Yleiskartta',
																'Valitse kartta joka on määritelty con4gis kartoissa yleiskarttana valitulle editorille viesteissä ja karttojen esittämisessä.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_location_label'] 	= array('Sijainti label',
																	'Määritä labelin korvata termi "kartan sijainti" käyttöliittymässä.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_label'] 		= array('Label',
																'Määrittää labelin lähteen mikä näytetään sijainnissa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_tooltip'] 		= array('Työkalutippi',
																'Määrittää työkalutipin sijainnin mikä näytetään sijainnissa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_popup'] 		= array('Ponnahdusikkuna',
																'Määrittää ponnahdusikkunan lähteen, mikä näytetään klikkaa sijainti symbolissa.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_link'] 		= array('Linkki',
																'Määrittää linkin lähteen, mikä ponnahtaa klikkaa sijainti symboolista.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['linkurl'] 			= array('Linkki toiselle sivulle',
																'Laita linkki jos haluat hypätä toiselle sivulle kun foorumia on klikattu .');
$GLOBALS['TL_LANG']['tl_c4g_forum']['link_newwindow'] 	= array('Avaa linkin toiseen ikkunaan',
																'Älä avaa linkkejä samalle ikkunalle, mutta laita linkatut sivut uuteen ikkunaan.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['sitemap_exclude'] 	= array('Sulje pois XML sivukartta',
																'Älä laita foorumia tai sen threadeja Google XML sivukarttaan (XML sivukartta on aktivoitu etupää moduulissa)');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['general_legend']	 	= 'Yleinen';
$GLOBALS['TL_LANG']['tl_c4g_forum']['language_legend'] 		= 'Language settings';
$GLOBALS['TL_LANG']['tl_c4g_forum']['comfort_legend']	 	= 'Boksit';
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_legend']		= 'Introsivu';
$GLOBALS['TL_LANG']['tl_c4g_forum']['infotext_legend'] 		= 'Info';
$GLOBALS['TL_LANG']['tl_c4g_forum']['groups_legend'] 		= 'Jäsen ryhmä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['rights_legend'] 		= 'Käyttöoikeudet';
$GLOBALS['TL_LANG']['tl_c4g_forum']['maps_legend'] 			= 'Kartat (con4gis)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['expert_legend']  		= 'Expert asetukset';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['new']    		= array('Uusi foorumi', 'Luo uusi foorumi');
$GLOBALS['TL_LANG']['tl_c4g_forum']['edit']   		= array('Muokkaa foorumia', 'Muokkaa foorumin ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copy']   		= array('Kopioi foorumi', 'kopioi foorumin ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copyChilds']   = array('Kopioi foorumi lasten kanssa', 'Kopioi foorumin ID %s lasten kanssa');
$GLOBALS['TL_LANG']['tl_c4g_forum']['cut']    		= array('Siirrä foorumi', 'siirttä foorumin ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['delete'] 		= array('Poista foorumi', 'Poista foorumin ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['toggle'] 		= array('Julkaise/älä julkaise foorumia', 'julkaise/älä julkaise foorumin ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['show']   		= array('Yksityiskohdat', 'Näytä foorumin ID:n yksityiskohdat %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['index']   		= array('Rakenna indeksi', 'Rakentaa indeksin valmiille foorumille');

/**
 * Links
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['build_index'] 				= array('täysteksti-indeksointi', 'Konfiguroi täystekstin-indeksiä');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OFF']    	= 'Off';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUBJ']   	= 'Aihe';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['CUST']   	= 'Käyttäjän määrittelemät viestit';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['POST']   	= 'Viesti';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUPO']   	= 'Aihe + viesti';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['THREA']  	= 'Thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PLINK']  	= 'Viestin linkki';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['LINK']   	= 'Linkin nimi';
// $GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  	= 'Extend Popup (ID(OSM)-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  	= 'Laajennettu ponnahdusikkuna (BETA)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PICK']   	= 'Yksi piste (GEO-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['EDIT']   	= 'Pisteet, viivat, polygonit (Editor)';

/**
 * Forum Rights
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_visible']        	= 'Foorumi näkyvissä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadlist'] 		= 'Threadlista';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_readpost'] 			= 'Lue viestejä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newpost'] 			= 'Luo uusi viesti threadissa';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newthread'] 			= 'Luo uusi thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_postlink']			= 'Luo linkkejä viestissää';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadsort']			= 'Threadin järjestely kenttä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threaddesc']			= 'Threadin kuvaus';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownpost']		= 'Muokkaa omaa viestiä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editpost']			= 'Muokkaa viestiä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownthread']		= 'Muokkaa omaa threadia';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editthread']			= 'Muokkaa threadia';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delownpost']			= 'Poista oma viesti';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delpost']			= 'Poista viesti';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delthread'] 			= 'Poista thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_movethread'] 		= 'Siirrä thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribethread']	= 'Tilaa thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribeforum'] 	= 'Tilaa foorumi';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_addmember'] 			= 'Lisää foorumin jäseniä';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapview'] 			= 'Katso karttoja (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 			= 'Muokkaa karttoja (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapextend'] 			= 'Pidennä kartan dataa (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 			= 'Muokkaa karttoja: Sijainnin tyyli';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_search'] 			= 'Etsi';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_latestthreads']		= 'Viimeisimmät threadit';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_alllanguages']	    = 'Edit all languages (multilingual)';

/**
 * Fulltext Indexing Configuration Texts
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['headline_index']  	= array('Täysteksti-indeksointi',
																'Tietoa täysteksti-indeksoinnista ja sen luomisesta.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['info_headline']   	= "Tietoa täysteksti-indeksoinnista";
$GLOBALS['TL_LANG']['tl_c4g_forum']['info']   	 	  	= array('Ensimmäinen indeksi',
																'Viimeinen total-indeksi',
																'Viimeisin indeksi',
																'Määrä indeksoituja sanoja');
$GLOBALS['TL_LANG']['tl_c4g_forum']['noIndex']    	    = "Indeksejä ei löydetty! Etsiminen ei toimi ilman indeksiä!";
$GLOBALS['TL_LANG']['tl_c4g_forum']['warning']     		= array("Kokonainen indeksointi kestää hetken. Tämä riippuu vahvasti foorumin sisällöstä.",
																"Ole kärsivällinen äläkä lähde sivustolta kun indeksiä luodaan!");
$GLOBALS['TL_LANG']['tl_c4g_forum']['success']			= "Index luotiin onnistuneesti.";
$GLOBALS['TL_LANG']['tl_c4g_forum']['fail']				= array("VIRHE: ",
																"Timeout luotaessa indeksiä!");





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