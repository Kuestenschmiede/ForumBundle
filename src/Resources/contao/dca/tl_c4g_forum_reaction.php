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

$GLOBALS['TL_DCA']['tl_c4g_forum_reaction'] = [
    'config' => [
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ],
        ],
    ],

    'fields' => [
        'id' => [
            'sql' => 'int unsigned NOT NULL auto_increment',
        ]
    ]
];