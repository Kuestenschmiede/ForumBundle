<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['name'] 					= array('Name', 'Name des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['state'] 			    = array('Status', 'Status des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['owner'] 			    = array('Besitzer', 'Besitzer des Tickets (Ersteller oder letzter Bearbeiter)');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['recipient'] 			= array('Empfänger', 'Empfänger des Tickets');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['delete']              = array('Ticket löschen','Löschen des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['new']                 = array('Ticket erstellen','Erstellen eines Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['edit']                = array('Ticket bearbeiten','Bearbeiten des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['copy']                = array('Ticket duplizieren', 'Duplizieren des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['delete']              = array('Ticket löschen','Löschen des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['show']                = array('Ticket zeigen','Zeigen des Tickets');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['post']                = array('Einträge zeigen','Zeigen die Einträge des Tickets');
/**
 * States
 */

$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][1]      = "Ungelesen";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][2]      = "Gelesen";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][3]      = "Geschlossen";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][4]      = "Neu";

?>