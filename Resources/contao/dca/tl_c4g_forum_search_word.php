<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');
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
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum_search_word'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql'                         => array
        (
            'keys' => array
            (
                'sw_id'      => 'primary',
                //'sw_word'    => 'index',
                'sw_word'    => 'unique'
            )
        )

    ),

    // Fields
    'fields' => array
    (
        'sw_id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'sw_word' => array
        (
            'sql'=> "varchar(32) NOT NULL default ''"
        ),
        'importId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'eval'                    => array('doNotCopy' => true)
        ),
    ),
);