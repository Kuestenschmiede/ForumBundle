<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * con4gis - the gis-kit
 *
 * @version   php 5
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['name'] 			= array('Name',
																'Name of forum');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_names']           = array('Optional captions', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_name']            = array('Caption', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language']        = array('Language', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['headline'] 		= array('Headline',
																'Here you can add a headline to the forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headlines']       = array('Optional headlines', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headline']        = array('Headline', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['description'] 		= array('Description',
																'The description is displayed as a tooltip.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_descriptions']    = array('Optional descriptions', '');
$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_description']     = array('Description', '');

$GLOBALS['TL_LANG']['tl_c4g_forum']['published'] 		= array('Published',
																'Activate this to publish the forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['box_imagesrc'] 	= array('Image for box',
																'Image to be displayed in a box in the frontend module.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['use_intropage'] 	= array('Use intropage',
																'An intropage is a page where you can display information. It contains a link to the threadlist of the forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage'] 		= array('Content of intropage',
																'The content of the intropage. May also contain images and links.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn'] 	= array('Text on button to forum',
																	'If you enter text here a button is generated, which links to the threadlist of the forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn_jqui'] 	= array('Use jQuery UI Style for forum button',
																		'Activate this to get a jQuery UI button, or deactivated it to get a simple link.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['pretext'] 			= array('Text before threadlist / subforumlist',
																'This text is displayed right before the threadlist / subforumlist.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['posttext'] 		= array('Text after threadlist / subforumlist',
																'This text is displayed right after the threadlist /subforumlist.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_groups'] 	= array('Define member groups',
																'Check this to assign member groups to this forum. Otherwise the assignments are inherited from the parent forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_groups'] 	= array('Forum members',
																'Members of the selected member groups are assigned the permissions for forum members.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_groups'] 	= array('Forum administrators',
																'Members of the selected member groups are assigned the permissions for forum administrators.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['define_rights'] 	= array('Define permissions',
																'Check this to assign permissions to guests, forum members and forum administrators. Otherwise the assignments are inherited from the parent forum. If there are no permissions assigned at all, default permissions are applied.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['guest_rights'] 	= array('Guest permissions',
																'Define which actions guests are allowed to perform.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['member_rights'] 	= array('Forum member permissions',
																'Define which actions forum members are allowed to perform.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_rights'] 	= array('Forum administrator permissions',
																'Define which actions forum administrators are allowed to perform.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['enable_maps'] 		= array('Enable maps (requires con4gis-Maps)',
																'Check this to activate map functionality for this forum. Note that you also have to configure map functionality in the frontend module, and to assign sufficient rights to the members. Requires the Contao extension \'c4g_maps\' to be installed! ');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_type'] 		= array('Location type',
																'Define the type of locations that may be created inside the forum.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locationstyle']  	= array('Allow overriding the maps locationstyle',
																			'Checking this option allows the user to override the maps locationstyle with his/her popup-extension.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locstyles']  	= array('allowed Location styles',
																		'Check the location styles available for the users. Default: all');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_id'] 			= array('Basemap',
																'Select a map, which is defined in con4gis-Maps as base map for the choosen editor in posts, and for showing maps.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_location_label'] 	= array('Location label',
																	'Define a label to substitute the term "map location" in the frontend.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_label'] 		= array('Label',
																'Defines the source of a label, which is to be displayed at a location.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_tooltip'] 		= array('Tooltip',
																'Defines the source of a tooltip, which is to be displayed at a location.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_popup'] 		= array('Popup',
																'Defines the source of a popup, which is to be displayed on click at a location symbol.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['map_link'] 		= array('Link',
																'Defines the source of a link, which is to be jumped to on click at a location symbol.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['linkurl'] 			= array('Link to other page',
																'Please enter a link if you want to jump to another page when the forum is clicked.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['link_newwindow'] 	= array('Open links in new window',
																'Do not open links in the same window, but display the linked pages in a new window.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['sitemap_exclude'] 	= array('Exclude from XML sitemap',
																'Don\'t put the forum and its threads into the Google XML sitemap (XML sitemap is activated in the frontend module)');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['general_legend']	 	= 'General';
$GLOBALS['TL_LANG']['tl_c4g_forum']['language_legend'] 		= 'Language settings';
$GLOBALS['TL_LANG']['tl_c4g_forum']['comfort_legend']	 	= 'Boxes';
$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_legend']		= 'Intropage';
$GLOBALS['TL_LANG']['tl_c4g_forum']['infotext_legend'] 		= 'Information';
$GLOBALS['TL_LANG']['tl_c4g_forum']['groups_legend'] 		= 'Member groups';
$GLOBALS['TL_LANG']['tl_c4g_forum']['rights_legend'] 		= 'Permissions';
$GLOBALS['TL_LANG']['tl_c4g_forum']['maps_legend'] 			= 'Maps (con4gis)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['expert_legend']  		= 'Expert settings';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['new']    		= array('New forum', 'Create a new forum');
$GLOBALS['TL_LANG']['tl_c4g_forum']['edit']   		= array('Edit forum', 'Edit forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copy']   		= array('Duplicate forum', 'Duplicate forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['copyChilds']   = array('Duplicate forum with children', 'Duplicate forum ID %s with children');
$GLOBALS['TL_LANG']['tl_c4g_forum']['cut']    		= array('Move forum', 'Move forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['delete'] 		= array('Delete forum', 'Delete forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['toggle'] 		= array('Publish/unpublish forum', 'Publish/unpublish forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['show']   		= array('Details', 'Show details of forum ID %s');
$GLOBALS['TL_LANG']['tl_c4g_forum']['index']   		= array('Build Index', 'Builds the Index for the complete Forum');

/**
 * Links
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['build_index'] 				= array('Fulltext-Indexing', 'Configurate the Fulltextindex');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OFF']    	= 'Off';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUBJ']   	= 'Subject';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['CUST']   	= 'User definable at post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['POST']   	= 'Post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['SUPO']   	= 'Subject + post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['THREA']  	= 'Thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PLINK']  	= 'Post link';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['LINK']   	= 'Name of link';
// $GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  	= 'Extend Popup (ID(OSM)-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['OSMID']  	= 'Extend Popup (BETA)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['PICK']   	= 'Single point (GEO-Picker)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['references']['EDIT']   	= 'Points, lines, polygones (Editor)';

/**
 * Forum Rights
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_visible']        	= 'Forum visible';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadlist'] 		= 'Threadlist';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_readpost'] 			= 'Read posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newpost'] 			= 'Create new post in thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_newthread'] 			= 'Create new thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_postlink']			= 'Create links in posts';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threadsort']			= 'Thread sort field';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_threaddesc']			= 'Thread description';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownpost']		= 'Edit own post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editpost']			= 'Edit post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editownthread']		= 'Edit own thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_editthread']			= 'Edit thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delownpost']			= 'Delete own post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delpost']			= 'Delete post';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_delthread'] 			= 'Delete thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_movethread'] 		= 'Move thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribethread']	= 'Subscribe thread';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_subscribeforum'] 	= 'Subscribe forum';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_addmember'] 			= 'Add forum members';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapview'] 			= 'View maps (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 			= 'Edit maps (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapextend'] 			= 'Extend mapdata (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_mapedit'] 			= 'Edit maps: Location style';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_search'] 			= 'Search';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_latestthreads']		= 'Latest threads';
$GLOBALS['TL_LANG']['tl_c4g_forum']['right_alllanguages']	    = 'Edit all languages (multilingual)';

/**
 * Fulltext Indexing Configuration Texts
 */
$GLOBALS['TL_LANG']['tl_c4g_forum']['headline_index']  	= array('Fulltext-Indexing',
																'Information about the Fulltextindex and creating it.');
$GLOBALS['TL_LANG']['tl_c4g_forum']['info_headline']   	= "Information about the Fulltextindex";
$GLOBALS['TL_LANG']['tl_c4g_forum']['info']   	 	  	= array('First Index',
																'Last total-Index',
																'Last Index',
																'Number of indexed words');
$GLOBALS['TL_LANG']['tl_c4g_forum']['noIndex']    	    = "No Index found! Your search will not work without an Index!";
$GLOBALS['TL_LANG']['tl_c4g_forum']['warning']     		= array("A complete Indexing will take a while. This depends strongly on the amount of content of the Forum.",
																"Please be patient and do not leave this site while creating the Index!");
$GLOBALS['TL_LANG']['tl_c4g_forum']['success']			= "The Index was successfully created.";
$GLOBALS['TL_LANG']['tl_c4g_forum']['fail']				= array("ERROR: ",
																"Timeout while creating the Index!");





$GLOBALS['TL_LANG']['tl_c4g_forum']['default_subscription_text'] = <<<TEXT
Hello ##USERNAME##,

member '##RESPONSIBLE_USERNAME##' ##ACTION_PRE## ##ACTION_NAME_WITH_SUBJECT## to your subscribed thread '##THREADNAME##' in froum '##FORUMNAME##'


##POST_CONTENT##


To open the thread use the following link:
##DETAILS_LINK##

__________________________________________________________________________________________

To unsubscribe from the forum use this link:
##UNSUBSCRIBE_LINK##

To cancel all subscriptions please use this link:
##UNSUBSCRIBE_ALL_LINK##
TEXT;
?>