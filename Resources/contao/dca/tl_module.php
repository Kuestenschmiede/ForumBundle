<?php if (!defined('TL_ROOT')) {
    die('You can not access this file directly!');
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

// set imageSize options (Contao 3&4)
if (method_exists('\System', 'getContainer')) {
    //contao 4
    $imageSizes = \System::getContainer()->get('contao.image.image_sizes')->getAllOptions();
} else {
    //contao 3
    $imageSizes = array('px');
}

    /***
     * Palettes
     */
    $GLOBALS['TL_DCA']['tl_module']['palettes']['c4g_forum'] =
        '{title_legend},name,headline,type;' .
        '{c4g_forum_general_legend},c4g_forum_type,c4g_forum_startforum,c4g_forum_navigation,c4g_forum_threadclick,c4g_forum_postsort,c4g_forum_collapsible_posts,c4g_forum_breadcrumb,c4g_forum_hide_intropages,c4g_forum_jumpTo,c4g_forum_language,c4g_forum_multilingual,c4g_forum_tooltip,c4g_forum_show_last_post_on_new;' .
        '{c4g_forum_user_legend},c4g_forum_show_realname,c4g_forum_rating_enabled,c4g_forum_rating_color,c4g_forum_show_post_count,c4g_forum_show_avatars,c4g_forum_show_online_status,c4g_forum_show_ranks,c4g_forum_show_pn_button;'.
        '{c4g_forum_bbcodes_legend:hide},c4g_forum_bbcodes;' .
        '{c4g_forum_sizes_legend:hide},c4g_forum_size,c4g_forum_scroll;' .
        '{c4g_forum_pagination_legend:hide},c4g_forum_pagination_active,c4g_forum_pagination_perpage,c4g_forum_pagination_format;' .
        '{c4g_forum_search_legend:hide},c4g_forum_search_onlythreads, c4g_forum_search_wholewords, c4g_forum_use_tags_in_search, c4g_forum_search_forums, c4g_forum_search_displayonly;' .
        '{c4g_forum_boxes_legend:hide},c4g_forum_boxes_text,c4g_forum_boxes_subtext,c4g_forum_boxes_lastpost,c4g_forum_boxes_center;' .
        '{c4g_forum_jqui_legend:hide},c4g_forum_jqui;' .
        '{c4g_forum_lib_legend:hide},c4g_forum_jquery_lib,c4g_forum_jqtable_lib,c4g_forum_jqhistory_lib,c4g_forum_jqtooltip_lib,c4g_forum_jqscrollpane_lib;' .
        '{c4g_forum_sitemap_legend:hide},c4g_forum_sitemap;' .
        '{expert_legend:hide},guests,cssID,space,c4g_forum_remove_lastperson,c4g_forum_remove_lastdate,c4g_forum_remove_createperson,c4g_forum_remove_createdate,c4g_forum_remove_count,c4g_forum_move_all,c4g_forum_param_forumbox,c4g_forum_param_forum';

    $GLOBALS['TL_DCA']['tl_module']['palettes']['c4g_forum_breadcrumb'] =
        '{title_legend},name,type;' .
        '{c4g_forum_breadcrumb_legend},c4g_forum_breadcrumb_jumpTo;' .
        '{protected_legend:hide},protected;' .
        '{expert_legend:hide},guests,cssID,space';

    $GLOBALS['TL_DCA']['tl_module']['palettes']['c4g_forum_pncenter'] =
        '{title_legend},name,headline,type,c4g_forum_uitheme_css_select,c4g_appearance_themeroller_css;';

    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_bbcodes';
    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_jqui';
    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_sitemap';
    //$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_show_search';
    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_show_avatars';
    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_show_online_status';
    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_show_ranks';
    $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]  = 'c4g_forum_multilingual';

    //$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_tags']    = 'c4g_forum_sitemap_filename,c4g_forum_use_tags_in_search';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_jqui']    = 'c4g_forum_jqui_lib,c4g_forum_uitheme_css_select,c4g_forum_uitheme_css_src,c4g_forum_dialogsize,c4g_forum_dialogs_embedded,c4g_forum_embdialogs_jqui,c4g_forum_breadcrumb_jqui_layout,c4g_forum_buttons_jqui_layout,c4g_forum_table_jqui_layout,c4g_forum_posts_jqui,c4g_forum_boxes_jqui_layout,c4g_forum_enable_scrollpane';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_bbcodes'] = 'c4g_forum_editor, c4g_forum_bbcodes_editor_imguploadpath, c4g_forum_bbcodes_editor_fileuploadpath, c4g_forum_bbcodes_editor_toolbaritems, c4g_forum_bbcodes_editor_uploadTypes,c4g_forum_bbcodes_editor_maxFileSize,c4g_forum_bbcodes_editor_imageWidth, c4g_forum_bbcodes_editor_imageHeight'; //, c4g_forum_bbcodes_smileys,c4g_forum_bbcodes_smileys_url,c4g_forum_bbcodes_autourl';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_sitemap'] = 'c4g_forum_sitemap_filename,c4g_forum_sitemap_contents';
    //$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_show_search'] = ',c4g_forum_search_for, c4g_forum_search_onlythreads, c4g_forum_search_wholewords, c4g_forum_use_tags_in_search, c4g_forum_search_displayonly, c4g_forum_search_period;';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_show_avatars']       = 'c4g_forum_avatar_size';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_show_online_status'] = 'c4g_forum_member_online_time';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_show_ranks']         = 'c4g_forum_member_ranks';
    $GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_forum_multilingual']  = 'c4g_forum_multilingual_languages';

    /***
     * Fields - General
     */

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_type'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_type'],
        'exclude'   => true,
        'inputType' => 'select',
        'options'   => array('DISCUSSIONS', 'QUESTIONS', 'TICKET'),
        'default'   => 'DISCUSSIONS',
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'       => "varchar(12) NOT NULL default 'DISCUSSIONS'"

    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_size'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_size'],
        'exclude'   => true,
        'inputType' => 'imageSize',
        'options'   => $imageSizes,
        'eval'      => array('rgxp' => 'digit'),
        'sql'       => "varchar(255) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_scroll'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_scroll'],
        'exclude'   => true,
        'inputType' => 'imageSize',
        'options'   => $imageSizes,
        'eval'      => array('rgxp' => 'digit'),
        'sql'       => "varchar(255) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_startforum'] = array
    (
        'label'      => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_startforum'],
        'exclude'    => true,
        'inputType'  => 'select',
        'foreignKey' => 'tl_c4g_forum.name',
        'eval'       => array('includeBlankOption' => true, 'blankOptionLabel' => '-'),
        'sql'        => "int(10) unsigned NOT NULL default '0'"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_search_onlythreads'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_onlythreads'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_search_wholewords'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_wholewords'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_use_tags_in_search'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_use_tags_in_search'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_search_forums'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_forums'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_search_displayonly'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_displayonly'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

//    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_search_period'] = array
//    (
//        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_period'],
//        'exclude'   => true,
//        'default'   => true,
//        'inputType' => 'checkbox',
//        'sql'       => "char(1) NOT NULL default '1'"
//    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_pagination_active'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_active'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_pn_button'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_pn_button'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_pagination_perpage'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_perpage'],
        'exclude'   => true,
        'default'   => '10',
        'inputType' => 'text',
        'eval'      => array('maxlength' => 3),
        'sql'       => "tinyint(3) NOT NULL default '10'"
    );
    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_pagination_format'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_format'],
        'exclude'   => true,
        'default'   => '[< ncn >]',
        'inputType' => 'text',
        'eval'      => array('maxlength' => 255),
        'sql'       => "varchar(255) NOT NULL default '[< ncn >]'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_navigation'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_navigation'],
        'exclude'   => true,
        'inputType' => 'select',
        'options'   => array('BOXES', 'TREE'),
        'default'   => 'BOXES',
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'       => "varchar(10) NOT NULL default 'BOXES'"

    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_threadclick'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_threadclick'],
        'exclude'   => true,
        'inputType' => 'select',
        'options'   => array('THREAD', 'FPOST', 'LPOST'),
        'default'   => 'THREAD',
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'       => "char(6) NOT NULL default 'THREAD'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_realname'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_realname'],
        'exclude'   => true,
        'inputType' => 'radio',
        'options'   => array('UU', 'FF', 'LL', 'FL', 'LF'),
        'default'   => 'UU',
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'       => "char(2) NOT NULL default 'UU'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_postsort'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_postsort'],
        'exclude'   => true,
        'inputType' => 'radio',
        'options'   => array('UP', 'DN'),
        'default'   => 'UP',
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'       => "char(2) NOT NULL default 'UP'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_collapsible_posts'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_collapsible_posts'],
        'exclude'   => true,
        'inputType' => 'radio',
        'options'   => array('NC', 'CO', 'CC', 'CF', 'CL'),
        'default'   => 'NC',
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'       => "char(2) NOT NULL default 'NC'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_breadcrumb'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_hide_intropages'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_hide_intropages'],
        'exclude'   => true,
        'default'   => '',
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default ''"
    );
    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_rating_enabled'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_enabled'],
        'exclude'   => true,
        'default'   => '',
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default ''"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_rating_color'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_color'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => array('maxlength'=>6, 'multiple'=>false, 'size'=>1, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'wizard'),
        'sql'       => "varchar(64) NOT NULL default ''",
    );



    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_post_count'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_post_count'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_avatars'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_avatars'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('submitOnChange' => true),
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_avatar_size'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_avatar_size'],
        'exclude'   => true,
        'inputType' => 'imageSize',
        'options'   => $imageSizes,
        'eval'      => array('rgxp' => 'digit'),
        'sql'       => "varchar(255) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_online_status'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_online_status'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('submitOnChange' => true),
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_member_online_time'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_member_online_time'],
        'exclude'   => true,
        'default'   => '500',
        'inputType' => 'text',
        'eval'      => array('rgxp' => 'digit', 'maxlength' => 5),
        'sql'       => "int(10) unsigned NOT NULL default '300'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_ranks'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_ranks'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('submitOnChange' => true),
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_member_ranks'] = array
    (
        'label'			=> &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_member_ranks'],
        'exclude' 		=> true,
        'inputType'     => 'multiColumnWizard',
        'eval' 			=> array
        (
            'columnFields' => array
            (
                'rank_min' => array
                (
                    'label'                 => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_min'],
                    'exclude'               => true,
                    'inputType'             => 'text',
                    'eval' 			        => array('rgxp' => 'digit', 'style' => 'width: 100px')
                ),
                'rank_name' => array
                (
                    'label'                 => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_name'],
                    'exclude'               => true,
                    'inputType'             => 'text',
                    'eval' 			        => array('style' => 'width: 100px')
                ),
                'rank_language' => array
                (
                    'label'                 => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_language'],
                    'exclude'               => true,
                    'inputType'             => 'select',
                    'options'               => \System::getLanguages(),
                    'eval'                  => array('chosen' => true, 'style' => 'width: 120px')

                )
            )
        ),
        'sql'       => "blob NULL"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_show_last_post_on_new'] = array
    (
    	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_last_post_on_new'],
    	'exclude'                 => true,
    	'default'                 => '',
    	'inputType'               => 'checkbox',
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jumpTo'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jumpTo'],
        'exclude'   => true,
        'inputType' => 'pageTree',
        'eval'      => array('fieldType' => 'radio'),
        'sql'       => "int(10) unsigned NOT NULL default '0'"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_language'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_language'],
        'exclude'   => true,
        'default'   => '',
        'inputType' => 'text',
        'eval'      => array('maxlength' => 10, "style" => 'width: 100px'),
        'sql'       => "char(5) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_multilingual'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual'],
        'exclude'   => true,
        'default'   => '',
        'inputType' => 'checkbox',
        'eval'      => array('submitOnChange' => true),
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_multilingual_languages'] = array
    (
        'label'      => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_language'],
        'exclude'    => true,
        'default'    => array('de','en','fi'),
        'inputType'  => 'select',
        'options'    => \System::getLanguages(),
        'eval'       => array('multiple' => true, 'chosen' => true, 'style' => 'width: 120px'),
        'sql'        => "blob NULL"
    );

    /***
     * Fields - BBCodes
     */

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('submitOnChange' => true),
        'sql'       => "char(1) NOT NULL default '1'"

    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '0'"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_ckeditor'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_ckeditor'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_editor'] = array(
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor'],
        'exclude'   => true,
        'default'   => 'ck',
        'inputType' => 'radio',
        'options'   => array(
            'ck' => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['ck'],
            //'bb' => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['bb'],
            'no' => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['no'],
        ),
        'sql'       => "char(2) NOT NULL default 'ck'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_imguploadpath']  = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imguploadpath'],
        'exclude'   => true,
        'default'   => '',
//        'inputType' => 'text',
//        'eval'      => array('maxlength' => 128, "style" => 'width: 200px', 'trailingSlash' => true),
        'inputType'               => 'fileTree',
        'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox'),
        'sql'                     => "blob NULL"
//        'sql'       => "char(128) NOT NULL default ''"
    );
    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_fileuploadpath'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_fileuploadpath'],
        'exclude'   => true,
        'default'   => '',
//        'inputType' => 'text',
//        'eval'      => array('maxlength' => 128, "style" => 'width: 200px', 'trailingSlash' => true),
        'inputType'               => 'fileTree',
        'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox'),
        'sql'                     => "blob NULL"
//        'sql'       => "char(128) NOT NULL default ''"
    );

    if ((version_compare( VERSION, '4', '>=' ))) {
        $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_toolbaritems']   = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_toolbaritems'],
            'exclude'   => true,
            'default'   => 'Cut,Copy,Paste,PasteText,PasteFromWord,-,Undo,Redo,TextColor,Bold,Italic,Underline,Strike,Subscript,Superscript,-,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,-,RemoveFormat,NumberedList,BulletedList,Link,Unlink,Anchor,-,Image,Youtube,FileUpload,-,Table,Smiley,-,Maximize,Source',
            'inputType' => 'text',
            'eval'      => array('class' => '', 'style' => 'width:662px'),
            'sql'       => 'varchar(600) NOT NULL default "Cut,Copy,Paste,PasteText,PasteFromWord,-,Undo,Redo,TextColor,Bold,Italic,Underline,Strike,Subscript,Superscript,-,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,-,RemoveFormat,NumberedList,BulletedList,Link,Unlink,Anchor,-,Image,Youtube,FileUpload,-,Smiley,-,Maximize,Source"'
        );
    } else {
        $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_toolbaritems']   = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_toolbaritems'],
            'exclude'   => true,
            'default'   => 'Cut,Copy,Paste,PasteText,PasteFromWord,-,Undo,Redo,TextColor,Bold,Italic,Underline,Strike,Subscript,Superscript,-,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,-,RemoveFormat,NumberedList,BulletedList,Link,Unlink,Anchor,-,Image,Youtube,FileUpload,-,Table,Smiley,-,Maximize,Source',
            'inputType' => 'text',
            'eval'      => array('class' => '', 'style' => 'width:662px'),
            'sql'       => "varchar(600) NOT NULL default 'Cut,Copy,Paste,PasteText,PasteFromWord,-,Undo,Redo,TextColor,Bold,Italic,Underline,Strike,Subscript,Superscript,-,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,-,RemoveFormat,NumberedList,BulletedList,Link,Unlink,Anchor,-,Image,Youtube,FileUpload,-,Smiley,-,Maximize,Source'"
        );
    }

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_smileys'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_smileys'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_tooltip']         = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip'],
        'exclude'   => true,
        'default'   => 'body_first_post',
        'inputType' => 'select',
        'options'   => array(
            'title_first_post' => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_first_post'],
            'title_last_post'  => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_last_post'],
            'body_first_post'  => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_first_post'],
            'body_last_post'   => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_last_post'],
            'threadtitle'      => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadtitle'],
            'threadbody'       => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadbody'],
            'disabled'         => $GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['disabled']
        ),
        'sql'       => "varchar(50) NOT NULL default 'body_first_post'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_smileys_url'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_smileys_url'],
        'exclude'   => true,
        'default'   => 'src/con4gis/ForumBundle/Resources/public/images/smileys',
        'inputType' => 'text',
        'eval'      => array('maxlength' => 128, "style" => 'width: 200px'),
        'sql'       => "char(128) NOT NULL default 'system/modules/con4gis_core/assets/vendor/wswgEditor/images/smilies'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_autourl'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_autourl'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );


    /***
     * Fields - Boxes
     */


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_boxes_text'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_text'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'clr'),
        'sql'       => "char(1) NOT NULL default '1'"
    );


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_boxes_subtext'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_subtext'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_boxes_lastpost'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_lastpost'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_boxes_center'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_center'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default ''"
    );

    /***
     * Fields - jQuery UI
     */

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqui'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('submitOnChange' => true),
        'sql'       => "char(1) NOT NULL default '1'"

    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqui_lib'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_lib'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_uitheme_css_select'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_select'],
        'exclude'                 => true,
        'default'                 => 'base',
        'inputType'               => 'radio',
        'options'                 => array('base','black-tie','blitzer','cupertino','dark-hive','dot-luv','eggplant','excite-bike','flick','hot-sneaks','humanity','le-frog','mint-choc','overcast','pepper-grinder','redmond','smoothness','south-street','start','sunny','swanky-purse','trontastic','ui-darkness','ui-lightness','vader'),
        'eval'                    => array('mandatory'=>true, 'submitOnChange' => true),
        'reference'               => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'sql'                     => "char(100) NOT NULL default 'base'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_uitheme_css_src'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_src'],
        'exclude'   => true,
        'inputType' => 'fileTree',
        'eval'      => array('fieldType' => 'radio', 'files' => true, 'extensions' => 'css'),
        'sql'       => "binary(16) NULL"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_dialogsize'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogsize'],
        'exclude'   => true,
        'inputType' => 'imageSize',
        'options'   => $imageSizes,
        'eval'      => array('rgxp' => 'digit'),
        'sql'       => "varchar(255) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_dialogs_embedded'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogs_embedded'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_embdialogs_jqui'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_embdialogs_jqui'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_breadcrumb_jqui_layout'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jqui_layout'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_buttons_jqui_layout'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_buttons_jqui_layout'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_table_jqui_layout'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_table_jqui_layout'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_posts_jqui'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_posts_jqui'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_boxes_jqui_layout'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_jqui_layout'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'sql'       => "char(1) NOT NULL default '1'"
    );


    /***
     * Fields - Libraries
     */


    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jquery_lib'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jquery_lib'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'w50'),
        'sql'       => "char(1) NOT NULL default '1'"

    );

//    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqtable_lib'] = array
//    (
//        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtable_lib'],
//        'exclude'   => true,
//        'default'   => true,
//        'inputType' => 'checkbox',
//        'eval'      => array('tl_class' => 'w50'),
//        'sql'       => "char(1) NOT NULL default '1'"
//    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqtable_lib'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtable_lib'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'w50'),
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqhistory_lib'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqhistory_lib'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'w50'),
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqtooltip_lib'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtooltip_lib'],
        'exclude'   => true,
        'default'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'w50'),
        'sql'       => "char(1) NOT NULL default '1'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_jqscrollpane_lib'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqscrollpane_lib'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'w50'),
        'sql'       => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_sitemap'] = array
    (
        'label'         => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'],
        'exclude'       => true,
        'default'       => '',
        'inputType'     => 'checkbox',
        'eval'          => array('submitOnChange' => true),
        'save_callback' => array(array('tl_module_c4g_forum', 'update_sitemap')),
        'sql'           => "char(1) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_sitemap_filename'] = array
    (
        'label'         => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_filename'],
        'exclude'       => true,
        'inputType'     => 'text',
        'eval'          => array('mandatory' => true, 'maxlength' => 30),
        'save_callback' => array(array('tl_module_c4g_forum', 'update_sitemap')),
        'sql'           => "varchar(30) NOT NULL default ''"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_sitemap_contents'] = array
    (
        'label'         => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_contents'],
        'exclude'       => true,
        'inputType'     => 'checkbox',
        'options'       => array('THREADS', 'FORUMS', 'INTROS'),
        'reference'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_references'],
        'eval'          => array('multiple' => true),
        'save_callback' => array(array('tl_module_c4g_forum', 'update_sitemap')),
        'sql'           => "blob NULL"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_sitemap_root'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_root'],
        'exclude'   => true,
        'inputType' => 'pageTree',
        'eval'      => array('mandatory'=>true),
        'sql'       => "int(10) unsigned NOT NULL default '0'"
    );

    if ((version_compare( VERSION, '4', '>=' ))) {
        $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_uploadTypes'] = array(
            'label' => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_uploadTypes'],
            'inputType' => 'text',
            'default' => 'jpg,png,gif,zip,pdf',
            'eval' => array('tl_class' => 'w50'),
            'sql' => 'varchar(255) NOT NULL default "jpg,png,gif,zip,pdf"'
        );
    } else {
        $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_uploadTypes'] = array(
            'label' => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_uploadTypes'],
            'inputType' => 'text',
            'default' => 'jpg,png,gif,zip,pdf',
            'eval' => array('tl_class' => 'w50'),
            'sql' => "varchar(255) NOT NULL default 'jpg,png,gif,zip,pdf'"
        );
    }

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_maxFileSize'] = array(
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_maxFileSize'],
        'inputType' => 'text',
        'default'   => '2048000',
        'eval'      => array('mandatory' => true, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50'),
        'sql'       => "varchar(255) NOT NULL default '2048000'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_imageWidth'] = array(
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageWidth'],
        'inputType' => 'text',
        'eval'      => array('mandatory' => true, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50'),
        'sql'       => "varchar(10) NOT NULL default '800'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_bbcodes_editor_imageHeight'] = array(
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imageHeight'],
        'inputType' => 'text',
        'eval'      => array('mandatory' => true, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50'),
        'sql'       => "varchar(10) NOT NULL default '600'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_appearance_themeroller_css'] = array
    (
        'label'     => $GLOBALS['TL_LANG']['tl_module']['c4g_appearance_themeroller_css'],
        'exclude'   => true,
        'inputType' => 'fileTree',
        'eval'      => array('tl_class'=>'w50 wizard', 'fieldType'=>'radio', 'files'=>true, 'extensions'=>'css'),
        'sql'       => "binary(16) NULL"
    );

    /***
     * Fields - Breadcrumb Module
     */
    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_breadcrumb_jumpTo'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jumpTo'],
        'exclude'   => true,
        'inputType' => 'pageTree',
        'eval'      => array('fieldType' => 'radio', 'mandatory' => true),
        'sql'       => "int(10) unsigned NOT NULL default '0'"
    );

    /***
     * Fields without GUI
     */
    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_sitemap_updated'] = array
    (
        'sql' => "int(10) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_move_all'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_move_all'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class'=>'clr long'),
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_param_forumbox'] = array
    (
        'label'         => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forumbox'],
        'exclude'       => true,
        'default'       => 'forumbox',
        'inputType'     => 'text',
        'eval'          => array('mandatory' => true, 'maxlength' => 42),
        'sql'           => "varchar(30) NOT NULL default 'forumbox'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_param_forum'] = array
    (
        'label'         => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forum'],
        'exclude'       => true,
        'default'       => 'forum',
        'inputType'     => 'text',
        'eval'          => array('mandatory' => true, 'maxlength' => 42),
        'sql'           => "varchar(30) NOT NULL default 'forum'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_remove_lastperson'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastperson'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class'=>'clr long'),
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_remove_lastdate'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastdate'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class'=>'clr long'),
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_remove_createperson'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createperson'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class'=>'clr long'),
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_remove_createdate'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createdate'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class'=>'clr long'),
        'sql'       => "char(1) NOT NULL default '0'"
    );

    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_remove_count'] = array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_count'],
        'exclude'   => true,
        'default'   => false,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class'=>'clr long'),
        'sql'       => "char(1) NOT NULL default '0'"
    );

//    $GLOBALS['TL_DCA']['tl_module']['fields']['c4g_forum_enable_maps'] = array
//    (
//        'sql' => "char(1) NOT NULL default ''"
//    );

    /**
     * Class tl_module_c4g_forum
     */
    class tl_module_c4g_forum extends \Backend
    {
        public function update_sitemap($value, $dc)
        {

            if ($value != $dc->varValue) {
                // force update of sitemap in the frontend by setting last sitemap timestamp to 0
                $this->Database->prepare(
                    "UPDATE tl_module SET " .
                    "c4g_forum_sitemap_updated=0 " .
                    "WHERE id = " . $this->Input->get('id')
                )->executeUncached();

            }

            return $value;
        }
    }

?>