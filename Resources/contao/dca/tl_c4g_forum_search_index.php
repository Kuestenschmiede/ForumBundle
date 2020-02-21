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
$GLOBALS['TL_DCA']['tl_c4g_forum_search_index'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql'                         => array
        (
            'keys' => array
            (
                'si_id'      => 'primary',
                'si_sw_id'   => 'index',
                'si_sw_id,si_type,si_dest_id'  => 'unique'
            )
        )

    ),

    // Fields
    'fields' => array
    (
        'si_id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'si_sw_id' => array
        (
            'sql'=> "int(10) unsigned NOT NULL default '0'"
        ),
        'si_type' => array
        (
            'sql'=> "varchar(10) NOT NULL default 'threadhl'"
        ),
        'si_dest_id' => array
        (
            'sql'=> "int(10) NOT NULL default '0'"
        ),
        'si_count' => array
        (
            'sql'=> "smallint(5) unsigned NOT NULL default '0'"
        ),
        'importId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
    ),
);