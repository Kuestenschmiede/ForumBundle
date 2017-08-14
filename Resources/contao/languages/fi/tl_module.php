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
        'Forum type',
        'Change type to change the wording. On informations threads are questions and posts are comments.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_size']              = array(
        'Koko (leveys, korkeus)',
        'Divisonin koko (DIV) jossa foorumi on näkyvissä. Koko on laskettu automaattisesti kun et laita arvoja tänne.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_scroll']            = array(
        'Scrollattavan alueen koko threadlistassa (leveys, koko)',
        'Jätä tyhjäksi jos et halua vierityspalkkia.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_startforum']        = array(
        'alkuperä',
        'Valitse parent foorumi aloittaaksesi. Jätä tyhjäksi nähdäksesi kaikki määritellyt foorumit.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_navigation']   = array(
        'Navigaatio',
        'Valitse navigaatio foorumille.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_threadclick']       = array(
        'Threadin klick toiminta',
        'Valitse toiminta joka suoritetaan kun threadia on klikattu.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_realname']     = array(
        'Käytä oikeita nimiä käyttäjänimien sijan',
        'Valitse jos ja kuinka haluat näyttää käyttäjien oikeat nimet käyttäjätunnusten sijan'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_postsort']          = array(
        'Viestien järjestys',
        'Valitse viestin järjestys viestien listalla threadissa.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_collapsible_posts'] = array(
        'Tee viesteistä kokoontaitettavia',
        'Valitse jos ja kuinka haluat viesteistä kokoontaitettavia.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb']        = array(
        'Näytä breadcrumbit',
        'Raksita tämä jos haluat breadcrumbit näytettäväksi.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_hide_intropages']   = array(
        'Piilota introsivut',
        'Raksita tämä piilottaaksesi introsivut siitä huolimatta että ne ovat määritelty. Tämä voi olla järkevää, jos haluat ymmärtää erilaisia näkemyksiä sinun foorumillasi useiden käyttöliittymien moduuleilla.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jumpTo']            = array(
        'Uudelleenohjaa sivu evätyllä pääsyllä',
        'Valitse sivu, johon kävijät ohjataan, kun luvan pyydettyä toimenpidettä ei myönnetä.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_language']          = array(
        'Frontendin-kieli',
        'Tyhjä=määrittää automaattisesti, de=German, en=English fi=finnish.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual']      = array(
        'multilingual entries',
        'you can fill out some fields in user language. With right language switch, you can change the language.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_languages'] = array(
        'Frontend languages for all multilanguages fields',
        'Select your languages.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_language'] = array(
        'Frontend languages',
        ''
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes']                       = array(
        'Käytä BBCodea',
        'Deaktivoi tämä checkbox, jos et halua käyttää BBCodea tällä foorumilla. Huomioi, että BBCoden deaktivointi sen jälkeen, kun sitä on jo käytetty, saattaa aiheuttaa muotoilu-virheitä.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_last_post_on_new'] = array("Näytä viimeisin viesti luo uusi kohdalla", "");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_enabled'] = array("Ota käyttöön arvostelu", "Ottaa käyttöön viiden tähden arvostelusysteemin kun viestejä kirjoitetaan.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_color'] = array("Arvostelutähtien väri", "Vaihtaa arvostelutähtien värin. Oletus: global tekstin väri");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_pn_button']                = array('Private messages','Show button for private messages');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor']                = array('Käytä WYSIWYG-Editoria', 'VAROITUS: Tämä ominaisuus toimii vain sulautetuilla foorumeilla!');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_toolbaritems']   = array('WYSIWYG-Editori tooltip napit', '');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imguploadpath']  = array('Kuvan latauskansio', 'Päätä minne ladatut kuvat pitäisi varastoida. Ylimääräinen kansio joka on nimetty päivämäärän mukaan on luotu tänne');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_fileuploadpath'] = array('Tiedoston latauskansio', 'Päätä minne ladatut tiedostot pitäisi varastoida. Ylimääräinen kansio joka on nimetty päivämäärän mukaan on luotu tänne');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_autourl'] = array(
        'Automaattisesti tunnista URL',
        'Tämä ominaisuus automaattisesti tunnistaa kirjoitetut URL ja muuntaa ne toimiviksi yhteyksiksi.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_text']     = array(
        'Box navigaatio: näytä foorumin nimi',
        'Raksista tämä näyttääksesi foorumin nimen box navigaatiossa.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_subtext']  = array(
        'Box navigaatio: näytä yksityiskohdat',
        'Raksita tämä näyttääksesi lasten määrän foorumissa, threadien määrän ja viestien määrän box navigaatiossa.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_lastpost'] = array(
        'Box navigaatio: Näytä viimeisimmän viestin tiedot',
        'Raksita tämä näyttääksesi tietoa liittyen viimeisimpään viestiin foorumilla.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_center']   = array(
        'Keski box navigaatio',
        'Check this to center the block containing the boxes.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui']                   = array(
        'Käytä jQuery UI:ta',
        'Älä raksita tätä deaktivoidaksesi jQuery UI:n kokonaan. Kokoelma ei ole ladattu ja kaikki jQuery UI:n riippuvaiset toiminnat ovat deaktivoitu!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_lib']               = array(
        'Lataa jQuery UI kokoelma',
        'Älä raksita tätä jos olet jo ladannut jQuery UI kokoelman jo itse: varmista että sinulla on yhteensopiva versio kokoelmasta!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_select']     = array(
        'jQuery UI ThemeRoller CSS theme',
        'Select a standart UI-Theme.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_src']        = array(
        'jQuery UI ThemeRoller CSS tiedosto',
        'Vaihtoehtoisesti: Valitse CSS tiedosto jonka olet luonut jQuery UI ThemeRollerilla.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogsize']             = array(
        'Dialogien koko (leveys, korkeus)',
        'Jätä tyhjäksi käyttääksesi oletusarvoja. Ei ole väliä jos käytät upotettua diaglogeja.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_buttons_jqui_layout']    = array(
        'Käytä jQuery UI Layouttia tooltip nappeja varten',
        'Raksita tämä käyttääksesi jQuery UI nappeja, muuten linkit luodaan.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jqui_layout'] = array(
        'Käytä jQuery UI layouttia breadcrumb nappeja varten',
        'Raksita tämä käyttääksesi jQuery UI nappeja, muuten linkit luodaan.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_table_jqui_layout']      = array(
        'Käytä jQuery UI Layouttia threadlistaa varten',
        'Raksita tämä käyttääksesi jQuery UI layouttia threadlistaa varten.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogs_embedded']       = array(
        'Upotetut dialogit',
        'Raksita tämä jos haluat dialogit upotettuna sivuun sen sijan että virtaa.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_embdialogs_jqui']        = array(
        'Käytä jQuery UI Layouttia',
        'Raksita tämä käyttääksesi jQuery UI Layouttia upotetuissa dialogeissa. '
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_posts_jqui']             = array(
        'Käytä jQuery UI Layouttia viesteissä',
        'Raksita tämä käyttääksesi jQuery UI Layouttia.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_jqui_layout']      = array(
        'Käytä jQuery UI Layouttia boksi navigaatiossa',
        'Raksita tämä käyttääksesi jQuery-UI CSS-luokkia muokataksesi navigaatio bokseja.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jquery_lib']       = array(
        'Lataa jQuery kokoelma',
        'Raksita tämä jos olet jo lataamassa jQuerya. Huomio: Varmista, että yhteensopiva versio on ladattu!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtable_lib']      = array(
        'Lataa jQuery DataTables kokoelma',
        'Älä raksita tätä jos et halua jQuery DataTablesia ladata! Huomio: Et voi käyttää threadlistaa jos jQuery DataTables ei ole saatavilla!'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqhistory_lib']    = array(
        'Lataa jQuery historia kokoelma',
        'Älä raksita tätä, jos et halua käyttää jQuery History.js funktionaallisuutta. Huomio: poistamalla tämän et voi käyttää backbuttonia foorumilla. Samalla selaimen URL kenttä ei ole päivitetty kun käytetään foorumin funktionallisuutta joten ei ole simppeliä linkin funktionalisuutta foorumeihin, threadeihin ja viesteihin.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtooltip_lib']    = array(
        'Lataa jQuery Tooltop kokoelma',
        'Älä raksita tätä deaktivoidaksesi jQuery Tooltip funktionaalisuuden.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqscrollpane_lib'] = array(
        'Lataa jScrollPane kokoelma',
        'Raksita tämä jos haluat käyttää muokattavaa scrollbarsia jQuery UI dialogeissa.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_enable_maps'] = array(
        'Ota käyttöön kartat (vaatii con4gis-Maps)',
        'Raksita tämä aktivoidaksesi kartan funktionaallisuuden yleisesti. Huomioi että sinun pitää myös konfiguroida foorumin kunnossapito. Vaatii Contao extensionin con4gis kartat asentuakseen! '
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_root'] = array("Kohdesivu sivukarttalinkkejä varten","");


    if (version_compare(VERSION, '3', '<')) {
        $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
            'Luo XML sivukartta',
            'Luo Google XML sivukartta juurihakemistossa.'
        );
    } else {
        $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
            'Luo XML sivukartta',
            'Luo Google XML sivukartta juurihakemistossa "share/".'
        );
    }
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_filename'] = array(
        'Sivukartan tiedostonimi',
        'Laita sivukartan tiedoston nimi ilman extensionia .xml.'
    );
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_contents'] = array(
        'Sivukartan sisältö',
        'Raksita sisällöt jotka haluat kirjoitetuksi sivukartta tiedostoon.'
    );

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jumpTo'] = array(
        'Uudelleenohjaa paikkaan',
        'Valitse sivu joka sisältää frontend moduulin foorumiin.'
    );


    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_uploadTypes'] = array("Sallitut tiedostotyypiy", "pilkulla eroteltuja ilman pistettä ");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_maxFileSize'] = array("Maksimi tiedostokoko", "Declaration in Byte: 1 kB = 1024 Byte, 1 MB = 1048576 Byte");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageWidth']  = array("Maksimi kuvan leveys", "Declaration in pixel");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageHeight'] = array("Maksimi kuvan korkeus", "Declaration in pixel");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_onlythreads'] = array("Checkbox for search threads only (no posts)", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_wholewords']  = array("Checkbox for whole words search", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_use_tags_in_search'] = array("Select for tags defined in your forum", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_forums']      = array("Select special forum", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_displayonly'] = array("Entry for searching usernames and periods", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_period']      = array("Periodsearch", "");

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_move_all'] = array("Move threads in other forum modules too (not recommend!)", "Just move threads between forum modules with same or similar settings!");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastperson'] = array("remove field -Last-", "Remove the -Last- field from table.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastdate'] = array("remove field -Last on-", "Remove the -Last on- field from table.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createperson'] = array("remove field -AUTHOR-", "Remove the -Author- field from table.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createdate'] = array("remove field -Created on-", "Remove the -Created on- field from table.");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_count'] = array("remove field -#-", "Remove the -#- field from table.");

    /**
     * Legend
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_general_legend'] = 'Foorumi - Yleinen';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_user_legend']         = 'Foorumi - User settings';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sizes_legend']        = 'Foorumi - Sizes';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_legend'] = 'Foorumi - Editor (BBCodes)';
    //$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pn_legend']      = 'Foorumi - Personal Messaging';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_legend']   = 'Foorumi - Boksi navigaatio asetukset';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_legend']    = 'Foorumi - Styling (jQuery UI)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_maps_legend']    = 'Foorumi - Kartat (con4gis-Maps)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_lib_legend']     = 'Foorumi - jQuery kokoelmat';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_legend'] = 'Foorumi - XML sivukartta';
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_legend']       = 'Foorumi - Search settings';

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_legend'] = 'Breadcrumb';

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_active'] = array("Aktivoi sivunumerointi", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_format'] = array("Sivunumerointi-muoto", "See <a onclick='window.open(this.href);return false;' href='http://www.xarg.org/2011/09/jquery-pagination-revised'>http://www.xarg.org/2011/09/jquery-pagination-revised</a>");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_perpage'] = array("Merkinnät per sivu", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_legend'] = 'Foorumi - Sivunumerointi';

    /**
     * References
     */
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['DISCUSSIONS'] = 'Discussions (threads & posts)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['QUESTIONS']   = 'Informations (questions & comments)';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['TREE']  = 'Puu';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['BOXES'] = 'Boksit';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREAD'] = 'Näytä kaikki threadin viestit';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FPOST']  = 'Näytä ensimmäinen viesti';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LPOST']  = 'Näytä viimeisin viesti';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['UU'] = 'Älä käytä oikeita nimiä (Käytä käyttäjänimiä sen sijan)';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FF'] = 'Käytä vain etunimeä';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LL'] = 'Käytä vain sukunimeä';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FL'] = 'Käytä etu- sekä sukunimeä';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['LF'] = 'Käytä suku- sekä etunimeä';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['UP'] = 'Vanhin viesti ensin';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['DN'] = 'Viimeisin viesti ensin';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['NC'] = 'Älä käytä kokoontaitettavia viestejä';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CO'] = 'Kaikkien viestien kokoontaitettavuus peruutettu';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CC'] = 'Kaikki viestit kokoontaitettiin';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CF'] = 'Ensimmäisen viestin kokoontaitettavuus peruutettu';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['CL'] = 'Viimeisimmän viestin kokoontaitettavuus peruutettu';

    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREADS'] = 'Julkiset threadit';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['FORUMS']  = 'Julkiset foorumit';
    $GLOBALS['TL_LANG']['tl_module']['c4g_references']['INTROS']  = 'Julkiset foorumit - Introsivut';

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

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip']                           = array("Tootip for threadlist", "");
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_first_post'] = "Ensimmäisen viestin otsikko";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_last_post']  = "Viimeisimmän viestin otsikko";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_first_post']  = "Ensimmäisen viestin sisältö";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_last_post']   = "Viimeisimmän viestin sisältö";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadtitle']      = "Threadin otsikko";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadbody']       = "Threadin kuvaus";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['disabled']         = "Poissa käytöstä";

    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor']                        = array('WYSIWYG-Editori', '');
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['ck']           = "Uusi WYSIWYG-Editori";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['bb']           = "Vanha WYSIWYG-Editor";
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['no']           = "Ei WYSIWYG-Editoria";

    $GLOBALS['TL_LANG']['tl_module']['c4g_appearance_themeroller_css'] = array('jQuery UI ThemeRoller CSS fille', 'select a jQuery UI CSS file.');
?>