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
$GLOBALS['TL_DCA']['tl_c4g_forum_search_last_index'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql'                         => array
        (
            'keys' => array
            (
                'id'     => 'primary',
            )
        )

    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(5) unsigned NOT NULL default '1'"
        ),
        'importId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'eval'                    => array('doNotCopy' => true)
        ),
        'first' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'last_total_renew' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'last_index' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
    ),
);