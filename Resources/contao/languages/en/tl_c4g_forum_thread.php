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
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['name'] 					= array('Name', 'Name of the ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['state'] 			    = array('State', 'State of the ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['owner'] 			    = array('Owner', 'Owner of the ticket (Creator of the last answer)');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['recipient'] 			= array('Recipient', 'Recipient of the ticket');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['delete']              = array('Delete Ticket','Delete the ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['new']                 = array('Create Ticket','Create a ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['edit']                = array('Modify Ticket','Modify the ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['copy']                = array('Duplicate Ticket', 'Duplicate the ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['show']                = array('Show Ticket','Show the ticket');
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['post']                = array('Show Posts','Show the posts of the ticket');

/**
 * States
 */
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][1]      = "Backoffice Unread";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][2]      = "Backoffice Read";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][3]      = "Closed";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][4]      = "New";

$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['counter_caption_thread'] = '[Thread #';
$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['counter_caption_ticket'] = '[Ticket #';
?>