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