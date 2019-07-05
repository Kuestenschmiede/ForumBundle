<?php
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
 * Global settings
 */
$GLOBALS['con4gis']['forum']['installed'] = true;

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['con4gis']['c4g_forum'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForum';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_breadcrumb'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForumBreadcrumb';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_pncenter'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GForumPNCenter';
$GLOBALS['FE_MOD']['con4gis']['c4g_forum_subscription'] = 'con4gis\ForumBundle\Resources\contao\modules\C4GSubscriptions';

/**
 * Backend modules
 */
array_insert($GLOBALS['BE_MOD'], array_search('con4gis_core', array_keys($GLOBALS['BE_MOD'])) + 2,
    ['con4gis_forum' => [
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
    ]]
);

//$GLOBALS['BE_MOD']['con4gis'] =
//    \con4gis\CoreBundle\Resources\contao\classes\C4GUtils::sortBackendModules($GLOBALS['BE_MOD']['con4gis']);

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
$GLOBALS['TL_MODELS']['tl_c4g_forum_subforum_subscription']       = \con4gis\ForumBundle\Resources\contao\models\C4GForumSubscriptionModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_forum_thread_subscription']       = \con4gis\ForumBundle\Resources\contao\models\C4GThreadSubscriptionModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_forum_thread']       = \con4gis\ForumBundle\Resources\contao\models\C4GThreadModel::class;

/**
 * Notification Center
 */


$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'] = array
(
    'sub_new_thread'   => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','threadname', 'forumname'),
        'email_text'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_html'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_sender_name'    => array('admin_email'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
    ),
    'sub_deleted_thread'  => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','threadname', 'forumname'),
        'email_text'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_html'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_sender_name'    => array('admin_email'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
    ),
    'sub_moved_thread'   => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','threadname', 'forumname'),
        'email_text'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_html'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_sender_name'    => array('admin_email'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
    ),
    'sub_new_post'   => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','threadname', 'forumname'),
        'email_text'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_html'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_sender_name'    => array('admin_email'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
    ),
    'sub_deleted_post'   => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','threadname', 'forumname'),
        'email_text'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_html'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_sender_name'    => array('admin_email'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
    ),
    'sub_edited_post'   => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','threadname', 'forumname'),
        'email_text'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_html'           => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
        'email_sender_name'    => array('admin_email'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'threadname','forumname', 'responsible_username', 'link', 'unsubscribe_link', 'unsubscribe_all_link',),
    ),
    'mail_new_pm'   => array
    (
        'recipients'           => array('admin_email','user_email'),
        'email_subject'        => array('admin_email','responsible_username','subject'),
        'email_text'           => array('user_name', 'responsible_username', 'link','message','subject'),
        'email_html'           => array('user_name', 'responsible_username', 'link','message','subject'),
        'email_sender_name'    => array('admin_email','responsible_username'),
        'email_sender_address' => array('admin_email'),
        'email_recipient_cc'   => array('admin_email'),
        'email_recipient_bcc'  => array('admin_email'),
        'email_replyTo'        => array('admin_email'),
        'file_content'         => array('user_name', 'responsible_username', 'link','message','subject'),
    )
);