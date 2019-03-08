<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');
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

/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['c4g_forum'] 			= array( 'Struktur', 'Foren- und Ticketbereiche verwalten.' );
$GLOBALS['TL_LANG']['MOD']['c4g_forum_thread'] 	    = array( 'Themenliste','Übersicht von Tickets und Themen');
$GLOBALS['TL_LANG']['MOD']['c4g_forum_post'] 	    = array( 'Übersicht Einträge');
$GLOBALS['TL_LANG']['MOD']['con4gis_forum']              = ['con4gis '.$GLOBALS['con4gis']['version'] . ' - Forum', 'con4gis Foren-Module.'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['c4g_forum'] 			= array('Forum (con4gis)',
															    'Mit diesem Modul binden Sie ein Forum oder Ticketsystem ein.');
$GLOBALS['TL_LANG']['FMD']['c4g_forum_breadcrumb'] 	= array('Forum (con4gis) - Navigationspfad',
															    'Mit diesem Modul binden Sie einen Navigationspfad für, über ein Forum verlinkte, Seiten ein.');
$GLOBALS['TL_LANG']['FMD']['c4g_forum_pncenter']	= array('Forum (con4gis) - Persönliche Nachrichten',
															    'Mit diesem Modul binden Sie die persönlichen Nachrichten für Ihre Forenmitglieder ein.');

$GLOBALS['TL_LANG']['FMD']['c4g_forum_subscription']	= array('Forum (con4gis) - Abonnements',
    'Dieses Modul gibt erweiterten Zugriff auf Themen- und Forenbereich-Abonnements.');

?>