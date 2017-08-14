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
    ),
);