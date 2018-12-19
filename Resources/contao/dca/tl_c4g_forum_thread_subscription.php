<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

/**
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum_thread_subscription'] = array
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
                'pid'    => 'index',
                'member' => 'index'
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
            'foreignKey'              => 'tl_c4g_forum_thread.name',
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'member' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'",
            'foreignKey'              => 'tl_member.username',
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'newPost' => array
        (
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'editedPost' => array
        (
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'deletedPost' => array
        (
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
    ),
);