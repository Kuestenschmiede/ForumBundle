<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */

/**
     * Table tl_c4g_forum_pn
     */
    $GLOBALS['TL_DCA']['tl_c4g_forum_pn'] = array
    (

        // Config
        'config' => array
        (
            'dataContainer'               => 'Table',
            'enableVersioning'            => true,
//            'onsubmit_callback'             => array(
//                array('\c4g\Core\C4GAutomator', 'purgeApiCache')
//            ),
            'sql' => array
            (
                'keys' => array
                (
                    'id' => 'primary'
                )
            )
        ),

        // List
        'list' => array
        (
            'sorting' => array
            (
                'mode'                    => 1,
                'fields'                  => array(''),
                'flag'                    => 1,
                'icon'                    => 'bundles/con4giscore/images/be-icons/con4gis.org_dark.svg',
            ),
            'label' => array
            (
                'fields'                  => array(''),
                'format'                  => '%s'
            ),
            'global_operations' => array
            (
                'all' => array
                (
                    'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                    'href'                => 'act=select',
                    'class'               => 'header_edit_all',
                    'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
                )
            ),
            'operations' => array
            (
                'edit' => array
                (
                    'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['edit'],
                    'href'                => 'act=edit',
                    'icon'                => 'edit.svg',
                ),
                'copy' => array
                (
                    'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['copy'],
                    'href'                => 'act=copy',
                    'icon'                => 'copy.svg'
                ),
                'delete' => array
                (
                    'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['delete'],
                    'href'                => 'act=delete',
                    'icon'                => 'delete.svg',
                    'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
                ),
                'show' => array
                (
                    'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['show'],
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
            'default'                     => '{title_legend},title;'
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
            'tstamp' => array(
                'sql'                     => "int(10) unsigned NOT NULL default '0'"
            ),
            'sender_id' => array(
                'sql'                     => "int(10) unsigned NOT NULL default '0'"
            ),
            'recipient_id' => array(
                'sql'                     => "int(10) unsigned NOT NULL default '0'"
            ),
            'subject' => array(
                'sql'                     => "varchar(255) NOT NULL default ''"
            ),
            'status' => array(
                'sql'                     => "char(1) NOT NULL default '0'"
            ),
            'message' => array(
                'sql'                     => "longtext NULL"
            ),
            'dt_created' => array(
                'sql'                     => "int(10) unsigned NOT NULL default '0'"
            )
        )
    );
