<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
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