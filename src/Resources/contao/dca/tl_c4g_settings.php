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

Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('c4g_forum_legend', 'expert_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE, true)
    ->addField('c4g_forum_type', 'c4g_forum_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_c4g_settings');

$GLOBALS['TL_DCA']['tl_c4g_settings']['fields']['c4g_forum_type'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_c4g_settings']['c4g_forum_type'],
    'inputType' => 'select',
    'options'   => array('DISCUSSIONS', 'QUESTIONS', 'TICKET'),
    'default'   => 'DISCUSSIONS',
    'reference' => &$GLOBALS['TL_LANG']['tl_c4g_settings']['c4g_references'],
    'sql'       => "varchar(255) NOT NULL default 'DISCUSSIONS'"
);