<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 5
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

/**
 * Global settings
 */
$GLOBALS['con4gis']['forum']['installed'] = true;

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['con4gis']['c4g_forum'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForum';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_breadcrumb'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForumBreadcrumb';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_pncenter'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForumPNCenter';

/**
 * Backend modules
 */
array_insert($GLOBALS['BE_MOD']['con4gis_bricks'],1, array(
    'c4g_forum' => array
    (
        'tables' 		=> array('tl_c4g_forum'),
        'build_index' 	=> array('con4gis\ForumBundle\Resources\contao\classes\C4GForumBackend', 'buildIndex')
    ),
    'c4g_forum_thread' => array
    (
        'tables'        => array('tl_c4g_forum_thread'),
        'icon'	 		=> 'bundles/con4gisforum/icons/forumicon.png'
    ),
    'c4g_forum_post' => array
    (
        'tables'        => array('tl_c4g_forum_post'),
        'icon'	 		=> 'bundles/con4gisforum/icons/forumicon.png'
    )
));

	



/**
 * Add frontend form field for memberImage (Avatar)
 */
$GLOBALS['TL_FFL']['avatar'] = 'con4gis\ForumBundle\Resources\contao\widgets\Avatar';

/**
 * Add backend form field for memberImage (Avatar)
 */
$GLOBALS['BE_FFL']['avatar'] = 'con4gis\ForumBundle\Resources\contao\widgets\Avatar';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['removeOldFeeds'][] = array('con4gis\ForumBundle\Resources\contao\classes\C4GForumHelper','removeOldFeedsHook');

/**
 * CSS
 */
if(TL_MODE == "BE") {
    $GLOBALS['TL_CSS']['c4g_forum_backend'] = 'bundles/con4gisforum/css/c4gForumBackend.css';
}

/**
* Models
*/
$GLOBALS['TL_MODELS']['tl_c4g_forum']       = 'con4gis\ForumBundle\Resources\contao\models\C4gForumModel';