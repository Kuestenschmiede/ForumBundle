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

namespace con4gis\ForumBundle\Resources\contao\classes;

use con4gis\CoreBundle\Resources\contao\classes\notification\C4GNotification;
use con4gis\ForumBundle\Resources\contao\models\C4GForumSubscriptionModel;
use con4gis\ForumBundle\Resources\contao\models\C4GThreadModel;
use con4gis\ForumBundle\Resources\contao\models\C4GThreadSubscriptionModel;

/**
 * Class C4GForumSubscription
 * @package con4gis\ForumBundle\Resources\contao\classes
 */
    class C4GForumSubscription
    {

        /**
         * @var null
         */
        protected $Database = null;
        /**
         * @var null
         */
        protected $Environment = null;
        /**
         * @var null|string
         */
        protected $ForumName = null;
        /**
         * @var null
         */
        protected $User = null;
        /**
         * @var null|string
         */
        public $frontendUrl = null;

        /**
         * @var array
         */
        public $MailCache = array();

        /**
         * @var C4GForumHelper
         */
        protected $helper = null;

        /**
         * Construktor
         */
        public function __construct($helper = null, $database, $environment = null, $user = null, $forumName = "", $frontendUrl = "", $forumType = "FORUM")
        {
            $this->helper      = $helper;
            $this->Database    = $database;
            $this->User        = $user;
            $this->Environment = $environment;
            $this->frontendUrl = $frontendUrl;
            if ($forumName == "") {
                $this->ForumName = $helper->getTypeText($forumType,'FORUM');
            } else {
                $this->ForumName = $forumName;
            }
        }

        /**
         * Give back a subforum subscription
         *
         * @param int $forumId
         * @param int $userId
         */
        public function getSubforumSubscriptionFromDB($forumId, $userId)
        {
            return $this->Database->prepare("SELECT id AS subscriptionId " . "FROM tl_c4g_forum_subforum_subscription " . "WHERE pid = ? AND member = ?")->execute($forumId, $userId)->subscriptionId;
        }

        /**
         * Give back a subforum subscription
         *
         * @param int $forumId
         * @param int $userId
         */
        public function getCompleteSubforumSubscriptionFromDB($forumId, $userId)
        {
            return $this->Database->prepare("SELECT id AS subscriptionId " . "FROM tl_c4g_forum_subforum_subscription " . "WHERE pid = ? AND member = ? AND thread_only=?")->execute($forumId, $userId, false)->subscriptionId;
        }

        /**
         *
         * Give back a thread subscription
         *
         * @param int $threadId
         * @param int $userId
         */
        public function getThreadSubscriptionFromDB($threadId, $userId)
        {
            return $this->Database->prepare("SELECT id AS subscriptionId " . "FROM tl_c4g_forum_thread_subscription " . "WHERE pid = ? AND member = ?")->execute($threadId, $userId)->subscriptionId;
        }


        /**
         * Give back subscribers of a given forum id from DB as array.
         *
         * @param $forumId
         * @param $all
         *
         * @return mixed
         */
        public function getForumSubscribersFromDB($forumId, $all)
        {

            if ($all) {
                $aReturn = $this->Database->prepare("SELECT a.member AS memberId, b.email AS email, b.username as username, 1 AS type " . "FROM tl_c4g_forum_subforum_subscription a " . "LEFT JOIN tl_member b ON b.id = a.member " . "WHERE a.pid = ?")->execute($forumId)->fetchAllAssoc();
            } else {
                $aReturn = $this->Database->prepare("SELECT a.member AS memberId, b.email AS email, b.username as username, 1 as type " . "FROM tl_c4g_forum_subforum_subscription a " . "LEFT JOIN tl_member b ON b.id = a.member " . "WHERE a.pid = ? AND a.thread_only = 0")->execute($forumId)->fetchAllAssoc();
            }


            foreach ($aReturn as $key => $aItem) {
                if (empty($aReturn[$key]['username'])) {
                    $aReturn[$key]['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'];
                }
            }

            return $aReturn;
        }

        /**
         * Give back all subscribers of a given thread id from DB as array.
         *
         * @param $threadId
         *
         * @return mixed
         */
        public function getThreadSubscribersFromDB($threadId)
        {

            $aReturn = $this->Database->prepare("SELECT a.member AS memberId, b.email AS email, b.username as username, 0 as type " . "FROM tl_c4g_forum_thread_subscription a " . "LEFT JOIN tl_member b ON b.id = a.member " . "WHERE a.pid = ?")->execute($threadId)->fetchAllAssoc();

            foreach ($aReturn as $key => $aItem) {
                if (empty($aReturn[$key]['username'])) {
                    $aReturn[$key]['username'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['DELETED_USER'];
                }
            }

            return $aReturn;

        }

        /**
         * @param int $forumId
         * @param int $userId
         */
        public function insertSubscriptionSubforumIntoDB($forumId, $userId, $putVars)
        {

            $set                 = array();
            $set['pid']         = $forumId;
            $set['member']      = $userId;
//            $set['thread_only'] = $subscriptionOnlyThreads;
            $set['deletedPost'] = $putVars['deletedPost'] === 'true' ? '1' : '0';
            $set['editedPost'] = $putVars['editedPost'] === 'true' ? '1' : '0';
            $set['newPost'] = $putVars['newPost'] === 'true' ? '1' : '0';
            $set['newThread'] = $putVars['newThread'] === 'true' ? '1' : '0';
            $set['movedThread'] = $putVars['movedThread'] === 'true' ? '1' : '0';
            $set['deletedThread'] = $putVars['deletedThread'] === 'true' ? '1' : '0';
            $objInsertStmt       = $this->Database->prepare("INSERT INTO tl_c4g_forum_subforum_subscription %s")->set($set)->execute();

            return $objInsertStmt->affectedRows;
        }

        /**
         * @param int $threadId
         * @param int $userId
         */
        public function insertSubscriptionThreadIntoDB($threadId, $userId, $putVars)
        {
            $set            = array();
            $set ['pid']    = $threadId;
            $set ['member'] = $userId;
            $set['deletedPost'] = $putVars['deletedPost'] === 'true' ? '1' : '0';
            $set['editedPost'] = $putVars['editedPost'] === 'true' ? '1' : '0';
            $set['newPost'] = $putVars['newPost'] === 'true' ? '1' : '0';

            $objInsertStmt = $this->Database->prepare("INSERT INTO tl_c4g_forum_thread_subscription %s")->set($set)->execute();

            return $objInsertStmt->affectedRows;
        }

        /**
         * Delete subscription by $subscriptionId
         *
         * @param int $subscriptionId
         */
        public function deleteSubscriptionThread($subscriptionId)
        {
            $objDeleteStmt = $this->Database->prepare("DELETE FROM tl_c4g_forum_thread_subscription WHERE id = ?")->execute($subscriptionId);
            if ($objDeleteStmt->affectedRows == 0) {
                return false;
            }

            return true;
        }

        /**
         * Delete subscription by $subscriptionId
         *
         * @param int $subscriptionId
         */
        public function deleteSubscriptionSubforum($subscriptionId)
        {
            $objDeleteStmt = $this->Database->prepare("DELETE FROM tl_c4g_forum_subforum_subscription WHERE id = ?")->execute($subscriptionId);
            if ($objDeleteStmt->affectedRows == 0) {
                return false;
            }

            return true;
        }

        /**
         * Delete all subscription of a member
         *
         * @param int $subscriptionId
         */
        public function deleteAllSubscriptions($memberId)
        {
            $rows = 0;

            $objDeleteStmt = $this->Database->prepare("DELETE FROM tl_c4g_forum_subforum_subscription WHERE member = ?")->execute($memberId);
            $rows += $objDeleteStmt->affectedRows;

            $objDeleteStmt = $this->Database->prepare("DELETE FROM tl_c4g_forum_thread_subscription WHERE member = ?")->execute($memberId);
            $rows += $objDeleteStmt->affectedRows;

            if ($rows == 0) {
                return false;
            }

            return true;
        }

        /**
         * Delete subscription by $threadId
         *
         * @param int $threadId
         */
        public function deleteSubscriptionForThread($threadId)
        {
            $objDeleteStmt = $this->Database->prepare("DELETE FROM tl_c4g_forum_thread_subscription WHERE pid = ?")->execute($threadId);
            if ($objDeleteStmt->affectedRows == 0) {
                return false;
            }

            return true;
        }

        /**
         * Check validity of Mail-Address
         *
         * @param string $email
         * @return boolean
         */
        protected function checkmail($email)
        {

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * @param $subscribers
         * @param $threadId
         * @param $sendKind
         * @param $forumModule
         * @param bool $sUrl
         * @param string $forumType
         * @param null $headline
         */
        public function sendSubscriptionEMail($subscribers, $threadId, $sendKind, $forumModule, $sUrl=false, $forumType='DISCUSSIONS', $headline=null)
        {
            \System::loadLanguageFile("tl_c4g_forum");
            $thread = $this->helper->getThreadAndForumNameAndMailTextFromDBUncached($threadId);
            $forumId = $thread['forumid'];
            $addresses = array();
            foreach ($subscribers as $subscriber) {
                if ((!$addresses[$subscriber ['email']]) && ($subscriber['memberId'] != $this->User->id)) {
                    if ($subscriber['type'] == 1) {
                        $sType = 'SUBFORUM';
                        $sPerm = 'subscribeforum';
                    } else {
                        $sType = 'THREAD';
                        $sPerm = 'subscribethread';
                    }

                    // check if subscriber still has permission to get subscription mails
                    if ($this->helper->checkPermission($thread['forumid'], $sPerm, $subscriber['memberId'])) {
                        /** Send Notifications via Notification center*/

                        try {
                            $threadModel = C4GThreadModel::findByPk($threadId);
                            switch ($sendKind) {
                                case "new" :
                                    if (!$this->isSubscriptionValid(
                                        'newPost',
                                        C4GThreadSubscriptionModel::findByThreadAndMember($threadId, $subscriber['memberId']),
                                        C4GForumSubscriptionModel::findByForumAndMember($forumId, $subscriber['memberId']))) {
                                        break 2;
                                    }
                                    $notification = new C4GForumNotification(C4GForumNotification::SUB_NEW_POST);
                                    $notificationIDs = unserialize($forumModule->sub_new_post);
                                    break;
                                case "edit":
                                    if (!$this->isSubscriptionValid(
                                        'editedPost',
                                        C4GThreadSubscriptionModel::findByThreadAndMember($threadId, $subscriber['memberId']),
                                        C4GForumSubscriptionModel::findByForumAndMember($forumId, $subscriber['memberId']))) {
                                        break 2;
                                    }
                                    $notification = new C4GForumNotification(C4GForumNotification::SUB_EDITED_POST);
                                    $notificationIDs = unserialize($forumModule->sub_edited_post);
                                    break;
                                case "delete" :
                                    if (!$this->isSubscriptionValid(
                                        'deletedPost',
                                        C4GThreadSubscriptionModel::findByThreadAndMember($threadId, $subscriber['memberId']),
                                        C4GForumSubscriptionModel::findByForumAndMember($forumId, $subscriber['memberId']))) {
                                        break 2;
                                    }
                                    $notification = new C4GForumNotification(C4GForumNotification::SUB_DELETED_POST);
                                    $notificationIDs = unserialize($forumModule->sub_deleted_post);
                                    break;
                                case "delThread" :
                                    if (!$this->isSubscriptionValid(
                                        'deletedThread',
                                        null,
                                        C4GForumSubscriptionModel::findByForumAndMember($forumId, $subscriber['memberId']))) {
                                        break 2;
                                    }
                                    $notification = new C4GForumNotification(C4GForumNotification::SUB_DELETED_THREAD);
                                    $notificationIDs = unserialize($forumModule->sub_deleted_thread);
                                    break;
                                case "moveThread" :
                                    if (!$this->isSubscriptionValid(
                                        'movedThread',
                                        null,
                                        C4GForumSubscriptionModel::findByForumAndMember($forumId, $subscriber['memberId']))) {
                                        break 2;
                                    }
                                    $notification = new C4GForumNotification(C4GForumNotification::SUB_MOVED_THREAD);
                                    $notificationIDs = unserialize($forumModule->sub_moved_thread);
                                    break;
                                case "newThread" :
                                    if (!$this->isSubscriptionValid(
                                        'newThread',
                                        null,
                                        C4GForumSubscriptionModel::findByForumAndMember($forumId, $subscriber['memberId']))) {
                                        break 2;
                                    }
                                    $notification = new C4GForumNotification(C4GForumNotification::SUB_NEW_THREAD);
                                    $notificationIDs = unserialize($forumModule->sub_new_thread);
                                    break;
                                default:
                                    return;
                            }

                            $notification->setTokenValue('admin_email', $GLOBALS['TL_CONFIG']['adminEmail']);
                            $notification->setTokenValue('user_email', $subscriber['email']);
                            $notification->setTokenValue('user_name', $subscriber['username']);
                            $notification->setTokenValue('threadname', $thread['threadname']);
                            $notification->setTokenValue('forumname', $thread['forumname']);
                            $notification->setTokenValue('responsible_username', $this->User->username);
                            $notification->setTokenValue('link', $this->helper->getUrlForThread($threadId, $thread['forumid'], $sUrl));
                            $notification->setTokenValue('unsubscribe_link', $this->generateUnsubscribeLinkSubforum($thread['forumid'], $subscriber['email'], $sUrl));
                            $notification->setTokenValue('unsubscribe_all_link', $this->generateUnsubscribeLinkAll($subscriber['email'], $sUrl));
                            $notification->send($notificationIDs);
                        } catch (\Throwable $e) {
                            //Todo log the exception message
//                            \System::getContainer()->get('logger')->error($e->getMessage());
                        }
                    }
                }
            }
        }


        /**
         * @param $sText
         * @param $aData
         * @return mixed|string
         */
        private function parseMailText($sText, $aData)
        {

            //$sText = htmlentities($sText);
            $sText = html_entity_decode($sText);
            $sText = strip_tags($sText);
            // first, check if we've got a language part corresponding
            // to subscriber's language
            $lang = substr(strtoupper($aData['LANGUAGE']), 0, 2);
            $langPos = strpos($sText, "##LANGUAGE_START:" . $lang . "##");
            $langFound = false;
           if($langPos !== false) {

				// language for subscriber found, get its part
				// there should be a ##LANGUAGE_END## marker
				$langPos += 21;
				$langEnd = strpos($sText, "##LANGUAGE_END##", $langPos);
				if($langEnd !== false) {
					$sText = substr($sText, $langPos, $langEnd - $langPos);
					$langFound = true;
				}
			}
			
			if(!$langFound) {
				// language for subscriber not found, just strip ALL
				// localizations from data
				$sText = preg_replace('/(##LANGUAGE_START:)(\\s|\\S)*(##LANGUAGE_END##)/', '', $sText);
			}

            foreach ($aData as $key => $value) {
                $sText = str_replace('##' . $key . '##', $value, $sText);
            }


            return strip_tags($sText);


            //        USERNAME: ##USERNAME##
            //
            //        RESPONSIBLE_USERNAME: ##RESPONSIBLE_USERNAME##
            //
            //        ACTION_NAME: ##ACTION_NAME##
            //
            //        ACTION_NAME_WITH_SUBJECT: ##ACTION_NAME_WITH_SUBJECT##
            //
            //        FORUMNAME: ##FORUMNAME##
            //
            //        THREADNAME: ##THREADNAME##
            //
            //        POST_SUBJECT: ##POST_SUBJECT##
            //
            //        POST_CONTENT: ##POST_CONTENT##
            //
            //        DETAILS_LINK: ##DETAILS_LINK##
            //
            //        UNSUBSCRIBE_LINK: ##UNSUBSCRIBE_LINK##
            //
            //        UNSUBSCRIBE_ALL_LINK: ##UNSUBSCRIBE_ALL_LINK##
            //
            //        ##LANGUAGE_START:xx##
            //        ##LANGUAGE_END##
            //
        }

        /**
         * @param string $string
         * @return string
         */
        protected function encryptLinkData($string)
        {
            return str_rot13(rtrim(base64_encode($string), '='));
        }


        /**
         * @param string $string
         * @return string
         */
        protected function decryptLinkData($string)
        {
            return base64_decode(str_rot13($string) . '==');
        }

        /**
         * @param      $threadId
         * @param      $mail
         * @param bool $sUrl
         * @return string
         */
        public function generateUnsubscribeLinkThread($threadId, $mail, $sUrl = false)
        {
            if ($sUrl !== false) {
                $this->helper->frontendUrl = $sUrl;
            }

            return strtok($this->helper->frontendUrl, '?') . '?state=unsubscribethread:' . $this->encryptLinkData($threadId . ';' . $mail);
        }

        /**
         * @param      $forumId
         * @param      $mail
         * @param bool $sUrl
         * @return string
         */
        public function generateUnsubscribeLinkSubforum($forumId, $mail, $sUrl = false)
        {
            if ($sUrl !== false) {
                $this->helper->frontendUrl = $sUrl;
            }

            return strtok($this->helper->frontendUrl, '?') . '?state=unsubscribesubforum:' . $this->encryptLinkData($forumId . ';' . $mail);
        }

        /**
         * @param      $mail
         * @param bool $sUrl
         * @return string
         */
        public function generateUnsubscribeLinkAll($mail, $sUrl = false)
        {
            if ($sUrl !== false) {
                $this->helper->frontendUrl = $sUrl;
            }

            return strtok($this->helper->frontendUrl, '?') . '?state=unsubscribeall:' . $this->encryptLinkData($mail);
        }

        /**
         * @param string $value
         */
        public function unsubscribeLinkThread($value, $forumType)
        {
            $values = explode(';', $this->decryptLinkData($value), 2);
            $thread = $this->helper->getThreadFromDB($values[0]);
            $member = $this->Database->prepare("SELECT id,username FROM tl_member " . "WHERE email=?")->execute($values[1]);
            if ($member->id) {
                $subscriptionId = $this->getThreadSubscriptionFromDB($values[0], $member->id);
                if ($subscriptionId) {
                    if ($this->deleteSubscriptionThread($subscriptionId)) {
                        $message = sprintf(C4GForumHelper::getTypeText($forumType,'UNSUBSCRIBE_THREAD_LINK_SUCCESS'), $thread['name'], $member->username);
                    }
                }

            }
            if (!$message) {
                $message = C4GForumHelper::getTypeText($forumType,'UNSUBSCRIBE_THREAD_LINK_FAILED');
            }
            $return['message']  = $message;
            $return['forumid']  = $thread['forumid'];
            $return['threadid'] = $values[0];

            return $return;
        }

        /**
         * @param string $value
         */
        public function unsubscribeLinkSubforum($value)
        {
            $values = explode(';', $this->decryptLinkData($value), 2);
            $member = $this->Database->prepare("SELECT id,username FROM tl_member " . "WHERE email=?")->execute($values[1]);
            if ($member->id) {
                $subscriptionId = $this->getSubforumSubscriptionFromDB($values[0], $member->id);
                if ($subscriptionId) {
                    if ($this->deleteSubscriptionSubforum($subscriptionId)) {
                        $message = sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_SUBFORUM_LINK_SUCCESS'), $this->helper->getForumNameFromDB($values[0]), $member->username);
                    }
                }

            }

            if (!$message) {
                $return['message'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_SUBFORUM_LINK_FAILED');
            }
            $return['message'] = $message;
            $return['forumid'] = $values[0];

            return $return;

        }

        /**
         * @param string $value
         */
        public function unsubscribeLinkAll($value)
        {
            $email  = $this->decryptLinkData($value);
            $member = $this->Database->prepare("SELECT id,username FROM tl_member " . "WHERE email=?")->execute($email);
            if ($member->id) {
                if ($this->deleteAllSubscriptions($member->id)) {
                    return sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_ALL_LINK_SUCCESS'), $member->username);
                }

            }

            return C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_ALL_LINK_FAILED');
        }

        public function isSubscriptionValid(
            string $type,
            C4GThreadSubscriptionModel $threadSubscriptionModel = null,
            C4GForumSubscriptionModel $forumSubscriptionModel = null)
        {
            switch ($type) {
                case "newPost":
                    if ($threadSubscriptionModel !== null && intval($threadSubscriptionModel->newPost) === 1) {
                        return true;
                    } else {
                        if ($forumSubscriptionModel !== null && intval($forumSubscriptionModel->newPost) === 1) {
                            return true;
                        }
                        return false;
                    }
                    breaK;
                case "editedPost":
                    if ($threadSubscriptionModel !== null && intval($threadSubscriptionModel->editedPost) === 1) {
                        return true;
                    } else {
                        if ($forumSubscriptionModel !== null && intval($forumSubscriptionModel->editedPost) === 1) {
                            return true;
                        }
                        return false;
                    }
                    break;
                case "deletedPost":
                    if ($threadSubscriptionModel !== null && intval($threadSubscriptionModel->deletedPost) === 1) {
                        return true;
                    } else {
                        if ($forumSubscriptionModel !== null && intval($forumSubscriptionModel->deletedPost) === 1) {
                            return true;
                        }
                        return false;
                    }
                    break;
                case "newThread":
                    if ($forumSubscriptionModel !== null && intval($forumSubscriptionModel->newThread) === 1) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case "movedThread":
                    if ($forumSubscriptionModel !== null && intval($forumSubscriptionModel->movedThread) === 1) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case "deletedThread":
                    if ($forumSubscriptionModel !== null && intval($forumSubscriptionModel->deletedThread) === 1) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                default:
                    return false;
                    breaK;
            }
        }

}