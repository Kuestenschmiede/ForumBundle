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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['subject']				= array('Betreff', 'Betreff des Eintrags');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['text'] 			    = array('Text', 'Text des Beitrags');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['delete']              = array('Eintrag löschen','Löschen des Eintrags');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['new']                 = array('Antworten','Erstelle einen Beitrag');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['edit']                = array('Eintrag bearbeiten','Bearbeiten des Eintrags');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['copy']                = array('Eintrag duplizieren', 'Duplizieren des Eintrags');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['delete']              = array('Eintrag löschen','Löschen des Eintrags');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['show']                = array('Eintrag zeigen','Zeigen des Eintrags');


/**
 * States
 */

$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][1]      = "Ungelesen";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][2]      = "Gelesen";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][3]      = "Geschlossen";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][4]      = "Neu";

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['description_legend'] = 'Beitrag';
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['state_change'] = 'Statusänderung: ';

?>