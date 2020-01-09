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
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['subject']				= array('Subject', 'Subject of the post');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['text'] 			    = array('Text', 'Text of the post');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['delete']              = array('Delete Post','Delete the post');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['new']                 = array('Answer','Create a post');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['edit']                = array('Modify Post','Modify a post');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['copy']                = array('Duplicate Post', 'Duplicate a post');
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['show']                = array('Show Post','Show the post');

/**
 * States
 */
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][1]      = "Backoffice Unread";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][2]      = "Backoffice Read";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][3]      = "Closed";
$GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][4]      = "New";


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['description_legend'] = 'Post';
$GLOBALS['TL_LANG']['tl_c4g_forum_post']['state_change'] = 'State: ';

?>