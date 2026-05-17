<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */


$GLOBALS['TL_DCA']['tl_c4g_forum_upload'] = [
    'config' => [
        'dataContainer' => \Contao\DC_Table::class,
        'ptable' => 'tl_c4g_forum_post',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index'
            ]
        ]
    ],
    'fields' => [
        'id' => [
            'sql' => "int unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'importId' => [
            'sql' => "int unsigned NOT NULL default 0"
        ],
        'fileUuid' => [
            'sql' => "binary(16) NULL"
        ]
    ]
];