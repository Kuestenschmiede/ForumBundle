<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2026, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

/**
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum_thread'] = array
(

    // Config
    'config' => array
    (
        'dataContainer' => \Contao\DC_Table::class,
        'ptable' => 'tl_c4g_forum',
        'ctable' => array('tl_c4g_forum_post'),
        //'notCreatable'  => true,
        'sql'           => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        ),
        'onsubmit_callback' =>array(array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','saveDefaultThread')),
        'onload_callback'   =>array(array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'getThreadDatasets'))
    ),
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('creation'),
            'panelLayout'             => 'sort,filter;search,limit',
            'flag'                    => 6,
            'icon'                    => 'bundles/con4giscore/images/be-icons/con4gis_blue.svg',
        ),
        'label' => array
        (
            'fields'                  => array('name'),
            'label_callback'          => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','getThreadLabel'),
        ),
        'global_operations' => array
        (
            'all' => [
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ]/*,
            'back' => [
                'href'                => 'key=back',
                'class'               => 'header_back',
                'button_callback'     => ['\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'back'],
                'icon'                => 'back.svg',
                'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
            ],*/
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg',
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
            ),
            'post' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['post'],
                'href'                => 'do=c4g_forum_post&amp;table=tl_c4g_forum_post',
                'icon'	 		      => 'tablewizard.svg',
                'button_callback'     => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','forumPost')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array()
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array(''),
        'default'                     => '{title_legend},title,price;description,name,recipient,owner,state,creation;'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        ''                            => ''
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
//            'filter'                  => true,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'importId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'eval'                    => array('doNotCopy' => true)
        ),
        'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['name'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'state' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['state'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','getThreadOptions'),
//            'filter'                  => true,
            'search'                  => true,
            'eval'                    => array('includeBlankOption' => true, 'blankOptionLabel' => '-'),
            'sql'                     => "int(10) default '0'"
        ),
        'sort' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '999'"
        ),
        'threaddesc' => array
        (
            'sql'                     => "text NULL"
        ),
        'author' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'owner' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['owner'],
            'exclude'                 => true,
            'inputType'               => 'select',
//            'filter'                  => true,
            'search'                  => true,
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('maxlength'=>255, 'includeBlankOption'=>true, 'multiple'=>true, 'chosen'=>true),
            'sql'                     => "blob NULL"
        ),
        'recipient' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['recipient'],
            'exclude'                 => true,
            'inputType'               => 'select',
//            'filter'                  => true,
            'search'                  => true,
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('maxlength'=>255, 'includeBlankOption'=>true, 'multiple'=>true, 'chosen'=>true),
            'sql'                     => "blob NULL",
        ),
        'concerning' => array(
            'sql'                     => 'int(10) default "0"'
        ),
        'creation' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'",
            'default'                 => time(),
        ),
        'posts' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'last_post_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'edit_count' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'edit_last_author' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'edit_last_time' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tags' => array
        (
            'sql'                     => "blob NULL"
        )
    ),
);