<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Classes;

if (!function_exists('array_insert')) {
    /**
     * @param array $array
     * @param int   $position
     * @param array $insert
     */
    function array_insert(&$array, $position, $insert)
    {
        array_splice($array, $position, 0, $insert);
    }
}

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\CoreBundle\Classes\C4GVersionProvider;
use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\ForumBundle\Models\C4gForumMember;
use con4gis\ForumBundle\Models\C4gForumModel;
use con4gis\ForumBundle\Modules\C4GForum;
use Contao\Database;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;

class C4GForumHelper extends System
{
    public static $postIdToIgnoreInMap = 0;

    protected $Database = null;
    protected $Environment = null;
    protected $ForumName = null;
    public $User = null;
    protected $ForumCache = [];
    protected $checkGuestRights = false;
    protected $show_realname = 'UU';

    /**
     * @param $statement
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    protected function dbExecute($statement, $params = [])
    {
        if (empty($params)) {
            return $statement->execute();
        }
        return $statement->execute(...$params);
    }

    /**
     * @var C4GForumSubscription
     */
    public $subscription = null;

    public $frontendUrl = null;

    public $permissionError = '';

    /**
     * Konstruktor
     */
    public function __construct($database = null, $environment = null, $user = null, $forumName = '', $frontendUrl = '', $show_realname = 'UU', $forumType = 'FORUM')
    {
        $this->subscription = new \con4gis\ForumBundle\Classes\C4GForumSubscription($this, $database, $environment, $user, $forumName, $frontendUrl, $forumType);
            $this->Database = \Contao\Database::getInstance();
        $this->User = $user;
        $this->Environment = $environment;
        $this->frontendUrl = $frontendUrl;
        if ($forumName == '') {
            $this->ForumName = $this->getTypeText($forumType, 'FORUM');
        } else {
            $this->ForumName = $forumName;
        }
        $this->show_realname = $show_realname;
    }

    /**
     * Reset fields forum_id and post_number in table tl_c4g_forum_post
     */
    public function recalculatePostHelperData()
    {
        $objSelect = $this->Database->prepare(
            'SELECT a.id,a.forum_id,b.pid as forum_id_new, a.post_number, count(c.id) AS post_number_new FROM tl_c4g_forum_post a, tl_c4g_forum_thread b, tl_c4g_forum_post c ' .
            'WHERE b.id = a.pid AND c.pid = a.pid AND c.id <= a.id ' .
            'GROUP BY a.id ' .
            'HAVING	forum_id <> forum_id_new OR post_number <> post_number_new');
        $objSelect = $this->dbExecute($objSelect);
        while (($objSelectRow = $objSelect->fetchAssoc()) !== false) {
            $set = [];
            $set['forum_id'] = $objSelectRow['forum_id_new'];
            $set['post_number'] = $objSelectRow['post_number_new'];
            if (!empty($set)) {
                $sqlSet = [];
                $params = [];
                foreach ($set as $k => $v) {
                    $sqlSet[] = "`$k`=?";
                    $params[] = $v;
                }
                $params[] = $objSelectRow['id'];
                $this->Database->prepare("UPDATE tl_c4g_forum_post SET " . implode(', ', $sqlSet) . " WHERE id=?")
                                                ->execute(...$params);
            }
        }
    }

    /**
     * Reset fields posts and last_post_id in table tl_c4g_forum_post
     */
    public function recalculateThreadHelperData()
    {
        $objSelect = $this->Database->prepare(
            'SELECT a.id,a.posts,a.last_post_id, max(b.id) AS last_post_id_new,count(b.id) AS posts_new ' .
            'FROM tl_c4g_forum_thread a, tl_c4g_forum_post b ' .
            'WHERE b.pid = a.id ' .
            'GROUP BY a.id ' .
            'HAVING a.posts <> posts_new OR a.last_post_id <> last_post_id_new');
        $objSelect = $this->dbExecute($objSelect);

        while (($objSelectRow = $objSelect->fetchAssoc()) !== false) {
            $this->updateThreadHelperData($objSelectRow['id'], $objSelectRow['posts_new'], $objSelectRow['last_post_id_new']);
        }
    }

    /**
     * Reset fields threads, posts and last_thread_id in table tl_c4g_forum
     */
    public function recalculateForumHelperData()
    {
        $objSelect = $this->Database->prepare(
            'SELECT a.id,a.threads,a.last_thread_id, a.posts, a.last_post_id, max(b.id) AS last_thread_id_new,count(b.id) AS threads_new,sum(b.posts) AS posts_new, max(b.last_post_id) AS last_post_id_new ' .
            'FROM tl_c4g_forum a ' .
            'LEFT JOIN tl_c4g_forum_thread b ON (b.pid = a.id) ' .
            'GROUP BY a.id ' .
            'HAVING a.posts <> posts_new OR a.last_post_id <> last_post_id_new OR ' .
            'a.threads <> threads_new OR a.last_thread_id <> last_thread_id_new');
        $objSelect = $this->dbExecute($objSelect);

        while (($objSelectRow = $objSelect->fetchAssoc()) !== false) {
            $this->updateForumHelperData($objSelectRow['id'], $objSelectRow['threads_new'], $objSelectRow['last_thread_id_new'],
                $objSelectRow['posts_new'], $objSelectRow['last_post_id_new']);
        }
    }

    /**
     * Recalculate all fields which contain redundant data for SQL request simplification
     */
    public function recalculateHelperData()
    {
        $this->recalculatePostHelperData();
        $this->recalculateThreadHelperData();
        $this->recalculateForumHelperData();
    }

    /**
     * Return time and date as String from an integer Date/Time
     */
    public function getDateTimeString($intDateTime)
    {
        $date = new \Contao\Date($intDateTime);

        return $date->datim;
    }

    /**
     * Checks Forum-Permission for current user
     * @param $right
     * @param $memberGroups
     * @param $adminGroups
     * @param $guestRights
     * @param $memberRights
     * @param $adminRights
     * @param int $userId
     * @return bool
     */
    public function checkPermissionWithData($right, $memberGroups, $adminGroups, $guestRights, $memberRights, $adminRights, $userId = 0)
    {
        $hasPermission = false;
        $rights = StringUtil::deserialize($guestRights, true);
        if ((C4GUtils::isFrontendUserLoggedIn()) && (!$this->checkGuestRights)) {
            if (($userId != 0) && (($this->User->id ?? null) != $userId)) {
                $userGroups = StringUtil::deserialize($this->Database->prepare(
                    'SELECT `groups` FROM tl_member  ' .
                    'WHERE id=?')
                    ->execute((int)$userId)->fetchAssoc()['groups'] ?? null, true);
            } else {
                $userGroups = $this->User->groups ?? [];
            }

            if (empty($userGroups)) {
                $rights = StringUtil::deserialize($guestRights, true);
            } elseif (($adminGroups) && (count(array_intersect($userGroups, StringUtil::deserialize($adminGroups, true))) > 0)) {
                $rights = StringUtil::deserialize($adminRights, true);
            } elseif (($memberGroups) && (count(array_intersect($userGroups, StringUtil::deserialize($memberGroups, true))) > 0)) {
                $rights = StringUtil::deserialize($memberRights, true);
            } else {
                // logged in, but not in any allowed group -> fallback to guest rights
                $rights = StringUtil::deserialize($guestRights, true);
            }
        } else {
            // not logged in: newpost and newthread not possible at all
            switch ($right) {
                case 'newpost':
                case 'newthread':
                    $this->permissionError = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['USER_NOT_LOGGED_IN'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['USER_NOT_LOGGED_IN'] ?? 'User not logged in');

                    return false;
            }
        }

        if ($right === 'visible' && empty($rights)) {
            return true;
        }

        if (
            !is_array($GLOBALS['TL_LANG'] ?? null) ||
            !array_key_exists('C4G_FORUM', $GLOBALS['TL_LANG']) ||
            !is_array($GLOBALS['TL_LANG']['C4G_FORUM']) ||
            (!array_key_exists('DISCUSSION', $GLOBALS['TL_LANG']['C4G_FORUM']) && !array_key_exists('DISCUSSIONS', $GLOBALS['TL_LANG']['C4G_FORUM']))
        ) {
            System::loadLanguageFile('frontendModules');
        }

        if (is_array($rights) && (array_search($right, $rights) !== false)) {
            return true;
        }
        $this->permissionError = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['NO_PERMISSION'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['NO_PERMISSION'] ?? 'No permission');

        return false;
    }

    /**
     * Check the permission for the given right
     * @param $forumId
     * @param $right
     * @param int $userId
     * @return bool
     */
    public function checkPermission($forumId, $right, $userId = 0)
    {
        if (isset($this->ForumCache[$forumId])) {
            $forum = $this->ForumCache[$forumId];
        } else {
            $forum = $this->Database->prepare(
                'SELECT id, member_groups, admin_groups, guest_rights, member_rights, admin_rights, pid FROM tl_c4g_forum WHERE id=?')
                                     ->execute((int)$forumId)->fetchAssoc();
            if ($forum === false || empty($forum)) {
                return false;
            }
            while ($forum && ($forum['pid'] ?? 0)) {
                $pForum = $this->Database->prepare(
                    'SELECT id, member_groups, admin_groups, guest_rights, member_rights, admin_rights,pid FROM tl_c4g_forum WHERE id=?')
                    ->execute((int)$forum['pid'])->fetchAssoc();
                if ($pForum === false || empty($pForum)) {
                    break;
                }
                if (!($forum['guest_rights'] ?? null)) {
                    $forum['guest_rights'] = $pForum['guest_rights'];
                }
                if (!($forum['admin_rights'] ?? null)) {
                    $forum['admin_rights'] = $pForum['admin_rights'];
                }
                if (!($forum['member_rights'] ?? null)) {
                    $forum['member_rights'] = $pForum['member_rights'];
                }
                $forum['pid'] = $pForum['pid'];
            }

            $this->ForumCache[$forumId] = $forum;
        }

        $database = Database::getInstance();
        $statement = $database->prepare(
            'SELECT published FROM tl_c4g_forum WHERE id = ?'
        );
        $row = $statement->execute((int)$forumId)->fetchAssoc();
        if ($row === false || ($row['published'] ?? 0) != 1) {
            $this->permissionError = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['NO_PERMISSION'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['NO_PERMISSION'] ?? 'No permission');
            return false;
        }

        $return = $this->checkPermissionWithData($right, $forum['member_groups'] ?? '', $forum['admin_groups'] ?? '',
            $forum['guest_rights'] ?? '', $forum['member_rights'] ?? '', $forum['admin_rights'] ?? '', $userId);

        return $return;
    }

    /**
     * Check the permission for the given action
     * @param $forumId
     * @param $action
     * @param null $userId
     * @param $paramForumbox
     * @param $paramForum
     * @return bool
     */
    public function checkPermissionForAction($forumId, $action, $userId = null, $paramForumbox, $paramForum)
    {
        return $this->checkPermission($forumId, $this->actionToRight($action, $paramForum), $userId);
    }

    /**
     * Determines the right needed to perform the given action
     * @param $action
     * @param $paramForum
     * @return string
     */
    public function actionToRight($action, $paramForum)
    {
        switch ($action) {
            case 'newthread':
            case 'sendthread':
            case 'previewthread':
            case 'cancelthread':
            case 'ticket':
                return 'newthread';

            case 'newpost':
            case 'sendpost':
            case 'previewpost':
            case 'cancelpost':
                return 'newpost';

            case 'readthread':
            case 'readpost':
            case 'readlastpost':
            case 'readpostnumber':
                return 'readpost';

            case 'delthreaddialog':
            case 'delthread':
                return 'delthread';

            case 'movethread':
            case 'movethreaddialog':
                return 'movethread';

            case 'delpost':
            case 'delpostdialog':
                return 'delpost';

            case 'delownpost':
            case 'delownpostdialog':
                return 'delownpost';

            case 'editpost':
            case 'editpostdialog':
            case 'previeweditpost':
                return 'editpost';

            case 'editownpost':
            case 'editownpostdialog':
            case 'previeweditownpost':
                return 'editownpost';

            case 'editownthread':
            case 'editownthreaddialog':
                return 'editownthread';

            case 'editthread':
            case 'editthreaddialog':
                return 'editthread';

            case 'postlink':
                return 'postlink';

            case 'addmember':
            case 'addmemberdialog':
                return 'addmember';

            case $paramForum:
            case 'forumintro':
                return 'threadlist';

            case 'subscribethread':
            case 'subscribethreaddialog':
                return 'subscribethread';

            case 'subscribesubforum':
            case 'subscribesubforumdialog':
                return 'subscribeforum';

            case 'viewmapforpost':
            case 'viewmapforforum':
                return 'mapview';

            default:
                return $action;
        }
    }

    /**
     * @param $iMemberId
     * @param array $aSize
     * @return null|string
     */
    public static function getAvatarByMemberId($iMemberId, $aSize = [100, 100])
    {
        $aSize[0] = ($aSize[0] > 0) ? $aSize[0] : 100;
        $aSize[1] = ($aSize[1] > 0) ? $aSize[1] : 100;

        $aImage = StringUtil::deserialize(C4gForumMember::getAvatarByMemberId($iMemberId), true);
        $sImage = $aImage[0] ?? null;
        if (!$sImage) {
            return null;
        }
        $sImagePath = \Contao\Controller::replaceInsertTags('{{image::' . $sImage . '?width=' . $aSize[0] . '&height=' . $aSize[1] . '&mode=crop}}');

        return $sImagePath;
    }

    /**
     * Returns all subforums of a given forum id from DB as array. 0 = root.
     * @param $id
     * @param bool $children
     * @param bool $flat
     * @param string $idField
     * @param bool $allModules
     * @return array
     */
    public function getForumsFromDB($id, $children = false, $flat = false, $idField = 'pid', $allModules = false)
    {
        $idArray = array_map('intval', self::deserializeIds($id));
        if (empty($idArray) || (count($idArray) === 1 && $idArray[0] === 0)) {
            $idSetMarkers = '0';
        } else {
            $idSetMarkers = implode(',', array_fill(0, count($idArray), '?'));
        }

        if ($idSetMarkers === '0' && ($idField !== 'pid' && $idField !== 'id')) {
            return [];
        }
        switch ($this->show_realname) {
            case 'FF':
                $sqlLastUser = 'm.firstname';

                break;
            case 'LL':
                $sqlLastUser = 'm.lastname';

                break;
            case 'FL':
                $sqlLastUser = 'CONCAT(m.firstname, " ", m.lastname)';

                break;
            case 'LF':
                $sqlLastUser = 'CONCAT(m.lastname, " ", m.firstname)';

                break;
            case 'UU':
            default:
                $sqlLastUser = 'm.username';

                break;
        }

        if ($allModules) {
            $forums = $this->Database->prepare(
                'SELECT a.id,a.name,a.optional_names,a.headline,a.optional_headlines,a.description,a.optional_descriptions,a.threads,a.posts,a.box_imagesrc,t.name AS last_threadname,p.creation AS last_post_creation, ' . $sqlLastUser . ' AS last_username, ' .
                'a.member_groups, a.admin_groups, a.guest_rights, a.member_rights, a.admin_rights,' .
                'a.use_intropage, a.intropage, a.intropage_forumbtn, a.intropage_forumbtn_jqui, a.linkurl,a.link_newwindow,a.sitemap_exclude,' .
                'a.pretext, a.posttext, a.enable_maps, a.enable_maps_inherited, a.map_profile, a.map_location_label, a.map_override_locationstyle, a.map_label, a.map_tooltip ' .
                ' FROM tl_c4g_forum a ' .
                'LEFT JOIN tl_c4g_forum_post p ON p.id = a.last_post_id ' .
                'LEFT JOIN tl_c4g_forum_thread t ON t.id = p.pid ' .
                'LEFT JOIN tl_member m ON m.id = p.author ' .
                'WHERE a.published=? ' .
                'GROUP BY a.id ' .
                'ORDER BY a.sorting'
            );
            $forums = $this->dbExecute($forums, [1]);
        } else {
            if ($idField === 'pid' && count($idArray) === 1 && $idArray[0] === 0) {
                $forums = $this->Database->prepare(
                    'SELECT a.id,a.name,a.optional_names,a.headline,a.optional_headlines,a.description,a.optional_descriptions,count(b.id) AS subforums,a.threads,a.posts,a.box_imagesrc,t.name AS last_threadname,p.creation AS last_post_creation, ' . $sqlLastUser . ' AS last_username, ' .
                    'a.member_groups, a.admin_groups, a.guest_rights, a.member_rights, a.admin_rights,' .
                    'a.use_intropage, a.intropage, a.intropage_forumbtn, a.intropage_forumbtn_jqui, a.linkurl,a.link_newwindow,a.sitemap_exclude,' .
                    'a.pretext, a.posttext, a.enable_maps, a.enable_maps_inherited, a.map_profile, a.map_location_label, a.map_override_locationstyle, a.map_label, a.map_tooltip ' .
                    ' FROM tl_c4g_forum a ' .
                    'LEFT JOIN tl_c4g_forum b ON (b.pid = a.id) AND (b.published = ?) ' .
                    'LEFT JOIN tl_c4g_forum_post p ON p.id = a.last_post_id ' .
                    'LEFT JOIN tl_c4g_forum_thread t ON t.id = p.pid ' .
                    'LEFT JOIN tl_member m ON m.id = p.author ' .
                    'WHERE (a.pid=? OR a.pid IS NULL OR a.pid=\'\' OR a.pid=0) AND a.published=? ' .
                    'GROUP BY a.id ' .
                    'ORDER BY a.sorting'
                );
                $forums = $this->dbExecute($forums, [1, 0, 1]);
            } else {
                $forums = $this->Database->prepare(
                    'SELECT a.id,a.name,a.optional_names,a.headline,a.optional_headlines,a.description,a.optional_descriptions,count(b.id) AS subforums,a.threads,a.posts,a.box_imagesrc,t.name AS last_threadname,p.creation AS last_post_creation, ' . $sqlLastUser . ' AS last_username, ' .
                    'a.member_groups, a.admin_groups, a.guest_rights, a.member_rights, a.admin_rights,' .
                    'a.use_intropage, a.intropage, a.intropage_forumbtn, a.intropage_forumbtn_jqui, a.linkurl,a.link_newwindow,a.sitemap_exclude,' .
                    'a.pretext, a.posttext, a.enable_maps, a.enable_maps_inherited, a.map_profile, a.map_location_label, a.map_override_locationstyle, a.map_label, a.map_tooltip ' .
                    ' FROM tl_c4g_forum a ' .
                    'LEFT JOIN tl_c4g_forum b ON (b.pid = a.id) AND (b.published = ?) ' .
                    'LEFT JOIN tl_c4g_forum_post p ON p.id = a.last_post_id ' .
                    'LEFT JOIN tl_c4g_forum_thread t ON t.id = p.pid ' .
                    'LEFT JOIN tl_member m ON m.id = p.author ' .
                    'WHERE a.' . $idField . ' IN(' . $idSetMarkers . ') AND a.published=? ' .
                    'GROUP BY a.id ' .
                    'ORDER BY a.sorting'
                );
                if ($idSetMarkers === '0') {
                    $forums = $this->dbExecute($forums, [1, 1]);
                } else {
                    $forums = $this->dbExecute($forums, array_merge([1], $idArray, [1]));
                }
            }
        }

        $return = [];
        $forumArr = $forums->fetchAllAssoc();
        $flatArray = [];
        foreach ($forumArr as $key => &$value) {
            $hasPermission = $this->checkPermissionWithData('visible', $value['member_groups'], $value['admin_groups'],
                $value['guest_rights'], $value['member_rights'], $value['admin_rights']);
            if ($hasPermission) {
                if ($children) {
                    if ($value['subforums'] > 0) {
                        if ($flat) {
                            $flatArray = array_merge($flatArray, $this->getForumsFromDB($value['id'], true, true, $idField, false));
                        } else {
                            $value['childs'] = $this->getForumsFromDB($value['id'], true, false, $idField, false);
                        }
                    }
                }

                $return[$key] = $value;
            }
        }
        if ($flat) {
            $return = array_merge($return, $flatArray);
        }

        return $return;
    }

    /**
     * @param int $startId
     * @param int $currentId
     * @param string $prefix
     * @param boolean $recCall
     * @return string
     */
    public function getForumsAsHTMLDropdownMenuFromDB($startId, $currentId, $prefix = '', $recCall = false)
    {
        if (!$recCall) {
            $return = '<select name="searchLocation" class="formdata ui-corner-all">' .
                '<option value="' . $startId . '">' . ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SEARCHDIALOG_DDL_STARTFORUM'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_STARTFORUM'] ?? 'Startforum')) . '</option>';
        } else {
            $return = '';
        }
        $forums = $this->Database->prepare(
                'SELECT a.id, a.name, a.optional_names, count(b.id) AS subforums, a.member_groups, a.admin_groups, a.guest_rights, a.member_rights, a.admin_rights' .
                ' FROM tl_c4g_forum a ' .
                'LEFT JOIN tl_c4g_forum b ON (b.pid = a.id) AND (b.published = ?) ' .
                'WHERE a.pid = ? AND a.published = ? ' .
                'GROUP BY a.id ' .
                'ORDER BY a.sorting'
        )->execute(1, $startId, 1);
        $forumArr = $forums->fetchAllAssoc();

        foreach ($forumArr as $forum) {
            //check permissions
            if ($this->checkPermissionWithData('visible', $forum['member_groups'], $forum['admin_groups'],
                                                $forum['guest_rights'], $forum['member_rights'], $forum['admin_rights'])
                &&
                $this->checkPermissionWithData('search', $forum['member_groups'], $forum['admin_groups'],
                            $forum['guest_rights'], $forum['member_rights'], $forum['admin_rights'])
            ) {
                $return .= '<option ';
                //is it the current Forum? Then select it by default.
                if ($forum['id'] == $currentId) {
                    $return .= 'selected ';
                }
                $return .= 'value="' . $forum['id'] . '">' . $prefix . $forum['name'] . '</option>';
                if ($forum['subforums'] > 0) {
                    $return .= $this->getForumsAsHTMLDropdownMenuFromDB($forum['id'], $currentId, ' &nbsp; ' . $prefix, true);
                }
            }
        }
        if (!$recCall) {
            $return .= '</select>';
        }

        return $return;
    }

    /**
     * @param $id
     * @param bool $children
     * @param string $idField
     * @return array
     */
    public function getForumsIdsFromDB($id, $children = false, $idField = 'pid')
    {
        $idArray = array_map('intval', self::deserializeIds($id));
        $idSetMarkers = implode(',', array_fill(0, count($idArray), '?'));

        if ($idSetMarkers === '') {
            return [];
        }
        $forums = $this->Database->prepare(
                'SELECT a.id, count(b.id) AS subforums,a.member_groups,a.admin_groups,a.guest_rights,a.member_rights,a.admin_rights ' .
                'FROM tl_c4g_forum a ' .
                'LEFT JOIN tl_c4g_forum b ON (b.pid = a.id) AND (b.published = ?) ' .
                'WHERE a.' . $idField . ' IN(' . $idSetMarkers . ') AND a.published=? ' .
                'GROUP BY a.id ' .
                'ORDER BY a.sorting'
        );
        $forums = $this->dbExecute($forums, array_merge([1], $idArray, [1]));
        $return = [];
        $forumArr = $forums->fetchAllAssoc();
        foreach ($forumArr as $forum) {
            if ($this->checkPermissionWithData('visible', $forum['member_groups'], $forum['admin_groups'],
                    $forum['guest_rights'], $forum['member_rights'], $forum['admin_rights'])) {
                $return[] = $forum['id'];

                if ($children) {
                    if ($forum['subforums'] > 0) {
                        $return = array_merge($return, $this->getForumsIdsFromDB($forum['id'], true));
                    }
                }
            }
        }

        return $return;
    }

    /**
     * @param $forumId
     * @return mixed|null
     */
    public function getForumFromDB($forumId)
    {
        $forum = $this->getForumsFromDB($forumId, false, false, 'id');
        if (is_array($forum) && !empty($forum)) {
            $values = array_values($forum);
            if (isset($values[0])) {
                return $values[0];
            }
        }
        return null;
    }

    /**
     * Returns all threads of a given forum id from DB as array.
     * @param $forumId
     * @return mixed
     */
    public function getThreadsFromDB($forumIds)
    {
        $idArray = array_map('intval', self::deserializeIds($forumIds));
        $forumIdMarkers = implode(',', array_fill(0, count($idArray), '?'));

        if ($forumIdMarkers === '') {
            return [];
        }

        switch ($this->show_realname) {
            case 'FF':
                $sqlAuthor = 'b.firstname AS username';
                $sqlLastUser = 'd.firstname';

                break;
            case 'LL':
                $sqlAuthor = 'b.lastname AS username';
                $sqlLastUser = 'd.lastname';

                break;
            case 'FL':
                $sqlAuthor = 'CONCAT(b.firstname, " ", b.lastname) AS username';
                $sqlLastUser = 'CONCAT(d.firstname, " ", d.lastname)';

                break;
            case 'LF':
                $sqlAuthor = 'CONCAT(b.lastname, ", ", b.firstname) AS username';
                $sqlLastUser = 'CONCAT(d.lastname, ", ", d.firstname)';

                break;
            case 'UU':
            default:
                $sqlAuthor = 'b.username';
                $sqlLastUser = 'd.username';

                break;
        }
        $threads = $this->Database->prepare(
                'SELECT a.id,a.name,a.threaddesc,' . $sqlAuthor . ',a.creation,a.sort,a.posts,' .
                       'c.creation AS lastPost, ' . $sqlLastUser . ' AS lastUsername, a.recipient,a.owner ' .
                'FROM tl_c4g_forum_thread a ' .
                'LEFT JOIN tl_member b ON b.id = a.author ' .
                'LEFT JOIN tl_c4g_forum_post c ON c.id = a.last_post_id ' .
                'LEFT JOIN tl_member d ON d.id = c.author ' .
                'WHERE a.pid IN(' . $forumIdMarkers . ')');
        $threads = $this->dbExecute($threads, $idArray);

        $aThreads = $threads->fetchAllAssoc();
        foreach ($aThreads as $key => $aThread) {
            if (empty($aThreads[$key]['username'])) {
                $aThreads[$key]['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETED_USER'] ?? 'Deleted User');
            }
        }

        return $aThreads;
    }

    /**
     * Returns all threads of a given forum id (including all subforums) from DB as array.
     * @param $forumId
     * @return mixed
     */
    public function getThreadsFromDBWithSubforums($forumId)
    {
        $idArray = array_map('intval', self::deserializeIds($forumId));
        $forumIds = $this->getForumsIdsFromDB($idArray, true);

        if (empty($forumIds)) {
            return $this->getThreadsFromDB($idArray);
        }

        $forumIdsArray = array_map('intval', self::deserializeIds($forumIds));
        $forumIdsMarkers = implode(',', array_fill(0, count($forumIdsArray), '?'));

        switch ($this->show_realname) {
            case 'FF':
                $sqlAuthor = 'b.firstname AS username';
                $sqlLastUser = 'd.firstname';

                break;
            case 'LL':
                $sqlAuthor = 'b.lastname AS username';
                $sqlLastUser = 'd.lastname';

                break;
            case 'FL':
                $sqlAuthor = 'CONCAT(b.firstname, " ", b.lastname) AS username';
                $sqlLastUser = 'CONCAT(d.firstname, " ", d.lastname)';

                break;
            case 'LF':
                $sqlAuthor = 'CONCAT(b.lastname, " ", b.firstname) AS username';
                $sqlLastUser = 'CONCAT(d.lastname, " ", d.firstname)';

                break;
            case 'UU':
            default:
                $sqlAuthor = 'b.username';
                $sqlLastUser = 'd.username';

                break;
        }

        $threads = $this->Database->prepare(
                'SELECT a.id,a.name,a.threaddesc,' . $sqlAuthor . ',a.creation,a.sort,a.posts,' .
                'c.creation AS lastPost, ' . $sqlLastUser . ' AS lastUsername ' .
                'FROM tl_c4g_forum_thread a ' .
                'LEFT JOIN tl_member b ON b.id = a.author ' .
                'LEFT JOIN tl_c4g_forum_post c ON c.id = a.last_post_id ' .
                'LEFT JOIN tl_member d ON d.id = c.author ' .
                'WHERE a.pid IN( ' . $forumIdsMarkers . ' ) ' .
                'ORDER BY c.creation DESC')
                ->limit(100);
        $threads = $this->dbExecute($threads, $forumIdsArray);

        $aThreads = $threads->fetchAllAssoc();
        foreach ($aThreads as $key => $aThread) {
            if (empty($aThreads[$key]['username'])) {
                $aThreads[$key]['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETED_USER'] ?? 'Deleted User');
            }
        }

        return $aThreads;
    }

    /**
     * does just what you think it would do
     * (!!!never call this if you don't plan to renew every single index!!!)
     */
    private function deleteAllTheIndexesFromDB()
    {
        $this->Database->prepare(
                'DELETE FROM tl_c4g_forum_search_index '
        )->execute();
        $this->Database->prepare(
                'DELETE FROM tl_c4g_forum_search_word '
        )->execute();
        $this->Database->prepare(
                'ALTER TABLE tl_c4g_forum_search_word AUTO_INCREMENT=1 '
        )->execute();
        $this->Database->prepare(
                'ALTER TABLE tl_c4g_forum_search_index AUTO_INCREMENT=1 '
        )->execute();
    }

    /**
     * deletes every Index and set it new
     * (this may take a while)
     */
    public function renewAllTheIndexesFromDB()
    {
        // set time limit for this function to 10 minutes
        set_time_limit(600);

        //check if it was the first update
        $wasFirst = false;
        $infoId = $this->Database->prepare(
                'SELECT id FROM tl_c4g_forum_search_last_index '
        )->executeUncached();
        $infoId = $infoId->fetchAllAssoc();
        if ($infoId[0]['id'] != 1) {
            $this->Database->prepare(
                    'INSERT INTO tl_c4g_forum_search_last_index ' .
                    '(id) VALUES ( 1 )'
            )->executeUncached();
            $wasFirst = true;
        }

        //delete all Indexes
        $this->deleteAllTheIndexesFromDB();

        //for every type
        //fetch every id of that type
        //create an index
        $types = [
                ['type' => 'threadhl', 'table' => 'tl_c4g_forum_thread'],
                ['type' => 'threaddesc', 'table' => 'tl_c4g_forum_thread'],
                ['type' => 'post', 'table' => 'tl_c4g_forum_post'],
        ];
        foreach ($types as $type) {
            $idSet = $this->Database->prepare(
                    'SELECT DISTINCT id FROM ' . $type['table']
            )->executeUncached();
            $idSet = $idSet->fetchAllAssoc();
            foreach ($idSet as $id) {
                $this->createIndex($type['type'], $id['id']);
            }
            unset($idSet, $id);
        }

        //update the additional index information
        $time = time();

        $sqlSet = [];
        $params = [];
        if ($wasFirst) {
            $sqlSet[] = 'first=?';
            $params[] = $time;
        }
        $sqlSet[] = 'last_total_renew=?';
        $params[] = $time;
        $sqlSet[] = 'last_index=?';
        $params[] = $time;

        $sql = 'UPDATE tl_c4g_forum_search_last_index SET ' . implode(', ', $sqlSet) . ' WHERE id = 1';
        $stmt = $this->Database->prepare($sql);
        if (count($params) === 1) {
            $stmt->execute($params[0]);
        } else {
            $stmt->execute(...$params);
        }

        //unset
        unset($time);
    }

    /**
     * deletes a thread from the DB
     * @param int $id
     */
    public function deleteThreadIndexFromDB($id)
    {
        $posts = $this->Database->prepare(
                'SELECT id FROM tl_c4g_forum_post ' .
                'WHERE pid = ? '
                )->executeUncached([$id]);
        $posts = $posts->fetchAllAssoc();

        $postIdSet = [];
        foreach ($posts as $post) {
            $postIdSet[] = $post['id'];
        }

        unset($posts);

        //delete all post-indexes
        $this->deleteIndexesFromDB('post', $postIdSet);

        //delete the headline-index for the thread
        $this->deleteIndexFromDB('threadhl', $id);

        //delete the description-index for the thread
        $this->deleteIndexFromDB('threaddesc', $id);
    }

    /**
     * (only delete an index if you want to delete the indexed row!!!)
     * @param string $type
     * @param string $idSet
     */
    public function deleteIndexesFromDB($type, $idSet)
    {
        if (is_array($idSet) && count($idSet) > 0) {
            $idSetArray = self::deserializeIds($idSet);
            if (empty($idSetArray) || (count($idSetArray) === 1 && $idSetArray[0] === 0)) {
                return;
            }
            $idSetMarkers = implode(',', array_fill(0, count($idSetArray), '?'));

            $this->Database->prepare(
                    'DELETE FROM tl_c4g_forum_search_index ' .
                    'WHERE si_type = ? ' .
                    'AND si_dest_id IN(' . $idSetMarkers . ')'
            )->execute(array_merge([$type], $idSetArray));
        }
    }

    /**
     * (only delete an index if you want to delete the indexed row!!!)
     * @param string $type (accepted type are 'threadhl', 'threaddesc' and 'post')
     * @param int $id
     */
    public function deleteIndexFromDB($type, $id)
    {
        $this->Database->prepare(
                'DELETE FROM tl_c4g_forum_search_index ' .
                'WHERE si_type = ? ' .
                'AND si_dest_id = ? '
                )->execute($type, $id);
    }

    /**
     * returns language values, if multilingual
     * @param $threadId
     * @param $fieldname
     * @param $language
     * @param string $initialvalue
     * @return string
     */
    public function translateThreadField($threadId, $fieldname, $language, $initialvalue = '')
    {
        $result = $this->Database->prepare(
            'SELECT value AS data FROM tl_c4g_forum_thread_translations WHERE pid = ? ' .
            'AND language = ? ' .
            'AND fieldname = ? '
        )->execute($threadId, $language, $fieldname)->data;

        if ($result) {
            return $result;
        }

        return $initialvalue;
    }

    /**
     * insert language value, if multilingual
     * @param $threadId
     * @param $fieldname
     * @param $language
     * @param $value
     * @return bool
     */
    public function insertLanguageEntryIntoDB($threadId, $fieldname, $language, $value)
    {
        $set = [];
        $set['pid'] = $threadId;
        $set['fieldname'] = $fieldname;
        $set['language'] = $language;
        $set['value'] = $value;

        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }

        $stmt = $this->Database->prepare("INSERT INTO tl_c4g_forum_thread_translations SET " . implode(', ', $sqlSet));
        if (count($params) === 1) {
            $objInsertStmt = $stmt->execute($params[0]);
        } else {
            $objInsertStmt = $stmt->execute(...$params);
        }

        if (!$objInsertStmt->affectedRows) {
            return false;
        }

        return $objInsertStmt->insertId;
    }

    /**
     * @param $threadId
     * @param $fieldname
     * @param $language
     * @param $value
     * @return bool
     */
    public function updateDBLanguageEntry($threadId, $fieldname, $language, $value)
    {
        $set = [];
        $set['pid'] = $threadId;
        $set['fieldname'] = $fieldname;
        $set['language'] = $language;
        $set['value'] = $value;

        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }
        $params[] = $threadId;
        $params[] = $language;
        $params[] = $fieldname;

        $objUpdateStmt = $this->Database->prepare(
            "UPDATE tl_c4g_forum_thread_translations SET " . implode(', ', $sqlSet) . " " .
            'WHERE pid = ? ' .
            'AND language = ? ' .
            'AND fieldname = ? ')
            ->execute(...$params);

        if (!$objUpdateStmt->affectedRows) {
            return false;
        }

        return true;
    }

    /**
     * @param $threadId
     * @return bool
     */
    public function deleteTranslationsForThread($threadId)
    {
        $objDeleteStmt = $this->Database->prepare('DELETE FROM tl_c4g_forum_thread_translations WHERE pid = ?')->execute($threadId);
        if ($objDeleteStmt->affectedRows == 0) {
            return false;
        }

        return true;
    }

    /**
     * creates an index for a specified tablerow
     * also use this for recreation
     * @param string $type (accepted type are 'threadhl', 'threaddesc' and 'post')
     * @param int $id
     */
    public function createIndex($type, $id)
    {
        //delete old index (so this can also be used for recreation)
        $this->deleteIndexFromDB($type, $id);

        //fetch data
        $dataSet = [];

        $srcTable = 'tl_c4g_forum_thread';
        $translTable = 'tl_c4g_forum_thread_translations';

        $inPost = false;
        switch ($type) {
            case 'threadhl':
                $srcCol = 'name';

                break;
            case 'threaddesc':
                $srcCol = 'threaddesc';

                break;
            case 'post':
                $srcTable = 'tl_c4g_forum_post';
                $srcCol = "CONCAT(subject, ' ', text, ' ', tags)";

                break;
            case 'tag':
                $srcTable = 'tl_c4g_forum_post';
                $srcCol = 'tags';

                break;
            case 'threadtag':
                $srcTable = 'tl_c4g_forum_thread';
                $srcCol = 'tags';

                break;
            default:
                //@TODO ERROR MESSAGE
                break;
        }

        $select = 'SELECT ' . $srcCol . ' AS data ' .
                'FROM ' . $srcTable . ' ' .
                'WHERE id = ? ';

        $dataSet = $this->Database->prepare(
                $select
        )->execute($id)->data;

        if (!empty($dataSet)) {
            //compress data
            if ($type == 'tag' || $type == 'threadtag') {
                $dataSet = C4GUtils::compressDataSetForSearch($dataSet, false, true, true, true);
                $dataSet = explode(',', $dataSet);
                $dataSet = array_map('trim', $dataSet);
            } else {
                $dataSet = C4GUtils::compressDataSetForSearch($dataSet);
                $dataSet = explode(' ', $dataSet);
            }
        }

        if ($type == 'threadhl') {
            $select_translations = 'SELECT value AS data ' .
                'FROM ' . $translTable . ' ' .
                'WHERE pid = ? ' .
                'AND fieldname = ? ';

            $dataTranslSet = $this->Database->prepare(
                $select_translations
            )->execute($id, $srcCol)->data;

            if (!empty($dataTranslSet)) {
                $dataTranslSet = C4GUtils::compressDataSetForSearch($dataTranslSet);
                $dataTranslSet = explode(' ', $dataTranslSet);

                if (empty($dataSet)) {
                    $dataSet = $dataTranslSet;
                } else {
                    $dataSet = array_merge($dataSet, $dataTranslSet);
                }
            }
        }

        if (empty($dataSet)) {
            return null;
        }

        //investigate data
        $indexDataSet = [];
        foreach ($dataSet as $data) {
            if ($data != '') {
                if (strlen($data) > 32) {
                    $data = substr($data, 0, 32);
                }

                $wordIndexId = $this->fetchAndSetIndexIdForWord($data);
                if (isset($indexDataSet[$wordIndexId])) {
                    $indexDataSet[$wordIndexId]['si_count'] = $indexDataSet[$wordIndexId]['si_count'] + 1;
                } else {
                    $indexDataSet[$wordIndexId]['si_sw_id'] = $wordIndexId;
                    $indexDataSet[$wordIndexId]['si_count'] = 1;
                }
            }
        }
        unset($dataSet);
        sort($indexDataSet);

        if (empty($indexDataSet)) {
            return null;
        }

        //create index
        $insertValues = [];
        $placeholders = [];
        foreach ($indexDataSet as $indexData) {
            $placeholders[] = '(?, ?, ?, ?)';
            $insertValues[] = $indexData['si_sw_id'];
            $insertValues[] = $type;
            $insertValues[] = $id;
            $insertValues[] = $indexData['si_count'];
        }

        $insert = 'INSERT INTO tl_c4g_forum_search_index ' .
                '(si_sw_id, si_type, si_dest_id, si_count) VALUES ' . implode(', ', $placeholders);
        
        $stmt = $this->Database->prepare($insert);
        $this->dbExecute($stmt, $insertValues);

        //free used vars
        unset($insert, $indexDataSet, $indexData);

        //update the additional index information
        $this->Database->prepare(
                'UPDATE tl_c4g_forum_search_last_index SET ' .
                'last_index=? ' .
                'WHERE id = 1 '
        )->execute(time());
    }

    /**
     * fetches the ids of similar word in tl_c4g_forum_search_word
     * @param string $word
     * @param boolean $andSet
     */
    public function fetchIndexIdForWord($word, $wholeWords = false)
    {
        $return = [];
        if ($wholeWords) {
            $wordIds = $this->Database->prepare(
                    'SELECT sw_id AS id FROM tl_c4g_forum_search_word ' .
                    'WHERE sw_word = ?'
            )->executeUncached([$word])->fetchAllAssoc();
        } else {
            $word = '%' . $word . '%';
            $wordIds = $this->Database->prepare(
                    'SELECT sw_id AS id FROM tl_c4g_forum_search_word ' .
                    'WHERE sw_word LIKE ( ? ) '
            )->executeUncached([$word])->fetchAllAssoc();
        }

        foreach ($wordIds as $wordId) {
            $return[] = $wordId['id'];
        }

        return $return;
    }

    /**
     * fetches the id of a word in tl_c4g_forum_search_word
     * creates a new entry if not found and returns the new id
     * @param string $word
     * @param boolean $andSet
     */
    public function fetchAndSetIndexIdForWord($word)
    {
        $wordId = $this->Database->prepare(
                'SELECT sw_id AS id FROM tl_c4g_forum_search_word ' .
                'WHERE sw_word = ? '
        )->executeUncached([$word])->id;

        //check if statement was successfull
        if ($wordId == null) {
            //create new entry and return the new id
            $stmt = $this->Database->prepare(
                    'INSERT INTO tl_c4g_forum_search_word (sw_word) ' .
                    'VALUES ( ? ) ');
            return $this->dbExecute($stmt, [$word])->insertId;
        }

        return $wordId;
    }

    /**
     * gives you back an array of threads, you were looking for
     * @param array $searchParam
     */
    public function searchSpecificThreadsFromDB($searchParam)
    {
        //define some vars
        $authorId = 0;
        $inPosts = false;
        $inHeadlines = false;
        $inDescriptions = false;

        $GLOBALS['c4gForumSearchParamCache']['search'] = '<div>' . ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SEARCH_TERM'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH_TERM'] ?? 'Search term')) . ': <b>' . $searchParam['search'] . '</b></div>';
        //prepare searchstring
        $search = C4GUtils::compressDataSetForSearch($searchParam['search']);
        if ($search == '') {
            // no search terms left? try to prepare without stripping stopwords...
            $search = C4GUtils::compressDataSetForSearch($searchParam['search'], false, true, true, true);
        }

        //explode searchstring
        $searchParam['search'] = explode(' ', $search);
        $searchParam['search'] = array_filter($searchParam['search']);

        $bFilterByTags = false;
        $bTagsOnly = false;
        // add tags to searchwords if present
        if (isset($searchParam['tags'])) {
            $searchParam['tags'] = self::deserializeIds($searchParam['tags']);
            if (!empty($searchParam['tags'])) {
                $bFilterByTags = true;
                $GLOBALS['c4gForumSearchParamCache']['search'] .= '<div>' . ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['TAGS'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['TAGS'] ?? 'Tags')) . ':<b> ' . implode(', ', $searchParam['tags']) . '</b></div>';
                if (empty($searchParam['search']) || $searchParam['onlyTags'] == 'true') {
                    $bTagsOnly = true;
                    $GLOBALS['c4gForumSearchParamCache']['search'] = '<div>' . ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['TAGS'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['TAGS'] ?? 'Tags')) . ': <b>' . implode(', ', $searchParam['tags']) . '</b></div>';
                }
            }
        }

        //check if still empty
        if (empty($searchParam['search']) && !$bTagsOnly) {
            $GLOBALS['c4gForumSearchParamCache']['search'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SEARCHRESULTPAGE_SEARCHTAGERROR'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_SEARCHTAGERROR'] ?? 'Tag error');

            return [];
        }

        //removes duplicates
        $searchParam['search'] = array_unique($searchParam['search']);
        //and everything that is not "true"
        $searchParam['search'] = array_filter($searchParam['search']);

        //check if author exists and get his id
        if ($searchParam['author'] != '') {
            $authorId = $this->Database->prepare(
                    'SELECT id FROM tl_member ' .
                    'WHERE username LIKE ? '
                    )->execute($searchParam['author'])->id;
            if ($authorId == null) {
                $GLOBALS['c4gForumSearchParamCache']['search'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SEARCHRESULTPAGE_SEARCHNOSUCHAUTHOR'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_SEARCHNOSUCHAUTHOR'] ?? 'No such author');

                return [];
            }
        }

        //create a typeset
        $typeSet = [];
        $typeSetParams = [];
        if ($searchParam['searchOnlyThreads'] != 'true') {
            $inPosts = true;
            $typeSet[] = '?';
            $typeSetParams[] = 'post';
        }

        // always search thread name
        $inHeadlines = true;
        $typeSet[] = '?';
        $typeSetParams[] = 'threadhl';

        // always search thread description
        $inDescriptions = true;
        $typeSet[] = '?';
        $typeSetParams[] = 'threaddesc';

        if ($bTagsOnly) {
            $typeSet = ['?'];
            $typeSetParams = ['tag'];
        } else {
            $typeSet[] = '?';
            $typeSetParams[] = 'tag';
        }

        $typeSetMarkers = implode(', ', $typeSet) ?: "'0'";

        //create wordIdSet
        $wordIdSet = [];
        //for each word in the searchstring
        foreach ($searchParam['search'] as $searchWord) {
            //check wordlength
            if (strlen($searchWord) > 32) {
                $searchWord = substr($searchWord, 0, 32);
            }

            //TODO check
            //search word-id
            $wordIds = $this->fetchIndexIdForWord($searchWord, ($searchParam['searchWholeWords'] == 'true'));
            if ($wordIds == null) {
                continue;
            }

            //TODO
            //add word-id to set
            //$wordIdSet[] = $wordId;
            foreach ($wordIds as $wordId) {
                $wordIdSet[] = $wordId;
            }
        }

        if ($bTagsOnly) {
            foreach ($searchParam['tags'] as $searchWord) {
                //check wordlength
                if (strlen($searchWord) > 32) {
                    $searchWord = substr($searchWord, 0, 32);
                }

                //TODO check
                //search word-id
                $wordIds = $this->fetchIndexIdForWord($searchWord, ($searchParam['searchWholeWords'] == 'true'));
                if ($wordIds == null) {
                    continue;
                }

                //TODO
                //add word-id to set
                //$wordIdSet[] = $wordId;
                foreach ($wordIds as $wordId) {
                    $wordIdSet[] = $wordId;
                }
            }
        }
        if (!empty($wordIdSet)) {
            $wordIdSet = self::deserializeIds($wordIdSet);
            if (empty($wordIdSet) || (count($wordIdSet) === 1 && $wordIdSet[0] === 0)) {
                return [];
            }
            $wordIdSetMarkers = implode(',', array_fill(0, count($wordIdSet), '?'));

            $hits = [];
            if ($inPosts) { //-------------------------------------------------------------[if]---
                //get indexResults
                $select = 'SELECT si_dest_id AS id, si_type AS type, si_count AS count ' .
                        'FROM tl_c4g_forum_search_index ' .
                        'WHERE si_sw_id IN(' . $wordIdSetMarkers . ') ' .
                        'AND si_type IN(' . $typeSetMarkers . ') ' .
                        'ORDER BY type ';
                $indexResultsRaw = $this->Database->prepare($select)->execute(...array_merge($wordIdSet, $typeSetParams));
                $indexResults = $indexResultsRaw->fetchAllAssoc();

                //get thread-ids for posts
                $postSet = [];
                $countSave = [];
                foreach ($indexResults as $key => $indexResult) {
                    if ($indexResult['type'] == 'post') {
                        $postSet[] = $indexResult['id'];
                        $countSave[$indexResult['id']] = $indexResult['count'];
                        unset($indexResults[$key]);
                    } else {
                        if ($bFilterByTags) {
                            if ($indexResult['type'] == 'tag') {
                                $postSet[] = $indexResult['id'];
                                $countSave[$indexResult['id']] = $indexResult['count'];
                                unset($indexResults[$key]);
                            }
                        }
                    }
                }
                if (!empty($postSet)) {
                    $postSetArray = self::deserializeIds($postSet);
                    $postSetMarkers = implode(',', array_fill(0, count($postSetArray), '?'));

                    $sqlAuthor = '';
                    $authorParam = [];
                    if ($authorId) {
                        $sqlAuthor = 'AND author = ? ';
                        $authorParam = [(int)$authorId];
                    }
                    $postResultsRaw = $this->Database->prepare(
                            'SELECT pid, id FROM tl_c4g_forum_post ' .
                            'WHERE id IN(' . $postSetMarkers . ') ' . $sqlAuthor
                            )->execute(...array_merge($postSetArray, $authorParam));
                    $postResults = $postResultsRaw->fetchAllAssoc();

                    foreach ($postResults as &$postResult) {
                        $postResult['count'] = $countSave[$postResult['id']];
                        $postResult['id'] = $postResult['pid'];
                        unset($postResult['pid']);
                    }

                    $indexResults = array_merge($indexResults, $postResults);
                    unset($postResults);
                }

                unset($postSet);
            } else { //-------------------------------------------------------------------[else]---
                //get indexResults
                $select = 'SELECT si_dest_id AS id, si_count AS count ' .
                        'FROM tl_c4g_forum_search_index ' .
                        'WHERE si_sw_id IN(' . $wordIdSetMarkers . ') ' .
                        'AND si_type IN(' . $typeSetMarkers . ') ';
                $indexResultsRaw = $this->Database->prepare($select)->execute(...array_merge($wordIdSet, $typeSetParams));
                $indexResults = $indexResultsRaw->fetchAllAssoc();
            } //-------------------------------------------------------------------------[end]---

            foreach ($indexResults as $indexResult) {
                if (isset($hits[$indexResult['id']])) {
                    $hits[$indexResult['id']] += $indexResult['count'];
                } else {
                    $hits[$indexResult['id']] = $indexResult['count'];
                }
            }
        } else {
            return [];
        }

        if (!empty($hits)) {
            //create idSet
            $idSet = array_keys($hits);
        } else {
            return [];
        }

        //create SQL for date
        $sqlDate = '';
        $dateParam = [];
        if ($searchParam['timePeriod'] > 0) {
            if ($searchParam['dateRelation'] == 'dateOfBirth') {
                $dateRelation = 'a';
            } else {
                $dateRelation = 'c';
            }
            $timeDirection = $searchParam['timeDirection'] === '<' ? '<' : '>';
            $timePeriod = (int)strtotime('-' . $searchParam['timePeriod'] . ' ' . $searchParam['timeUnit'], time());

            $sqlDate = 'AND ' . $dateRelation . '.creation ' . $timeDirection . " ? ";
            $dateParam = [$timePeriod];
        }

        // when author is provided extract ids for all found threads containing the given author
        if ($authorId) {
            $idSetArray = self::deserializeIds($idSet);
            $idSetMarkers = implode(',', array_fill(0, count($idSetArray), '?'));
            $threadIds = $this->Database->prepare(
                    'SELECT DISTINCT a.pid ' .
                    'FROM tl_c4g_forum_post a ' .
                    'WHERE a.pid IN(' . $idSetMarkers . ') AND a.author = ?')->execute(...array_merge($idSetArray, [(int)$authorId]))->fetchAllAssoc();
            $idSet = [];
            foreach ($threadIds as $id) {
                $idSet[] = $id['pid'];
            }
            if (empty($idSet)) {
                return [];
            }
        }

        switch ($this->show_realname) {
            case 'FF':
                $sqlAuthor = 'b.firstname AS username';
                $sqlLastUser = 'd.firstname';

                break;
            case 'LL':
                $sqlAuthor = 'b.lastname AS username';
                $sqlLastUser = 'd.lastname';

                break;
            case 'FL':
                $sqlAuthor = 'CONCAT(b.firstname, " ", b.lastname) AS username';
                $sqlLastUser = 'CONCAT(d.firstname, " ", d.lastname)';

                break;
            case 'LF':
                $sqlAuthor = 'CONCAT(b.lastname, " ", b.firstname) AS username';
                $sqlLastUser = 'CONCAT(d.lastname, " ", d.firstname)';

                break;
            case 'UU':
            default:
                $sqlAuthor = 'b.username';
                $sqlLastUser = 'd.username';

                break;
        }

        $locationsArray = self::deserializeIds($searchParam['searchLocation'] ?? '');
        $locationsMarkers = implode(',', array_fill(0, count($locationsArray), '?'));

        $idSetArray = self::deserializeIds($idSet);
        $idSetMarkers = implode(',', array_fill(0, count($idSetArray), '?'));

        //finally get what we were looking for
        $results = $this->Database->prepare(
                'SELECT a.id,a.name,a.threaddesc,' . $sqlAuthor . ',a.creation,a.sort,a.posts, (SELECT GROUP_CONCAT(tags) FROM tl_c4g_forum_post WHERE pid = a.id) AS tags,' .
                'c.creation AS lastPost, ' . $sqlLastUser . ' AS lastUsername, a.pid AS forumid ' .
                'FROM tl_c4g_forum_thread a ' .
                'LEFT JOIN tl_member b ON b.id = a.author ' .
                'LEFT JOIN tl_c4g_forum_post c ON c.id = a.last_post_id ' .
                'LEFT JOIN tl_member d ON d.id = c.author ' .
                'WHERE a.pid IN(' . $locationsMarkers . ') ' .
                'AND a.id IN(' . $idSetMarkers . ') ' .
                $sqlDate
                )
                    ->limit(500)
                    ->execute(...array_merge($locationsArray, $idSetArray, $dateParam));
        $results = $results->fetchAllAssoc();

        foreach ($results as $key => &$result) {
            if (empty($result['username'])) {
                $result['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETED_USER'] ?? 'Deleted User');
            }

            if ($bFilterByTags) {
                $resultTags = explode(',', $result['tags'] ?? '');
                $resultTags = array_map('trim', $resultTags);
                $aIntersect = array_intersect($resultTags, $searchParam['tags']);
                if (empty($aIntersect)) {
                    unset($results[$key]);

                    continue;
                }
            } else {
                //add counts to the result as we need it for sorting
                $result['hits'] = $hits[$result['id']] ?? 0;
            }
            // check permission of user to see the found thread
            if (!$this->checkPermission($result['forumid'], 'threadlist') ||
                !$this->checkPermission($result['forumid'], 'search')) {
                unset($results[$key]);
            }
        }

        //return the results
        return $results;
    }

    /**
     * Give back a thread with a given threadId (without posts)
     * @param int $threadId
     */
    public function getThreadFromDB($threadId, $withPosts = false)
    {
        switch ($this->show_realname) {
            case 'FF':
                $sqlAuthor = 'b.firstname AS username';
                $sqlLastUser = 'd.firstname';

                break;
            case 'LL':
                $sqlAuthor = 'b.lastname AS username';
                $sqlLastUser = 'd.lastname';

                break;
            case 'FL':
                $sqlAuthor = 'CONCAT(b.firstname, " ", b.lastname) AS username';
                $sqlLastUser = 'CONCAT(d.firstname, " ", d.lastname)';

                break;
            case 'LF':
                $sqlAuthor = 'CONCAT(b.lastname, " ", b.firstname) AS username';
                $sqlLastUser = 'CONCAT(d.lastname, " ", d.firstname)';

                break;
            case 'UU':
            default:
                $sqlAuthor = 'b.username';
                $sqlLastUser = 'd.username';

                break;
        }
        if ($withPosts) {
            $select = 'SELECT a.id,a.name,a.threaddesc,' . $sqlAuthor . ',a.creation,a.sort,a.posts,a.state,' .
                    'c.creation AS lastPost, ' . $sqlLastUser . ' AS lastUsername ' .
                    'FROM tl_c4g_forum_thread a ' .
                    'LEFT JOIN tl_member b ON b.id = a.author ' .
                    'LEFT JOIN tl_c4g_forum_post c ON c.id = a.last_post_id ' .
                    'LEFT JOIN tl_member d ON d.id = c.author ' .
                    'WHERE a.id = ? ';
        } else {
            $select = 'SELECT a.id,a.pid AS forumid,a.name,a.threaddesc,a.sort,a.author,a.state,' . $sqlAuthor . ',a.creation, a.posts ' .
                    'FROM tl_c4g_forum_thread a ' .
                    'LEFT JOIN tl_member b ON b.id = a.author ' .
                    'WHERE a.id = ? ';
        }

        $thread = $this->Database->prepare($select)
                ->execute((int)$threadId);

        $aThread = $thread->fetchAssoc();
        if (empty($aThread['username'])) {
            $aThread['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETED_USER'] ?? 'Deleted User');
        }

        return $aThread;
    }

    /**
     * Give back a thread for a given threadId (comes back without ID since it's already known)
     * @param int $threadId
     */
    public function getThreadToIdFromDB($threadId)
    {
        switch ($this->show_realname) {
            case 'FF':
                $sqlAuthor = 'b.firstname AS username';
                $sqlLastUser = 'd.firstname';

                break;
            case 'LL':
                $sqlAuthor = 'b.lastname AS username';
                $sqlLastUser = 'd.lastname';

                break;
            case 'FL':
                $sqlAuthor = 'CONCAT(b.firstname, " ", b.lastname) AS username';
                $sqlLastUser = 'CONCAT(d.firstname, " ", d.lastname)';

                break;
            case 'LF':
                $sqlAuthor = 'CONCAT(b.lastname, " ", b.firstname) AS username';
                $sqlLastUser = 'CONCAT(d.lastname, " ", d.firstname)';

                break;
            case 'UU':
            default:
                $sqlAuthor = 'b.username';
                $sqlLastUser = 'd.username';

                break;
        }
        $thread = $this->Database->prepare(
                'SELECT a.name,a.threaddesc,' . $sqlAuthor . ',a.creation,a.sort,a.posts,' .
                'c.creation AS lastPost, ' . $sqlLastUser . ' AS lastUsername ' .
                'FROM tl_c4g_forum_thread a ' .
                'LEFT JOIN tl_member b ON b.id = a.author ' .
                'LEFT JOIN tl_c4g_forum_post c ON c.id = a.last_post_id ' .
                'LEFT JOIN tl_member d ON d.id = c.author ' .
                'WHERE a.id = ? '
                )->execute((int)$threadId);

        $aThread = $thread->fetchAssoc();
        if (empty($aThread['username'])) {
            $aThread['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETED_USER'] ?? 'Deleted User');
        }

        return $aThread;
    }

    /**
     * @param $threadId
     * @param $postId
     * @param string $order
     * @return mixed
     */
    protected function getPostsFromDBInternal($threadId, $postId, $order = 'DESC')
    {
        switch ($this->show_realname) {
            case 'FF':
                $sqlAuthor = 'b.firstname AS username';
                $sqlEditUser = 'e.firstname';

                break;
            case 'LL':
                $sqlAuthor = 'b.lastname AS username';
                $sqlEditUser = 'e.lastname';

                break;
            case 'FL':
                $sqlAuthor = 'CONCAT(b.firstname, " ", b.lastname) AS username';
                $sqlEditUser = 'CONCAT(e.firstname, " ", e.lastname)';

                break;
            case 'LF':
                $sqlAuthor = 'CONCAT(b.lastname, " ", b.firstname) AS username';
                $sqlEditUser = 'CONCAT(e.lastname, " ", e.firstname)';

                break;
            case 'UU':
            default:
                $sqlAuthor = 'b.username';
                $sqlEditUser = 'e.username';

                break;
        }
        $select = 'SELECT a.id,a.pid AS threadid,' . $sqlAuthor . ',a.author AS authorid,a.creation,a.subject,a.text,c.name AS threadname, c.author AS threadauthor, d.name AS forumname,d.id AS forumid, a.rating, ' .
                         'a.post_number, c.posts, a.edit_count, ' . $sqlEditUser . ' AS edit_username, a.edit_last_time, a.linkname, a.linkurl, d.link_newwindow,' .
                         'a.loc_geox, a.loc_geoy, a.loc_data_type, a.loc_data_content, a.locstyle, a.loc_label, a.loc_tooltip, a.loc_osm_id, a.tags, ' .
                         'd.map_label, d.map_tooltip, d.map_popup, d.map_link ' .
                'FROM tl_c4g_forum_post a ' .
                'LEFT JOIN tl_member b ON b.id = a.author ' .
                'INNER JOIN tl_c4g_forum_thread c ON c.id = a.pid ' .
                'INNER JOIN tl_c4g_forum d ON d.id = c.pid ' .
                'LEFT JOIN tl_member e ON e.id = a.edit_last_author';

        if ($threadId <> 0) {
            $posts = $this->Database->prepare(
                $select . ' WHERE a.pid = ? ORDER BY a.id ' . $order);
            $posts = $this->dbExecute($posts, [(int)$threadId]);
        } else {
            $posts = $this->Database->prepare(
                $select . ' WHERE a.id = ? ');
            $posts = $this->dbExecute($posts, [(int)$postId]);
        }
        $aPosts = $posts->fetchAllAssoc();

        foreach ($aPosts as $key => $aPost) {
            if (empty($aPosts[$key]['username'])) {
                $aPosts[$key]['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETED_USER'] ?? 'Deleted User');
            }
        }

        return $aPosts;
    }

    /**
     * @param int $postId
     */
    public function getPostFromDB($postId)
    {
        return $this->getPostsFromDBInternal(0, $postId);
    }

    /**
     * @param int $threadId
     */
    public function getPostsOfThreadFromDB($threadId, $desc = true)
    {
        if ($desc) {
            return $this->getPostsFromDBInternal($threadId, 0);
        }

        return $this->getPostsFromDBInternal($threadId, 0, 'ASC');
    }

    /**
     * @param int $threadId
     * @param int $charcount
     */
    public function getFirstPostLimitedTextOfThreadFromDB($threadId, $charcount = 150, $bUseTitle = false)
    {
        $sColumn = 'text';
        if ($bUseTitle === true) {
            $sColumn = 'subject';
        }

        $result = $this->Database->prepare(
                'SELECT LEFT(' . $sColumn . ', ? ) AS intro FROM tl_c4g_forum_post ' .
                'WHERE pid = ? ' .
                'AND post_number = 1');
        $result = $this->dbExecute($result, [$charcount, (int)$threadId])->intro;

        if ($result) {
            return $result;
        }

        return false;
    }
    /**
     * @param int $threadId
     * @param int $charcount
     */
    public function getLastPostLimitedTextOfThreadFromDB($threadId, $charcount = 150, $bUseTitle = false)
    {
        $sColumn = 'text';
        if ($bUseTitle === true) {
            $sColumn = 'subject';
        }

        return $this->Database->prepare(
                'SELECT LEFT(' . $sColumn . ', ? ) as intro FROM tl_c4g_forum_post ' .
                'WHERE pid = ? ' .
                'AND post_number = (SELECT MAX(post_number) FROM tl_c4g_forum_post WHERE pid = ?)')
                ->execute($charcount, (int)$threadId, (int)$threadId)->intro;
    }

    /**
     * @param int $threadId
     */
    public function getDefaultLocstyleFromDB($threadId)
    {
        return $this->Database->prepare(
                'SELECT locstyle FROM tl_c4g_forum_post ' .
                'WHERE pid = ?' .
                'AND locstyle <> 0')
                ->limit(1)
                ->execute((int)$threadId)->locstyle;
    }

    /**
     * @param int $threadId
     */
    public function getIdOfLastPostFromDB($threadId)
    {
        return $this->Database->prepare(
                'SELECT max(id) as postid FROM tl_c4g_forum_post ' .
                'WHERE pid=?')
                ->execute((int)$threadId)->postid;
    }

    /**
     * @param int $threadId
     * @param int $postNumber
     */
    public function getIdOfPostNumberFromDB($threadId, $postNumber)
    {
        return $this->Database->prepare(
                'SELECT max(id) as postid FROM tl_c4g_forum_post ' .
                'WHERE pid=? AND post_number=?')
                ->execute((int)$threadId, (int)$postNumber)->postid;
    }

    /**
     * @param int $threadId
     * @deprecated
     */
    public function getThreadAndForumNameFromDBUncached($threadId)
    {
        return $this->getThreadAndForumNameFromDB($threadId);
    }
    /**
     * @param int $threadId
     * @deprecated
     */
    public function getThreadAndForumNameAndMailTextFromDBUncached($threadId)
    {
        return $this->getThreadAndForumNameFromDB($threadId);
    }

    /**
     * @param int $threadId
     */
    public function getThreadAndForumNameFromDB($threadId, $language = '')
    {
        return $this->Database->prepare(
                'SELECT a.name AS threadname, b.name AS forumname, a.recipient, a.owner, ' .
                'b.optional_names AS optional_forumnames, a.pid AS forumid, c.value as threadname_translated ' .
                'FROM tl_c4g_forum_thread a ' .
                'INNER JOIN tl_c4g_forum b ON b.id = a.pid ' .
                'LEFT JOIN tl_c4g_forum_thread_translations c ON a.id = c.pid ' .
                'WHERE a.id = ? AND (c.id IS NULL OR (c.fieldname = ?))')
                ->execute((int)$threadId, 'name')->fetchAssoc();
    }

    /**
     * @param int $threadId
     */
    public function getForumIdForThread($threadId)
    {
        $res = $this->Database->prepare(
                'SELECT pid FROM tl_c4g_forum_thread  ' .
                'WHERE id=?')
                ->execute((int)$threadId)->fetchAssoc();
        return $res['pid'] ?? 0;
    }

    /**
     * @param int $threadId
     */
    public function getForumNameForThread($threadId, $language = '')
    {
        $result = $this->Database->prepare(
                'SELECT name, optional_names FROM tl_c4g_forum ' .
                'WHERE id = (SELECT pid FROM tl_c4g_forum_thread WHERE id = ?)')
                ->execute((int)$threadId)->fetchAssoc();

        if ($result && $language) {
            $names = \Contao\StringUtil::deserialize($result['optional_names'] ?? '', true);
            if ($names) {
                foreach ($names as $name) {
                    if ($name['optional_language'] == $language) {
                        return $name['optional_name'];
                    }
                }
            }
        }

        return $result['name'] ?? '';
    }

    /**
     * @param int $postId
     */
    public function getForumIdForPost($postId)
    {
        $res = $this->Database->prepare(
                'SELECT forum_id FROM tl_c4g_forum_post  ' .
                'WHERE id=?')
                ->execute((int)$postId)->fetchAssoc();
        return $res['forum_id'] ?? 0;
    }

    /**
     * @param int $forumId
     */
    public function getForumNameFromDB($forumId, $language = '')
    {
        $result = $this->Database->prepare('SELECT name, optional_names FROM tl_c4g_forum WHERE id=?')->execute((int)$forumId)->fetchAssoc();

        if ($result && $language) {
            $names = \Contao\StringUtil::deserialize($result['optional_names'] ?? '', true);
            if ($names) {
                foreach ($names as $name) {
                    if ($name['optional_language'] == $language) {
                        return $name['optional_name'];
                    }
                }
            }
        }

        return $result['name'] ?? '';
    }
    public function getTicketTitle($ticketId, $forumtype, $time = null)
    {
        $thread = $this->Database->prepare('SELECT * FROM tl_c4g_forum_thread WHERE id=?')->execute((int)$ticketId)->fetchAssoc();
        $title = '[' . C4GForumHelper::getTypeText($forumtype, 'THREAD') . ' #';
        $title .= sprintf('%04d', $thread['id']) . '] ' . $thread['name'];
        if ($time) {
            $title .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], intval($thread['tstamp']));
        }
        if ($thread['state'] && $forumtype === 'TICKET') {
            $state = C4GForumTicketStatus::getState($thread['state']);
            $title .= ': (<b>' . $state . '</b>)';
        }

        return $title;
    }

    /**
     * Get path to forum as associative array
     * @param int $forumId
     */
    public function getForumPath($forumId, $rootForumId)
    {
        $forumId = intval($forumId);
        $forums = $this->Database->prepare(
            'SELECT a.id,a.pid,a.name,a.optional_names,a.use_intropage,count(b.id) AS subforums FROM tl_c4g_forum a ' .
            'LEFT JOIN tl_c4g_forum b ON (b.pid = a.id) AND (b.published = ?) ' .
            'GROUP BY a.id')->execute(1);
        while (($row = $forums->fetchAssoc()) !== false) {
            $data[$row['id']] = $row;
        }

        $id = $forumId ?: 0;
        $result = [];

        $finished = false;
        $counter = 0;
        while (!$finished && $counter < 50) {
            $counter ++;
            if ($id == 0) {
                $insertArray = [
                    [
                        'id' => $rootForumId,
                        'name' => $this->ForumName,
                        'use_intropage' => false,
                        'subforums' => true,
                    ],
                ];
                if (empty($result) || !isset($result[0]) || $insertArray !== $result[0]) {
                    array_splice($result, 0, 0, $insertArray);
                }
            } else {
                if (!isset($data[$id])) {
                    break;
                }

                $insertArray = [[
                    'id' => $id,
                    'name' => $data[$id]['name'] ?? '',
                    'optional_names' => $data[$id]['optional_names'] ?? '',
                    'use_intropage' => $data[$id]['use_intropage'] ?? false,
                    'subforums' => $data[$id]['subforums'] ?? false, ]];
                if (empty($result) || !isset($result[0]) || $insertArray !== $result[0]) {
                    array_splice($result, 0, 0, $insertArray);
                }
            }
            if ($id == $rootForumId) {
                $finished = true;
            } else {
                $id = $data[$id]['pid'] ?? 0;
            }
        }
        if ($counter > 40) {
            return [$result[count($result) - 1]];
        }

        return $result;
    }

    /**
     * @param $threadId
     * @param $userId
     * @param $subject
     * @param $post
     * @param $tags
     * @param $forumId
     * @param $post_number
     * @param $linkname
     * @param $linkurl
     * @param $loc_geox
     * @param $loc_geoy
     * @param $locstyle
     * @param $loc_label
     * @param $loc_tooltip
     * @param $loc_data_content
     * @param $loc_osm_id
     *
     * @return bool
     */
    public function insertPostIntoDBInternal($threadId, $userId, $subject, $post, $tags, $rating = 0, $forumId, $post_number, $linkname, $linkurl, $loc_geox, $loc_geoy, $locstyle, $loc_label, $loc_tooltip, $loc_data_content, $loc_osm_id, $recipient, $owner)
    {
        $set = [];
        $set2 = [];
        $set['pid'] = $threadId;
        $set['author'] = $userId;
        $set['creation'] = time();
        $set['text'] = C4GUtils::secure_ugc($post);
        $set['subject'] = C4GUtils::secure_ugc($subject);
        $set['forum_id'] = $forumId;
        $set['post_number'] = $post_number;
        $set['tstamp'] = time();
        $set['state'] = 1;
        $set2['state'] = 1;
        $set2['tstamp'] = time();
        $set2['recipient'] = $recipient;
        $set2['owner'] = $owner;
        if ($linkname != null) {
            $set['linkname'] = C4GUtils::secure_ugc($linkname);
        }
        if ($linkurl != null) {
            $set['linkurl'] = C4GUtils::secure_ugc($linkurl);
        }
        if ($loc_geox != null) {
            $set['loc_geox'] = C4GUtils::secure_ugc($loc_geox);
        }
        if ($loc_geoy != null) {
            $set['loc_geoy'] = C4GUtils::secure_ugc($loc_geoy);
        }
        if ($locstyle != null) {
            $set['locstyle'] = $locstyle;
        }
        if ($loc_label != null) {
            $set['loc_label'] = C4GUtils::secure_ugc($loc_label);
        }
        if ($loc_tooltip != null) {
            $set['loc_tooltip'] = C4GUtils::secure_ugc($loc_tooltip);
        }
        // if ($loc_osm_id!=NULL) {
        $set['loc_osm_id'] = C4GUtils::secure_ugc($loc_osm_id);
        // }
        if ($loc_data_content != null && $loc_data_content != '') {
            $set['loc_data_type'] = 'geojson';
            $set['loc_data_content'] = C4GUtils::secure_ugc($loc_data_content);
        }

        if (!empty($tags)) {
            $set['tags'] = implode(', ', $tags);
        }

        if (empty($rating)) {
            $rating = 0;
        }
        $set['rating'] = $rating;

        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }

        $objInsertStmt = $this->Database->prepare("INSERT INTO tl_c4g_forum_post SET " . implode(', ', $sqlSet))
                                        ->execute(...$params);
        $result['post_id'] = $objInsertStmt->insertId;

        $sqlSet2 = [];
        $params2 = [];
        foreach ($set2 as $k => $v) {
            $sqlSet2[] = "`$k`=?";
            $params2[] = $v;
        }
        $params2[] = $threadId;
        $varSQL = $this->Database->prepare("UPDATE tl_c4g_forum_thread SET " . implode(', ', $sqlSet2) . " WHERE id=?")
            ->execute(...$params2);

        if (!$objInsertStmt->affectedRows) {
            return false;
        }
        //update thread and forum

        //update index
        $this->createIndex('post', $result['post_id']);
        $this->createIndex('tag', $result['post_id']);

        return $result;
    }

    /**
     * @param array $post
     * @param int $userId
     * @param string $subject
     * @param string $postText
     * @param string $linkname
     * @param string $linkurl
     * @param string $loc_geox
     * @param string $loc_geoy
     * @param int $locstyle
     * @param string $label
     * @param string $tooltip
     */
    public function updatePostDB($post, $userId, $subject, $tags, $rating = 0, $postText, $linkname, $linkurl, $loc_geox, $loc_geoy, $locstyle, $loc_label, $loc_tooltip, $loc_data_content, $loc_osm_id)
    {
        $set = [];
        $set['text'] = C4GUtils::secure_ugc($postText);
        $set['subject'] = C4GUtils::secure_ugc($subject);
        $set['edit_count'] = $post['edit_count'] + 1;
        $set['edit_last_author'] = $userId;
        $set['edit_last_time'] = time();
        if ($linkname !== null) {
            $set['linkname'] = C4GUtils::secure_ugc($linkname);
        }
        if ($linkurl !== null) {
            $set['linkurl'] = C4GUtils::secure_ugc($linkurl);
        }
        if ($loc_geox != null) {
            $set['loc_geox'] = C4GUtils::secure_ugc($loc_geox);
        } else {
            $set['loc_geox'] = '';
        }
        if ($loc_geoy != null) {
            $set['loc_geoy'] = C4GUtils::secure_ugc($loc_geoy);
        } else {
            $set['loc_geoy'] = '';
        }
        if ($locstyle != null) {
            $set['locstyle'] = $locstyle;
        }
        if ($loc_label != null) {
            $set['loc_label'] = C4GUtils::secure_ugc($loc_label);
        }
        if ($loc_tooltip != null) {
            $set['loc_tooltip'] = C4GUtils::secure_ugc($loc_tooltip);
        }
        // if ($loc_osm_id!=NULL) {
        $set['loc_osm_id'] = C4GUtils::secure_ugc($loc_osm_id);
        // }
        if ($loc_data_content != null && $loc_data_content != '') {
            $set['loc_data_type'] = 'geojson';
            $set['loc_data_content'] = C4GUtils::secure_ugc($loc_data_content);
        } else {
            if ($loc_data_content !== null) {
                $set['loc_data_type'] = '';
                $set['loc_data_content'] = '';
            }
        }

        if (!empty($tags)) {
            $set['tags'] = implode(', ', $tags);
        }

        if (empty($rating)) {
            $rating = 0;
        }
        $set['rating'] = $rating;

        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }
        $params[] = $post['id'];

        $objUpdateStmt = $this->Database->prepare("UPDATE tl_c4g_forum_post SET " . implode(', ', $sqlSet) . " WHERE id=?")
                                        ->execute(...$params);

        if (!$objUpdateStmt->affectedRows) {
            return false;
        }

        //update index
        $this->createIndex('post', $post['id']);
        $this->createIndex('tag', $post['id']);

        return true;
    }

    /**
     * @param array $thread
     * @param int $userId
     * @param string $name
     * @param string $threaddesc
     * @param int $sort
     */
    public function updateThreadDB($thread, $userId, $name, $threaddesc, $sort)
    {
        $set = [];
        $set['name'] = C4GUtils::secure_ugc($name);
        $set['threaddesc'] = nl2br(C4GUtils::secure_ugc($threaddesc));
        $set['edit_count'] = $thread['edit_count'] + 1;
        $set['edit_last_author'] = $userId;
        $set['edit_last_time'] = time();
        $set['sort'] = $sort;
        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }
        $params[] = $thread['id'];

        $objUpdateStmt = $this->Database->prepare("UPDATE tl_c4g_forum_thread SET " . implode(', ', $sqlSet) . " WHERE id=?")
                                        ->execute(...$params);

        if (!$objUpdateStmt->affectedRows) {
            return false;
        }

        //update index
        $this->createIndex('threadhl', $thread['id']);
        $this->createIndex('threaddesc', $thread['id']);
        $this->createIndex('threadtag', $thread['id']);

        return true;
    }

    /**
     * @param int $threadId
     * @param int $posts
     * @param int $last_post_id
     */
    public function updateThreadHelperData($threadId, $posts, $last_post_id)
    {
        $set = [];
        $set['posts'] = $posts;
        $set['last_post_id'] = $last_post_id;
        if (!empty($set)) {
            $sqlSet = [];
            $params = [];
            foreach ($set as $k => $v) {
                $sqlSet[] = "`$k`=?";
                $params[] = $v;
            }
            $params[] = $threadId;
            $this->Database->prepare("UPDATE tl_c4g_forum_thread SET " . implode(', ', $sqlSet) . " WHERE id=?")
                ->execute(...$params);
            if (!$this->Database->affectedRows) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $forumId
     * @param int $threads
     * @param int $last_thread_id
     * @param int $posts
     */
    public function updateForumHelperData($forumId, $threads, $last_thread_id, $posts, $last_post_id)
    {
        $set = [];
        if ($threads) {
            $set['threads'] = $threads;
        }
        if ($last_thread_id) {
            $set['last_thread_id'] = (int) $last_thread_id;
        }
        if ($posts) {
            $set['posts'] = $posts;
        }
        if ($last_post_id) {
            $set['last_post_id'] = (int) $last_post_id;
        }

        if (count($set)) {
            $sqlSet = [];
            $params = [];
            foreach ($set as $k => $v) {
                $sqlSet[] = "`$k`=?";
                $params[] = $v;
            }
            $params[] = $forumId;
            $this->Database->prepare("UPDATE tl_c4g_forum SET " . implode(', ', $sqlSet) . " WHERE id=?")
                ->execute(...$params);
            if (!$this->Database->affectedRows) {
                return false;
            }
        }

        return true;
    }

    /**
     * Delete post by id
     * @param int $postId
     */
    protected function deletePostInternal($postId)
    {
        $objDeleteStmt = $this->Database->prepare('DELETE FROM tl_c4g_forum_post WHERE id=?')
                                        ->execute((int)$postId);
        if ($objDeleteStmt->affectedRows == 0) {
            return false;
        }
        //update index
        $this->deleteIndexFromDB('post', $postId);

        return true;
    }

    /**
     * Delete thread
     * @param int $threadId
     */
    protected function deleteThreadInternal($threadId)
    {
        //delete index
        $this->deleteThreadIndexFromDB((int)$threadId);

        $objDeleteStmt = $this->Database->prepare('DELETE FROM tl_c4g_forum_post WHERE pid=?')
                                        ->execute((int)$threadId);
        $objDeleteStmt = $this->Database->prepare('DELETE FROM tl_c4g_forum_thread WHERE id=?')
                                        ->execute((int)$threadId);
        if ($objDeleteStmt->affectedRows == 0) {
            return false;
        }

        return true;
    }

    /**
     * @param int $postId
     */
    protected function rollbackInsertPost($postId)
    {
        // rollback transaction is not supported by all MySQL Databases, so "rollback" manually
        $this->deletePostInternal($postId);
        $this->recalculateThreadHelperData();
        $this->recalculateForumHelperData();
    }

    /**
     * @param int $threadId
     * @param int $userId
     * @param string $subject
     * @param string $post
     * @param string $linkname
     * @param string $linkurl
     * @param string $loc_geox
     * @param string $loc_geoy
     * @param int $locstyle
     * @param string $loc_label
     * @param string $loc_tooltip
     * @throws Exception
     */
    public function insertPostIntoDB($threadId, $userId, $subject, $post, $tags, $rating = 0, $linkname, $linkurl, $loc_geox, $loc_geoy, $locstyle, $loc_label, $loc_tooltip, $loc_data_content, $loc_osm_id, $recipient, $owner)
    {
        $this->Database->beginTransaction();

        try {
            $thread = $this->Database->prepare(
                'SELECT a.pid AS forum_id, a.posts AS threadposts, b.posts AS forumposts, b.threads AS forumthreads ' .
                'FROM tl_c4g_forum_thread a, tl_c4g_forum b WHERE ' .
                'a.id=? AND b.id = a.pid')->execute((int)$threadId);
            $result = $this->insertPostIntoDBInternal($threadId, $userId, $subject, $post, $tags, $rating, $thread->forum_id, $thread->threadposts + 1,
                                                      $linkname, $linkurl, $loc_geox, $loc_geoy, $locstyle, $loc_label, $loc_tooltip, $loc_data_content, $loc_osm_id, $recipient, $owner);
            if (!$result) {
                $this->Database->rollbackTransaction();

                return false;
            }
            if (!$this->updateThreadHelperData($threadId, $thread->threadposts + 1, $result['post_id'])) {
                $this->Database->rollbackTransaction();
                $this->rollbackInsertPost($result['post_id']);

                return false;
            }
            if (!$this->updateForumHelperData($thread->forum_id, false, false, $thread->forumposts + 1, $result['post_id'])) {
                $this->Database->rollbackTransaction();
                $this->rollbackInsertPost($result['post_id']);

                return false;
            }

            $result['forum_id'] = $thread->forum_id;
        } catch (Exception $e) {
            $this->Database->rollbackTransaction();
            if ($result) {
                $this->rollbackInsertPost($result['post_id']);
            }

            throw $e;
        }
        $this->Database->commitTransaction();

        return $result;
    }

    /**
     * @param int $postId
     */
    protected function rollbackInsertThread($threadId)
    {
        // rollback transaction is not supported by all MySQL Databases, so "rollback" manually
        $this->deleteThreadInternal($threadId);
        $this->recalculateThreadHelperData();
        $this->recalculateForumHelperData();
    }

    /**
     * @param $forumId
     * @param $threadname
     * @param $userId
     * @param $threaddesc
     * @param $sort
     * @param $post
     * @param $tags
     * @param $linkname
     * @param $linkurl
     * @param $geox
     * @param $geoy
     * @param $locstyle
     * @param $label
     * @param $tooltip
     * @param $geodata
     * @param $loc_osm_id
     *
     * @return bool
     * @throws \Exception
     */
    public function insertThreadIntoDB($forumId, $threadname, $userId, $threaddesc, $sort, $post, $tags, $linkname, $linkurl, $geox, $geoy, $locstyle, $label, $tooltip, $geodata, $loc_osm_id, $recipient, $owner = 0, $ticketId = null, $rating = 0)
    {
        $this->Database->beginTransaction();

        try {
            $set = [];
            $forum = $this->Database->prepare(
                'SELECT threads, posts FROM tl_c4g_forum WHERE id=?')->execute((int)$forumId);

            if ($ticketId) {
                $set['concerning'] = $ticketId;
            }
            $set['pid'] = $forumId;
            $set['author'] = $userId;
            $set['creation'] = time();
            $set['sort'] = $sort;
            $set['name'] = C4GUtils::secure_ugc($threadname);
            $set['threaddesc'] = C4GUtils::secure_ugc($threaddesc);
            $set['recipient'] = $recipient;
            $set['owner'] = $owner;
            $set['tstamp'] = time();
            $set['state'] = 1;
            if (!empty($tags)) {
                $set['tags'] = implode(', ', $tags);
            }

            $sqlSet = [];
            $params = [];
            foreach ($set as $k => $v) {
                $sqlSet[] = "`$k`=?";
                $params[] = $v;
            }

            $objInsertStmt = $this->Database->prepare("INSERT INTO tl_c4g_forum_thread SET " . implode(', ', $sqlSet))
                                            ->execute(...$params);

            if (!$objInsertStmt->affectedRows) {
                $this->Database->rollbackTransaction();

                return false;
            }
            $new_thread_id = $objInsertStmt->insertId;

            $savePost = ($post || $linkname || $linkurl);

            if ($savePost) {
                $result = $this->insertPostIntoDBInternal($objInsertStmt->insertId, $userId, $threadname, $post, $tags, $rating, $forumId, 1, $linkname, $linkurl, $geox, $geoy, $locstyle, $label, $tooltip, $geodata, $loc_osm_id, $recipient, $owner);
                if (!$result) {
                    $this->Database->rollbackTransaction();
                    $this->rollbackInsertThread($new_thread_id);

                    return false;
                }

                if (!$this->updateThreadHelperData($new_thread_id, 1, $result['post_id'])) {
                    $this->Database->rollbackTransaction();
                    $this->rollbackInsertThread($new_thread_id);

                    return false;
                }
                if (!$this->updateForumHelperData($forumId, $forum->threads + 1, $new_thread_id, $forum->posts + 1, $result['post_id'])) {
                    $this->Database->rollbackTransaction();
                    $this->rollbackInsertThread($new_thread_id);

                    return false;
                }
            } else {
                if (!$this->updateForumHelperData($forumId, $forum->threads + 1, $new_thread_id, false, false)) {
                    $this->Database->rollbackTransaction();
                    $this->rollbackInsertThread($new_thread_id);

                    return false;
                }
            }

            $result['thread_id'] = $new_thread_id;
        } catch (Exception $e) {
            $this->Database->rollbackTransaction();
            $this->rollbackInsertThread($new_thread_id);

            throw $e;
        }
        $this->Database->commitTransaction();
        //update index
        $this->createIndex('threadhl', $new_thread_id);
        $this->createIndex('threaddesc', $new_thread_id);
        $this->createIndex('threadtag', $new_thread_id);

        return $result;
    }

    /**
     * @param int $threadId
     */
    public function deleteThreadFromDB($threadId)
    {
        $this->subscription->deleteSubscriptionForThread($threadId);
        $this->deleteTranslationsForThread($threadId);
        $return = $this->deleteThreadInternal($threadId);
        $this->recalculateForumHelperData();

        return $return;
    }

    /**
     * @param int $postId
     */
    public function deletePostFromDB($postId)
    {
        $return = $this->deletePostInternal($postId);
        $this->recalculatePostHelperData();
        $this->recalculateThreadHelperData();
        $this->recalculateForumHelperData();

        return $return;
    }

    /**
     * @param int $threadId
     * @param int $newForumId
     */
    public function moveThreadDB($threadId, $newForumId)
    {
        $set['pid'] = $newForumId;
        $this->Database->prepare("UPDATE tl_c4g_forum_thread SET pid=? WHERE id=?")
            ->execute((int)$newForumId, (int)$threadId);
        if (!$this->Database->affectedRows) {
            $return = false;
        } else {
            $return = true;
            $this->recalculatePostHelperData(); // update field forum_id
            $this->recalculateForumHelperData();
        }

        return $return;
    }
    /**
     * make sure that no word is longer than 50 characters to avoid design problems
     * @param string $threadname
     * @return string
     */
    public function checkThreadname($threadname, $opt_length = 30)
    {
        if ($opt_length) {
            return wordwrap($threadname, $opt_length, ' ', true);
        }
    }

    /**
     * @param $forumId
     * @param $frontendUser
     * @return mixed
     */
    public function getMemberGroupsForForum($forumId, $frontendUser)
    {
        $forum = $this->Database->prepare(
            'SELECT member_groups, admin_groups FROM tl_c4g_forum WHERE id=?')
                         ->execute((int)$forumId)->fetchAssoc();

        $forumMemGroups = self::deserializeIds($forum['member_groups'] ?? '');
        $forumAdGroups = self::deserializeIds($forum['admin_groups'] ?? '');

        $memGroups = [];
        if (!empty($forumMemGroups)) {
            $memGroupsMarkers = implode(',', array_fill(0, count($forumMemGroups), '?'));
            $memGroups = $this->Database->prepare(
                'SELECT id,name FROM tl_member_group WHERE id IN (' . $memGroupsMarkers . ')')
                             ->execute(...$forumMemGroups)->fetchAllAssoc();
        }

        $adGroups = [];
        if (!empty($forumAdGroups)) {
            $adGroupsMarkers = implode(',', array_fill(0, count($forumAdGroups), '?'));
            $adGroups = $this->Database->prepare(
                'SELECT id,name FROM tl_member_group WHERE id IN (' . $adGroupsMarkers . ')')
                             ->execute(...$forumAdGroups)->fetchAllAssoc();
        }

        $user = $this->Database->prepare(
            'SELECT id,`groups` FROM tl_member WHERE id =?')
            ->execute((int)$frontendUser)->fetchAssoc();
        $userGroups = \Contao\StringUtil::deserialize($user['groups'] ?? '', true);
        $return = [];
        foreach ($userGroups as $key) {
            foreach ($adGroups as $group) {
                if ($group['id'] == $key) {
                    $return[] = $group;
                }
            }
            foreach ($memGroups as $group) {
                if ($group['id'] == $key) {
                    $return[] = $group;
                }
            }
        }

        return $return;
    }

    /**
     * @param int $forumId
     * @return array
     */
    public function getNonMembersOfForum($forumId)
    {
        $forum = $this->Database->prepare(
            'SELECT member_groups,admin_groups FROM tl_c4g_forum WHERE id=?')
                         ->execute((int)$forumId)->fetchAssoc();

        $forumGroups = array_merge(self::deserializeIds($forum['member_groups'] ?? ''), self::deserializeIds($forum['admin_groups'] ?? ''));
        $members = $this->Database->prepare(
            'SELECT id,firstname, lastname, username, groups FROM tl_member')
                         ->execute()->fetchAllAssoc();

        // delete all members that are assigned to at least on group in $memGroups
        // needs to be done, because "groups" is from type "BLOB" -.-'
        foreach ($members as $key => $member) {
            $groups = StringUtil::deserialize($member['groups'] ?? '', true);
            if (count(array_intersect($groups, $forumGroups)) > 0) {
                unset($members[$key]);
            }
        }

        return $members;
    }

    /**
     * @param int $memGroupId
     * @param int $memberId
     * @return boolean
     */
    public function addMemberGroupDB($memGroupId, $memberId)
    {
        $objResult = $this->Database->prepare(
            'SELECT id,`groups` FROM tl_member WHERE id=?')
                         ->execute((int)$memberId);
        $members = $objResult ? $objResult->fetchAssoc() : null;
        if (!$members || $members['id'] == 0) {
            return false;
        }
        $groups = StringUtil::deserialize($members['groups'], true);
        if (!in_array($memGroupId, $groups)) {
            $groups[] = $memGroupId;
            $set['groups'] = serialize($groups);
            $this->Database->prepare("UPDATE tl_member SET `groups`=? WHERE id=?")
                                                ->execute($set['groups'], (int)$memberId);
            if (!$this->Database->affectedRows) {
                return false;
            }
        }

        return true;
    }

    /**
     * Update rights and groups after submit of a forum in the Contao Backend to make
     * sure that the inherited groups and rights are always saved correctly for every forum,
     * so there is no need to determine inherited rights/groups in the frontend modules.
     *
     * @param int $forumId
     * @param int $pid
     */
    public function updateForumRightsAndGroupInheritance($forumId, $pid)
    {
        $objSelect = $this->Database->prepare(
            'SELECT id,pid,define_rights,guest_rights,member_rights,admin_rights,define_groups,member_groups,admin_groups ' .
            'FROM tl_c4g_forum WHERE id=? OR (id=? AND id != 0) OR pid=?')->execute((int)$forumId, (int)$pid, (int)$forumId);

        $row = [];
        if ($objSelect) {
            while (($r = $objSelect->fetchAssoc()) !== false) {
                $row[$r['id']] = $r;
            }
        }

        if (!isset($row[$forumId])) {
            return;
        }

        $set = [];
        if (!$row[$forumId]['define_rights']) {
            if ($pid == 0) {
                // set default rights if forum has no parent
                $guestDefault = serialize($this->getGuestDefaultRights());
                $memberDefault = serialize($this->getMemberDefaultRights());
                $adminDefault = serialize($this->getAdminDefaultRights());

                if ($row[$forumId]['guest_rights'] != $guestDefault) {
                    $set['guest_rights'] = $guestDefault;
                    $row[$forumId]['guest_rights'] = $guestDefault;
                }

                if ($row[$forumId]['member_rights'] != $memberDefault) {
                    $set['member_rights'] = $memberDefault;
                    $row[$forumId]['member_rights'] = $memberDefault;
                }

                if ($row[$forumId]['admin_rights'] != $adminDefault) {
                    $set['admin_rights'] = $adminDefault;
                    $row[$forumId]['admin_rights'] = $adminDefault;
                }
            } else {
                if ($row[$forumId]['guest_rights'] != $row[$pid]['guest_rights']) {
                    $set['guest_rights'] = $row[$pid]['guest_rights'];
                    $row[$forumId]['guest_rights'] = $row[$pid]['guest_rights'];
                }

                if ($row[$forumId]['member_rights'] != $row[$pid]['member_rights']) {
                    $set['member_rights'] = $row[$pid]['member_rights'];
                    $row[$forumId]['member_rights'] = $row[$pid]['member_rights'];
                }

                if ($row[$forumId]['admin_rights'] != $row[$pid]['admin_rights']) {
                    $set['admin_rights'] = $row[$pid]['admin_rights'];
                    $row[$forumId]['admin_rights'] = $row[$pid]['admin_rights'];
                }
            }
        }

        if (!$row[$forumId]['define_groups']) {
            if ($pid == 0) {
                // delete all groups if forum has no parent
                if ($row[$forumId]['member_groups'] != null) {
                    $set['member_groups'] = null;
                    $row[$forumId]['member_groups'] = null;
                }

                if ($row[$forumId]['admin_groups'] != null) {
                    $set['admin_groups'] = null;
                    $row[$forumId]['admin_groups'] = null;
                }
            } else {
                if ($row[$forumId]['member_groups'] != $row[$pid]['member_groups']) {
                    $set['member_groups'] = $row[$pid]['member_groups'];
                    $row[$forumId]['member_groups'] = $row[$pid]['member_groups'];
                }

                if ($row[$forumId]['admin_groups'] != $row[$pid]['admin_groups']) {
                    $set['admin_groups'] = $row[$pid]['admin_groups'];
                    $row[$forumId]['admin_groups'] = $row[$pid]['admin_groups'];
                }
            }
        }

        if (!empty($set)) {
            $sqlSet = [];
            $params = [];
            foreach ($set as $k => $v) {
                $sqlSet[] = "`$k`=?";
                $params[] = $v;
            }
            $params[] = $forumId;
            $stmt = $this->Database->prepare("UPDATE tl_c4g_forum SET " . implode(', ', $sqlSet) . " WHERE id=?");
            $this->dbExecute($stmt, $params);
        }

        foreach ($row as $key => $forum) {
            // loop through all children of forum "forumId"
            if (($key != $forumId) && ($key != $pid)) {
                $change = false;
                if (!$forum['define_groups']) {
                    if ($row[$forumId]['member_groups'] != $forum['member_groups']) {
                        $change = true;
                    }
                    if ($row[$forumId]['admin_groups'] != $forum['admin_groups']) {
                        $change = true;
                    }
                }

                if (!$forum['define_rights']) {
                    if ($row[$forumId]['guest_rights'] != $forum['guest_rights']) {
                        $change = true;
                    }
                    if ($row[$forumId]['member_rights'] != $forum['member_rights']) {
                        $change = true;
                    }
                    if ($row[$forumId]['admin_rights'] != $forum['admin_rights']) {
                        $change = true;
                    }
                }
                if ($change) {
                    // recursively update subforums
                    $this->updateForumRightsAndGroupInheritance($forum['id'], $forum['pid']);
                }
            }
        }
    }

    /**
     * Update map settings after submit of a forum in the Contao Backend to make
     * sure that the inherited map settings are always saved correctly for every forum.
     *
     * @param int $forumId
     * @param int $pid
     */
    public function updateMapEnabledInheritance($forumId, $pid)
    {
        $objSelect = $this->Database->prepare(
                'SELECT id,pid,enable_maps,enable_maps_inherited,map_profile,map_location_label,map_label,map_tooltip,map_popup,map_link ' .
                'FROM tl_c4g_forum WHERE id=? OR (id=? AND id != 0) OR pid=?')->execute((int)$forumId, (int)$pid, (int)$forumId);
        $row = [];
        if ($objSelect) {
            while (($r = $objSelect->fetchAssoc()) !== false) {
                $row[$r['id']] = $r;
            }
        }

        if (!isset($row[$forumId])) {
            return;
        }

        $set = [];
        if (!$row[$forumId]['enable_maps']) {
            if ($row[$pid]['enable_maps'] || $row[$pid]['enable_maps_inherited']) {
                if (!$row[$forumId]['enable_maps_inherited']) {
                    $set['enable_maps_inherited'] = true;
                    $row[$forumId]['enable_maps_inherited'] = true;
                }

                if ($row[$forumId]['map_profile'] != $row[$pid]['map_profile']) {
                    $set['map_profile'] = $row[$pid]['map_profile'];
                    $row[$forumId]['map_profile'] = $row[$pid]['map_profile'];
                }

                if ($row[$forumId]['map_location_label'] != $row[$pid]['map_location_label']) {
                    $set['map_location_label'] = $row[$pid]['map_location_label'];
                    $row[$forumId]['map_location_label'] = $row[$pid]['map_location_label'];
                }

                if ($row[$forumId]['map_label'] != $row[$pid]['map_label']) {
                    $set['map_label'] = $row[$pid]['map_label'];
                    $row[$forumId]['map_label'] = $row[$pid]['map_label'];
                }

                if ($row[$forumId]['map_tooltip'] != $row[$pid]['map_tooltip']) {
                    $set['map_tooltip'] = $row[$pid]['map_tooltip'];
                    $row[$forumId]['map_tooltip'] = $row[$pid]['map_tooltip'];
                }

                if ($row[$forumId]['map_popup'] != $row[$pid]['map_popup']) {
                    $set['map_popup'] = $row[$pid]['map_popup'];
                    $row[$forumId]['map_popup'] = $row[$pid]['map_popup'];
                }

                if ($row[$forumId]['map_link'] != $row[$pid]['map_link']) {
                    $set['map_link'] = $row[$pid]['map_link'];
                    $row[$forumId]['map_link'] = $row[$pid]['map_link'];
                }
            } else {
                if ($row[$forumId]['enable_maps_inherited']) {
                    $set['enable_maps_inherited'] = false;
                    $row[$forumId]['enable_maps_inherited'] = false;
                }
            }

            if (is_array($set) && count($set) > 0) {
                $sqlSet = [];
                $params = [];
                foreach ($set as $k => $v) {
                    $sqlSet[] = "`$k`=?";
                    $params[] = $v;
                }
                $params[] = $forumId;
                $stmt = $this->Database->prepare("UPDATE tl_c4g_forum SET " . implode(', ', $sqlSet) . " WHERE id=?");
                $this->dbExecute($stmt, $params);
            }
        }

        $enableMaps = ($row[$forumId]['enable_maps'] || $row[$forumId]['enable_maps_inherited']);
        foreach ($row as $key => $forum) {
            // loop through all children of forum "forumId"
            if (($key != $forumId) && ($key != $pid)) {
                $change = false;
                if (!$forum['enable_maps']) {
                    if ($forum['enable_maps_inherited'] != $enableMaps) {
                        $change = true;
                    }
                    if ($enableMaps) {
                        if ($row[$forumId]['map_id'] != $forum['map_id']) {
                            $change = true;
                        }
                        if ($row[$forumId]['map_location_label'] != $forum['map_location_label']) {
                            $change = true;
                        }
                        if ($row[$forumId]['map_type'] != $forum['map_type']) {
                            $change = true;
                        }
                        if ($row[$forumId]['map_label'] != $forum['map_label']) {
                            $change = true;
                        }
                        if ($row[$forumId]['map_tooltip'] != $forum['map_tooltip']) {
                            $change = true;
                        }
                        if ($row[$forumId]['map_popup'] != $forum['map_popup']) {
                            $change = true;
                        }
                        if ($row[$forumId]['map_link'] != $forum['map_link']) {
                            $change = true;
                        }
                    }
                }
                if ($change) {
                    // recursively update subforums
                    $this->updateMapEnabledInheritance($forum['id'], $forum['pid']);
                }
            }
        }
    }

    /**
     *
     */
    public function executePermissionHook($return, $type)
    {
        if (isset($GLOBALS['TL_HOOKS']['C4gForumPermissions']) && is_array($GLOBALS['TL_HOOKS']['C4gForumPermissions'])) {
            foreach ($GLOBALS['TL_HOOKS']['C4gForumPermissions'] as $callback) {
                $hookClass = $callback[0];
                $strFunction = $callback[1];
                if ($strFunction) {
                    $this->import($hookClass);
                    $return = $this->$hookClass->$strFunction($return, $this, $type);
                }
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getGuestDefaultRights()
    {
        $return = ['visible','threadlist','readpost','threaddesc','search','latestthreads'];

        return $this->executePermissionHook($return, 'guest');
    }

    /**
     * @return array
     */
    public function getMemberDefaultRights()
    {
        $return = ['visible','threadlist','readpost','newpost','newthread','postlink','threaddesc','editownpost','editownthread','search','latestthreads'];

        return $this->executePermissionHook($return, 'member');
    }

    /**
     * @return array
     */
    public function getAdminDefaultRights()
    {
        $return = ['visible','threadlist','readpost','newpost','newthread','postlink','threaddesc','threadsort','editownpost','editpost', 'editownthread', 'editthread',
            'delownpost','delpost','delthread','movethread','subscribethread','subscribeforum','addmember','search','latestthreads','alllanguages','tickettomember','closethread', ];

        //ToDo check admin
        return $this->executePermissionHook($return, 'admin');
    }

    /**
     * @return array
     */
    public function getGuestRightList()
    {
        $return = $this->getGuestDefaultRights();
        if (C4GVersionProvider::isInstalled('con4gis/maps')) {
            $return[] = 'mapview';
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getRightList()
    {
        $return = $this->getAdminDefaultRights();
        if (C4GVersionProvider::isInstalled('con4gis/maps')) {
            $return[] = 'mapview';
            $return[] = 'mapedit';
            $return[] = 'mapextend';
            $return[] = 'mapedit_style';
        }

        return $return;
    }

    /**
     * @param int $forumId
     * @return array
     */
    public function getPostsFromDBForMap($forumId)
    {
        $posts = $this->Database->prepare(
                'SELECT a.id, a.pid AS threadid, c.name AS threadname, a.loc_geox, a.loc_geoy, a.loc_data_type, a.loc_data_content, a.locstyle, a.subject, a.linkname, a.linkurl, a.loc_label, a.loc_tooltip, a.loc_osm_id, a.text, b.map_label, b.map_tooltip, b.map_popup, b.map_link ' .
                'FROM tl_c4g_forum_post a, tl_c4g_forum b, tl_c4g_forum_thread c ' .
                "WHERE c.id = a.pid AND b.id = a.forum_id AND a.forum_id = ? AND ((a.loc_geox<>'' AND a.loc_geoy<>'') OR a.loc_data_type <> '')")
                ->execute($forumId);

        return $posts->fetchAllAssoc();
    }

    /**
     * @param int $forumId
     * @return array
     */
    public function getPostsFromDBForPopupExtension($forumId)
    {
        $posts = $this->Database->prepare(
                'SELECT a.id, a.subject, a.linkname, a.linkurl, a.loc_osm_id, b.map_override_locationstyle, a.locstyle, a.text ' .
                'FROM tl_c4g_forum_post a, tl_c4g_forum b ' .
                "WHERE b.id = a.forum_id AND a.forum_id = ? AND a.loc_osm_id <> '' AND b.map_type = ?")
                ->execute($forumId, 'OSMID')->fetchAllAssoc();

        foreach ($posts as &$post) {
            if (empty($post['map_override_locationstyle']) || $post['map_override_locationstyle'] == false) {
                unset($post['locstyle']);
            }
        }

        return $posts;
    }

    /**
     * @param array $post
     * @return array
     */
    public function getMapLocationForPost($post, $paramForumbox, $paramForum)
    {
        $location = [];
        $location['id'] = 900000 + $post['id'];
        $location['geox'] = $post['loc_geox'];
        $location['geoy'] = $post['loc_geoy'];
        $location['type'] = $post['loc_data_type'];
        $location['content'] = html_entity_decode($post['loc_data_content']);
        $location['locstyle'] = $post['locstyle'];
        $location['threadname'] = $post['threadname'];
        $location['label'] = '';
        switch ($post['map_label']) {
            case 'SUBJ':
                $location['label'] = $post['subject'];

                break;
            case 'LINK':
                $location['label'] = $post['linkname'];

                break;
            case 'CUST':
                $location['label'] = $post['loc_label'];

                break;
        }

        $location['graphicTitle'] = '';
        switch ($post['map_tooltip']) {
            case 'SUBJ':
                $location['graphicTitle'] = $post['subject'];

                break;
            case 'LINK':
                $location['graphicTitle'] = $post['linkname'];

                break;
            case 'CUST':
                $location['graphicTitle'] = $post['loc_tooltip'];

                break;
        }

        $location['popupInfo'] = '';
        switch ($post['map_popup']) {
            case 'SUBJ':
                if ($this->frontendUrl) {
                    $location['popupInfo'] = '<a href="' . $this->getUrlForPost($post['id'], $paramForumbox, $paramForum) . '">' . $post['subject'] . '</a>';
                }

                break;
            case 'POST':
                $location['popupInfo'] = $post['text'];

                break;
            case 'SUPO':
                $location['popupInfo'] =
                    '<h2>' . $post['subject'] . '</h2>' .
                    $post['text'];

                break;
        }

        $location['linkurl'] = '';
        switch ($post['map_link']) {
            case 'POST':
                if ($this->frontendUrl) {
                    $location['linkurl'] = $this->getUrlForPost($post['id'], $paramForumbox, $paramForum);
                }

                break;
            case 'THREA':
                if ($this->frontendUrl) {
                    $location['linkurl'] = $this->getUrlForThread($post['threadid'], $paramForumbox, $paramForum);
                }

                break;
            case 'PLINK':
                $location['linkurl'] = $post['linkurl'];

                break;
        }

        //Attributes for Map features
        $location['attr']['name'] = $post['subject'];
        $location['attr']['note'] = $post['text'];
        $location['attr']['website'] = $post['linkurl'];
        $location['attr']['parent'] = $post['threadname'];

        return $location;
    }

    /**
     * @param array $post
     * @return array
     */
    public function getPopupExtendInfoForPost($post)
    {
        $location = [];
        $location['id'] = 900000 + $post['id']; //because idk \:D/
        $location['osmid'] = $post['loc_osm_id'];
        $location['locstyle'] = $post['locstyle'];
        $location['subject'] = $post['subject'];
        $location['text'] = $post['text'];
        $location['link'] = $post['linkurl'];

        return $location;
    }

    /**
     * @param int $forumId
     */
    public function getMapLocationsForForum($forumId, $paramForumbox, $paramForum)
    {
        $locations = [];
        $posts = $this->getPostsFromDBForMap($forumId);
        foreach ($posts as $post) {
            if ($post['id'] != self::$postIdToIgnoreInMap) {
                $locations[] = $this->getMapLocationForPost($post, $paramForumbox, $paramForum);
            }
        }

        $forums = $this->Database->prepare('SELECT id FROM tl_c4g_forum WHERE pid = ?')->execute($forumId)->fetchAllAssoc();
        foreach ($forums as $forum) {
            $locations = array_merge($locations, $this->getMapLocationsForForum($forum['id'], $paramForumbox, $paramForum));
        }

        return $locations;
    }

    /**
     * @param int $forumId
     */
    public function getPopupExtensionsForForum($forumId)
    {
        $locations = [];
        $posts = $this->getPostsFromDBForPopupExtension($forumId);
        foreach ($posts as $post) {
            if ($post['id'] != self::$postIdToIgnoreInMap) {
                $locations[] = $this->getPopupExtendInfoForPost($post);
            }
        }

        $forums = $this->Database->prepare('SELECT id FROM tl_c4g_forum WHERE pid = ?')->execute($forumId)->fetchAllAssoc();
        foreach ($forums as $forum) {
            $locations = array_merge($locations, $this->getPopupExtensionsForForum($forum['id']));
        }

        return $locations;
    }

    /**
     * @param int $forumId
     */
    public function getLocStylesForForum($forumId)
    {
        $stmObj = $this->Database->prepare(
                'SELECT map_override_locstyles AS locstyles ' .
                'FROM tl_c4g_forum ' .
                'WHERE id = ?')
                ->execute($forumId);

        $ids = self::deserializeIds($stmObj->locstyles);
        if (count($ids) > 0) {
            $locStylesMarkers = implode(',', array_fill(0, count($ids), '?'));
            $locStyles = $this->Database->prepare('SELECT * FROM tl_c4g_map_locstyles WHERE id IN (' . $locStylesMarkers . ') ORDER BY name')->execute(...$ids);
        } else {
            $locStyles = $this->Database->prepare('SELECT * FROM tl_c4g_map_locstyles ORDER BY name')->execute();
        }

        return $locStyles->fetchAllAssoc();
    }

    /**
     * @param int $forumId
     * @param int $urlType 0=forum, 1=forumbox, 2=forumintro
     */
    public function getUrlForForum($forumId, $urlType = 0, $sUrl = false, $paramForumbox, $paramForum)
    {
        if ($sUrl !== false) {
            $this->frontendUrl = $sUrl;
        }
        $paramForum = $paramForum ? $paramForum : 'forum';
        $paramForumbox = $paramForumbox ? $paramForumbox : 'forumbox';

        switch ($urlType) {
            case 1:
                $action = $paramForumbox;

                break;
            case 2:
                $action = 'forumintro';

                break;
            default:
                $action = $paramForum;
        }

        return strtok($this->frontendUrl, '?') . '?state=' . $action . ':' . $forumId;
    }

    /**
     * @param int $threadId
     * @param int $forumId
     */
    public function getUrlForThread($threadId, $forumId = 0, $sUrl = false, $paramForum = 'forum')
    {
        if ($sUrl !== false) {
            $this->frontendUrl = $sUrl;
        }
        if ($forumId == 0) {
            $data = $this->Database->prepare(
                'SELECT pid FROM tl_c4g_forum_thread WHERE id=?')
                                     ->execute($threadId);
            $forumId = $data->pid;
        }
        $paramForum = $paramForum ? $paramForum : 'forum';

        return strtok($this->frontendUrl, '?') . '?state=' . $paramForum . ':' . $forumId . ';readthread:' . $threadId;
    }

    /**
     * @param int $postId
     */
    public function getUrlForPost($postId, $sUrl = false, $paramForumbox, $paramForum)
    {
        if ($sUrl !== false) {
            $this->frontendUrl = $sUrl;
        }
        $data = $this->Database->prepare(
                'SELECT forum_id FROM tl_c4g_forum_post WHERE id=?')
                ->execute($postId);

        return strtok($this->frontendUrl, '?') . '?state=' . $paramForum . ':' . $data->forum_id . ';readpost:' . $postId;
    }

    /**
     * @return array
     */
    public function getMapForums()
    {
        $forums = $this->Database->prepare(
                  'SELECT id, name ' .
                  'FROM tl_c4g_forum ' .
                  'WHERE (enable_maps = ? OR enable_maps_inherited=?)')
                  ->execute(1, 1);

        return $forums->fetchAllAssoc();
    }

    /**
     * Send mail from data stored in Temp file
     * @param array $data
     */
    public function sendMail($data)
    {
        try {
            $eMail = new \Email();
            $eMail->charset = $data['charset'];
            $eMail->from = $data['from'];
            $eMail->subject = $data['subject'];
            $eMail->text = $data['text'];
            $eMail->sendTo($data['to']);

            unset($eMail);
        } catch (Swift_RfcComplianceException $e) {
            C4gLogModel::addLogEntry('forum', $e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Perform CRON job from temporary file
     * @param string $filename
     */
    public function performCron($filename)
    {
        $strPath = System::getContainer()->getParameter('kernel.project_dir') . '/system/tmp/' . $filename . '.tmp';

        // Read the file content if it exists
        if (file_exists($strPath)) {
            $cron = StringUtil::deserialize(file_get_contents($strPath), true);
            foreach ($cron as $data) {
                switch ($data['command']) {
                    case 'sendmail':
                        $this->sendMail($data);
                        @unlink($strPath);

                        break;
                    case 'create_sitemap':
                        $this->createXMLSitemap($data);

                        break;
                }
            }
        }
    }

    /**
     * Generate C4GForum Cron-Job for generating the sitemap asynchronously
     */
    public function generateSitemapCronjob($module, $forumId = 0)
    {
        if ($module->c4g_forum_sitemap) {

            // only generate sitemap when guests are allowed to read forum posts/threads
            if ($forumId == 0) {
                $generate = true;
            } else {
                $this->checkGuestRights = true;
                $generate = ($this->checkPermission($forumId, 'readpost'));
                $this->checkGuestRights = false;
            }

            if ($generate) {
                $this->Database->prepare(
                        'UPDATE tl_module SET ' .
                        'c4g_forum_sitemap_updated=? ' .
                        'WHERE id = ?'
                )->executeUncached(time(), $module->id);
                $data['command'] = 'create_sitemap';
                $data['filename'] = $module->c4g_forum_sitemap_filename;
                $data['contents'] = StringUtil::deserialize($module->c4g_forum_sitemap_contents, true);
                $data['startforum'] = $module->c4g_forum_startforum;
                $data['param_forum'] = $module->c4g_forum_param_forum;
                $data['param_forumbox'] = $module->c4g_forum_param_forumbox;
                $cron[] = $data;
                $filename = md5(uniqid(mt_rand(), true));
                $objFile = fopen(System::getContainer()->getParameter('kernel.project_dir') . '/system/tmp/' . $filename . '.tmp', 'wb');
                fputs($objFile, serialize($cron));
                fclose($objFile);

                return $filename;
            }
        }

        return false;
    }
    public function createNewSubforum($forumId, $groupId)
    {
        $parentForum = $this->Database->prepare('SELECT * FROM tl_c4g_forum WHERE id=?')->execute($forumId)->fetchAssoc();
        $group = $this->Database->prepare('SELECT name FROM tl_member_group WHERE id=?')->execute($groupId)->fetchAssoc();
        $set = [];
        $set['name'] = 'Ticketsystem: ' . $group['name'];
        $set['pid'] = $forumId;
        $set['published'] = 1;
        $groupArray[] = $groupId;
        $set['define_groups'] = 1;
        $set['member_groups'] = serialize($groupArray);
        if (!$set['member_groups']) {
            $set['member_groups'] = $parentForum['member_groups'];
        }
        $set['admin_groups'] = $parentForum['admin_groups'];
        $set['member_rights'] = $parentForum['member_rights'];
        $set['admin_rights'] = $parentForum['admin_rights'];
        $set['member_id'] = $groupId;
        $set['default_author'] = $parentForum['default_author'];
        $set['tstamp'] = time();

        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }

        $this->Database->prepare("INSERT INTO tl_c4g_forum SET " . implode(', ', $sqlSet))->execute(...$params);

        return $this->Database->prepare('SELECT * FROM tl_c4g_forum WHERE pid=? AND member_id =?')->execute($forumId, $groupId)->fetchAssoc();
    }
    public function createNewTicketForum($forumId, $concerning, $subject)
    {
        $parentForum = $this->Database->prepare('SELECT * FROM tl_c4g_forum WHERE id=?')->execute($forumId)->fetchAssoc();
        $set = [];
        $set['name'] = $subject;
        $set['concerning'] = $concerning;
        $set['pid'] = $forumId;
        $set['published'] = 1;
//      $set['define_rights'] = 1;
        $set['member_groups'] = $parentForum['member_groups'];
        $set['admin_groups'] = $parentForum['admin_groups'];
        $set['member_rights'] = $parentForum['member_rights'];
        $set['admin_rights'] = $parentForum['admin_rights'];
        $set['default_author'] = $parentForum['default_author'];
        $set['tstamp'] = time();

        $sqlSet = [];
        $params = [];
        foreach ($set as $k => $v) {
            $sqlSet[] = "`$k`=?";
            $params[] = $v;
        }

        $this->Database->prepare("INSERT INTO tl_c4g_forum SET " . implode(', ', $sqlSet))->execute(...$params);

        return $this->Database->prepare('SELECT * FROM tl_c4g_forum WHERE pid=? AND concerning=?')->execute($forumId, $concerning)->fetchAssoc();
    }

    /**
     * Create XML sitemap
     */
    public function createXMLSitemap($data)
    {
        $path = 'share/';
        $objFile = fopen($path . $data['filename'] . '.xml', 'wb');

        fputs($objFile, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n");

        $this->checkGuestRights = true;

        //if ($data['startforum']==0) {
        $idfield = 'pid';
        /*}
        else {
            $idfield = 'id';
        }*/

        $url = parse_url($_SERVER['HTTP_REFERER']);
        $scheme = '';
        if ($url['scheme']) {
            $scheme = $url['scheme'] . '://';
        }
        $path = '';
        if ($url['path']) {
            $path = $url['path'];
        }
        $this->frontendUrl = $scheme . $url['host'] . $path;

        $forums = $this->getForumsFromDB($data['startforum'], true, true, $idfield, false);

        // generate URLs for forum intropages
        if (array_search('INTROS', $data['contents']) !== false) {
            foreach ($forums as $forum) {
                if (!$forum['sitemap_exclude'] && $forum['use_intropage']) {
                    fputs($objFile, '<url><loc>' . $this->getUrlForForum($forum['id'], 2, false, $data['param_forumbox'], $data['param_forum']) . "</loc></url>\n");
                }
            }
        }

        // generate URLs for forums
        if (array_search('FORUMS', $data['contents']) !== false) {
            foreach ($forums as $forum) {
                if (!$forum['sitemap_exclude']) {
                    if ($forum['subforums'] > 0) {
                        $urltype = 1;
                    } else {
                        $urltype = 0;
                    }
                    fputs($objFile, '<url><loc>' . $this->getUrlForForum($forum['id'], $urltype, false, $data['param_forumbox'], $data['param_forum']) . "</loc></url>\n");
                }
            }
        }

        // generate URLs for threads
        if (array_search('THREADS', $data['contents']) !== false) {
            foreach ($forums as $forum) {

                // check if guests have the right to read posts/threads
                if (!$forum['sitemap_exclude'] &&
                        $this->checkPermissionWithData('readpost', $forum['member_groups'], $forum['admin_groups'],
                        $forum['guest_rights'], $forum['member_rights'], $forum['admin_rights'])) {
                    $threads = $this->getThreadsFromDB($forum['id']);
                    foreach ($threads as $thread) {
                        fputs($objFile, '<url><loc>' . $this->getUrlForThread($thread['id'], $forum['id'], false, $data['param_forum']) . "</loc></url>\n");
                    }
                }
            }
        }

        $this->checkGuestRights = false;

        fputs($objFile, '</urlset>');
        fclose($objFile);
    }

    /**
     * Hook implemented to prevent Contao from deleting Sitemap XML files (referenced in config.php!)
     */
    public function removeOldFeedsHook()
    {
        $arrFeeds = [];
        $objSitemaps = Database::getInstance()->prepare("SELECT c4g_forum_sitemap_filename FROM tl_module WHERE type='c4g_forum' AND c4g_forum_sitemap='1' AND c4g_forum_sitemap_filename!=''")
            ->execute();

        while ($objSitemaps->next()) {
            $arrFeeds[] = $objSitemaps->c4g_forum_sitemap_filename;
        }

        Database::getInstance()->prepare("UPDATE tl_module  SET c4g_forum_sitemap_updated=0 WHERE type='c4g_forum' AND c4g_forum_sitemap='1' AND c4g_forum_sitemap_filename!=''")
            ->execute();

        return $arrFeeds;
    }

    /**
     * @param Database $database
     */
    public static function isGoogleMapsUsed($database)
    {
        // @todo
        // $services = $database->prepare(
        // 		'SELECT DISTINCT c.provider FROM tl_c4g_forum a, tl_c4g_maps b, tl_c4g_map_prof_services c '.
        // 		'WHERE b.id = a.map_id AND b.profile=c.pid AND c.provider=?')->execute('google');
        // return ($services->numRows > 0);
        return false;
    }

    /**
     * @param $forumType
     * @param $lngStrg
     * @param $language
     * @return mixed
     */
    public static function getTypeText($forumType, $lngStrg, $language = '')
    {
        if (
            !is_array($GLOBALS['TL_LANG'] ?? null) ||
            !array_key_exists('C4G_FORUM', $GLOBALS['TL_LANG']) ||
            !is_array($GLOBALS['TL_LANG']['C4G_FORUM']) ||
            (!array_key_exists('DISCUSSION', $GLOBALS['TL_LANG']['C4G_FORUM']) && !array_key_exists('DISCUSSIONS', $GLOBALS['TL_LANG']['C4G_FORUM']))
        ) {
            System::loadLanguageFile('frontendModules');
        }
        $sTitle = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS'][$lngStrg] ?? ($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION'][$lngStrg] ?? $lngStrg);
        if ($forumType == 'QUESTIONS' && ($GLOBALS['TL_LANG']['C4G_FORUM']['QUESTIONS'][$lngStrg] ?? null)) {
            $sTitle = $GLOBALS['TL_LANG']['C4G_FORUM']['QUESTIONS'][$lngStrg];
        } elseif ($forumType == 'TICKET' && ($GLOBALS['TL_LANG']['C4G_FORUM']['TICKET'][$lngStrg] ?? null)) {
            $sTitle = $GLOBALS['TL_LANG']['C4G_FORUM']['TICKET'][$lngStrg];
        }

        if ($language) {
            $sTitle = $sTitle . ' (' . strtoupper($language) . ')';
        }

        return $sTitle;
    }

    /**
     * Safely deserializes and casts IDs to integers.
     * @param mixed $ids
     * @return array
     */
    public static function deserializeIds($ids)
    {
        $cleanIds = [];
        $stack = is_array($ids) ? $ids : (is_null($ids) ? [] : [$ids]);

        while (!empty($stack)) {
            $current = array_shift($stack);

            if (is_array($current)) {
                foreach ($current as $val) {
                    $stack[] = $val;
                }
            } elseif (is_numeric($current)) {
                $val = (int)$current;
                if ($val >= 0) {
                    $cleanIds[] = $val;
                }
            } elseif (is_string($current)) {
                $current = trim($current);
                if ($current === '' || $current === 'a:0:{}') {
                    continue;
                }
                
                if (is_numeric($current)) {
                    $val = (int)$current;
                    if ($val >= 0) {
                        $cleanIds[] = $val;
                    }
                    continue;
                }

                if (preg_match('/^[asiO]:\d+:/', $current)) {
                    $deserialized = \Contao\StringUtil::deserialize($current, true);
                    if ($deserialized !== $current) {
                        $stack[] = $deserialized;
                    }
                } elseif (strpos($current, ',') !== false) {
                    foreach (explode(',', $current) as $val) {
                        $stack[] = $val;
                    }
                }
            }
        }

        $result = array_values(array_unique($cleanIds));

        return empty($result) ? [0] : $result;
    }

    /**
     * @param $forumId
     * @param $groupId
     * @param $subject
     * @param $text
     * @param $ticketId
     */
    public static function create_ticket($forumId, $groupId, $subject, $text, $ticketId)
    {
        $forum = new C4GForum(\ModuleModel::findByPk($forumId));
        $forum->autoTicket($forumId, $groupId, $subject, $text, $ticketId);
    }

    public static function isMemberModeratorOfForum($memberId, $forumId)
    {
        $forumModel = C4gForumModel::findByPk($forumId);
        $memberModel = MemberModel::findByPk($memberId);
        $moderatorGroups = StringUtil::deserialize($forumModel->admin_groups, true);
        $memberGroups = StringUtil::deserialize($memberModel->groups, true);

        foreach ($moderatorGroups as $group) {
            if (in_array($group, $memberGroups)) {
                return true;
            }
        }

        return false;
    }
}
