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
        'importId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'eval'                    => array('doNotCopy' => true)
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
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'movedThread' => array
        (
            'sql'                     => "char(1) NOT NULL default '1'"
        ),
        'deletedThread' => array
        (
            'sql'                     => "char(1) NOT NULL default '1'"
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