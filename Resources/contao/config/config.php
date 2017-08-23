<?php

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
 * Global settings
 */
$GLOBALS['con4gis_forum_extension']['installed']    = true;
$GLOBALS['con4gis_forum_extension']['version']      = '2.0.4-snapshot';

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['con4gis']['c4g_forum'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForum';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_breadcrumb'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForumBreadcrumb';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_pncenter'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForumPNCenter';

/**
 * Backend modules
 */
array_insert($GLOBALS['BE_MOD']['con4gis_bricks'],2, array(
    'c4g_forum' => array
    (
        'tables' 		=> array('tl_c4g_forum'),
        'build_index' 	=> array('con4gis\ForumBundle\Resources\contao\classes\C4GForumBackend', 'buildIndex')
    )
));


/**
 * Add frontend form field for memberImage (Avatar)
 */
$GLOBALS['TL_FFL']['avatar'] = 'Avatar';

/**
 * Add backend form field for memberImage (Avatar)
 */
$GLOBALS['BE_FFL']['avatar'] = 'Avatar';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['removeOldFeeds'][] = array('con4gis\ForumBundle\Resources\contao\classes\C4GForumHelper','removeOldFeedsHook');

/**
 * Rest-API
 */
$GLOBALS['TL_API']['c4g_forum_ajax'] 		= 'con4gis\ForumBundle\Resources\contao\modules\api\C4gForumAjaxApi';
$GLOBALS['TL_API']['c4g_forum_pn_api'] 		= 'con4gis\ForumBundle\Resources\contao\modules\api\C4gForumPnApi';
$GLOBALS['TL_MODELS']['tl_c4g_forum']       = 'con4gis\ForumBundle\Resources\contao\models\C4gForumModel';