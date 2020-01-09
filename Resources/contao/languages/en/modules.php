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
 * Back end modules
 */
//$GLOBALS['TL_LANG']['MOD']['con4gis'] 				= array( 'con4gis (construction kit)', 'www.con4gis.org' );

$GLOBALS['TL_LANG']['MOD']['c4g_forum'] 			= array( 'Structure', 'Administrate forums.' );
$GLOBALS['TL_LANG']['MOD']['c4g_forum_thread'] 	    = array( 'Thread list','Overview on tickets and threads');
$GLOBALS['TL_LANG']['MOD']['c4g_forum_post'] 	    = array( 'Overview Posts');
$GLOBALS['TL_LANG']['MOD']['con4gis_forum']              = ['con4gis '.$GLOBALS['con4gis']['version'] . ' - Forum', 'con4gis Forum modules.'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['c4g_forum'] 			= array('Forum (con4gis)',
															'This module allows you to integrate forums.');
$GLOBALS['TL_LANG']['FMD']['c4g_forum_breadcrumb'] 	= array('Forum (con4gis) - Breadcrumb',
															'This module allows you to integrate a forum breadcrumb on pages linked to the forum.');
$GLOBALS['TL_LANG']['FMD']['c4g_forum_pncenter'] 	= array('Forum (con4gis) - Private messages',
															'With this module you can integrate the private messages for your forum.');

