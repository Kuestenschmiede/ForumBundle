<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
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

$GLOBALS['TL_DCA']['tl_c4g_settings']['fields']['uploadPathImages']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_c4g_settings']['fields']['uploadPathDocuments']['eval']['mandatory'] = true;