<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
$GLOBALS['TL_DCA']['tl_c4g_forum_subforum_subscription'] = array
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
            'foreignKey'              => 'tl_c4g_forum.name',
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'member' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'",
            'foreignKey'              => 'tl_member.username',
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'thread_only' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'newThread' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'movedThread' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'deletedThread' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'newPost' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'editedPost' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'deletedPost' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
    ),
);