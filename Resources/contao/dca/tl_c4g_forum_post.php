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
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum_post'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql'                         => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        )

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
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'text' => array
        (
            'sql'                     => "text NULL"
        ),
        'subject' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'tags' => array
        (
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'author' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'creation' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'forum_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'post_number' => array
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
        'linkname' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'linkurl' => array
        (
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'loc_osm_id' => array
        (
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'loc_geox' => array
        (
            'sql'                     => "varchar(20) NOT NULL default ''"
        ),
        'loc_geoy' => array
        (
            'sql'                     => "varchar(20) NOT NULL default ''"
        ),
        'loc_data_type' => array
        (
            'sql'                     => "char(10) NOT NULL default ''"
        ),
        'loc_data_content' => array
        (
            'sql'                     => "text NULL"
        ),
        'locstyle' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'loc_label' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'loc_tooltip' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'rating' => array
        (
            'sql'                     => "double NOT NULL default '0'"
        )
    ),
);