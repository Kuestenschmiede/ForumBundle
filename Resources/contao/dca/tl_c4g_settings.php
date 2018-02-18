<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */



$GLOBALS['TL_DCA']['tl_c4g_settings']['palettes']['default'] .= '{c4g_forum_legend},c4g_forum_type;';

$GLOBALS['TL_DCA']['tl_c4g_settings']['fields']['c4g_forum_type'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_c4g_settings']['c4g_forum_type'],
    'inputType' => 'select',
    'options'   => array('DISCUSSIONS', 'QUESTIONS', 'TICKET'),
    'default'   => 'DISCUSSIONS',
    'reference' => &$GLOBALS['TL_LANG']['tl_c4g_settings']['c4g_references'],
    'sql'       => "varchar(255) NOT NULL default 'DISCUSSIONS'"
);