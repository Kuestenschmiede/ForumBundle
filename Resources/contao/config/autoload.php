<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	#'c4g\Forum',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    
	// Classes
//	'c4g\Forum\C4GUtils'                    => __DIR__.'/../classes/C4GUtils.php',
//	'c4g\Forum\C4GForumBackend'             => __DIR__.'/../classes/C4GForumBackend.php',
//	'c4g\Forum\C4gForumSingleFileUpload'    => __DIR__.'/../classes/C4gForumSingleFileUpload.php',
//	'c4g\Forum\PN\Inbox' 		  			=> __DIR__.'/../classes/Inbox.php',
//	'c4g\Forum\PN\Compose' 		  			=> __DIR__.'/../classes/Compose.php',
//	'c4g\Forum\PN\View' 		  			=> __DIR__.'/../classes/View.php',
//
//	// Models
//	'c4g\Forum\C4gForumMember'              => __DIR__.'/../models/C4gForumMember.php',
//	'c4g\Forum\C4gForumModel'               => __DIR__.'/../models/C4gForumModel.php',
//	'c4g\Forum\C4gForumPost'                => __DIR__.'/../models/C4gForumPost.php',
//	'c4g\Forum\C4gForumSession'             => __DIR__.'/../models/C4gForumSession.php',
//	'c4g\Forum\C4gForumPn'                  => __DIR__.'/../models/C4gForumPn.php',
//
//	// Modules
//	'c4g\Forum\C4GForum'		            => __DIR__.'/../modules/C4GForum.php',
//	'c4g\Forum\C4GForumBreadcrumb' 		  	=> __DIR__.'/../modules/C4GForumBreadcrumb.php',
//	'c4g\Forum\C4GForumPNCenter'            => __DIR__.'/../modules/C4GForumPNCenter.php',
//
//	// api
//	'C4gForumAjaxApi'             => __DIR__.'/../modules/api/C4gForumAjaxApi.php',
//	'C4gForumPnApi' 	          => __DIR__.'/../modules/api/C4gForumPnApi.php',
//
//	// Widgets
//	'Avatar'                      => __DIR__.'/../widgets/Avatar.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_tag_widget'            => __DIR__.'/../templates/backend/widget',
	'c4g_subscription'         => __DIR__.'/../templates/mail',
	'member_grouped'           => __DIR__.'/../templates/member',
	'mod_c4g_forum'            => __DIR__.'/../templates',
	'mod_c4g_forum_breadcrumb' => __DIR__.'/../templates',
	'mod_c4g_forum_plainhtml'  => __DIR__.'/../templates',
	'mod_c4g_forum_pncenter'   => __DIR__.'/../templates',
	'forum_user_data'          => __DIR__.'/../templates/partials',
	'modal_inbox'          	   => __DIR__.'/../templates/',
	'modal_compose'            => __DIR__.'/../templates/',
	'modal_view_message'       => __DIR__.'/../templates/',
));
