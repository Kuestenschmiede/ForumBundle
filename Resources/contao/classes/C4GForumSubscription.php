<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */

namespace con4gis\ForumBundle\Resources\contao\classes;

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
        public function insertSubscriptionSubforumIntoDB($forumId, $userId, $subscriptionOnlyThreads)
        {

            $set                 = array();
            $set ['pid']         = $forumId;
            $set ['member']      = $userId;
            $set ['thread_only'] = $subscriptionOnlyThreads;
            $objInsertStmt       = $this->Database->prepare("INSERT INTO tl_c4g_forum_subforum_subscription %s")->set($set)->execute();

            return $objInsertStmt->affectedRows;
        }

        /**
         * @param int $threadId
         * @param int $userId
         */
        public function insertSubscriptionThreadIntoDB($threadId, $userId)
        {

            $set            = array();
            $set ['pid']    = $threadId;
            $set ['member'] = $userId;

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
         * @param $sUrl
         * @return string
         */
        public function sendSubscriptionEMail($subscribers, $threadId, $sendKind, $sUrl=false, $forumType='DISCUSSIONS')
        {

            \System::loadLanguageFile("tl_c4g_forum");

            $thread = $this->helper->getThreadAndForumNameAndMailTextFromDBUncached($threadId);

            $cron      = array();
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


                    $sActionType = "POST";

                    $aMailData = array(
                        "USERNAME"                 => "",
                        "RESPONSIBLE_USERNAME"     => "",
                        "ACTION_NAME"              => "",
                        "ACTION_PRE"               => "",
                        "ACTION_NAME_WITH_SUBJECT" => "",
                        "FORUMNAME"                => "",
                        "THREADNAME"               => "",
                        "POST_SUBJECT"             => "",
                        "POST_CONTENT"             => "",
                        "DETAILS_LINK"             => "",
                        "UNSUBSCRIBE_LINK"         => "",
                        "UNSUBSCRIBE_ALL_LINK"     => "",
                    );

                    // check if subscriber still has permission to get subscription mails
                    if ($this->helper->checkPermission($thread['forumid'], $sPerm, $subscriber['memberId'])) {
                        switch ($sendKind) {
                            case "new" :
                                $subjectAddition                       = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_' . $sType . '_MAIL_NEW'];
                                $aMailData['ACTION_NAME']              = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_NEW_' . $sActionType . ''];
                                $aMailData['ACTION_NAME_WITH_SUBJECT'] = sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_NEW_' . $sActionType . '_WITH_SUBJECT'], $this->MailCache ['subject']);

                                break;
                            case "edit" :
                                $subjectAddition                       = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_' . $sType . '_MAIL_EDIT'];
                                $aMailData['ACTION_NAME']              = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_EDIT_' . $sActionType . ''];
                                $aMailData['ACTION_NAME_WITH_SUBJECT'] = sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_EDIT_' . $sActionType . '_WITH_SUBJECT'], $this->MailCache ['subject']);

                                break;
                            case "delete" :
                                $subjectAddition                       = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_' . $sType . '_MAIL_DELETE'];
                                $aMailData['ACTION_NAME']              = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_DEL_' . $sActionType . ''];
                                $aMailData['ACTION_NAME_WITH_SUBJECT'] = sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_DEL_' . $sActionType . '_WITH_SUBJECT'], $this->MailCache ['subject']);

                                break;
                            case "delThread" :
                                $subjectAddition                       = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_' . $sType . '_MAIL_DELTHREAD'];
                                $aMailData['ACTION_NAME']              = C4GForumHelper::getTypeText($forumType,'SUBSCRIPTION_MAIL_ACTION_DEL_THREAD');
                                $aMailData['ACTION_NAME_WITH_SUBJECT'] = sprintf(C4GForumHelper::getTypeText($forumType,'SUBSCRIPTION_MAIL_ACTION_DEL_THREAD_WITH_SUBJECT'), $thread['threadname']);
                                $sActionType                           = "THREAD";
                                break;
                            case "moveThread" :
                                $subjectAddition                       = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_' . $sType . '_MAIL_MOVETHREAD'];
                                $aMailData['ACTION_NAME']              = C4GForumHelper::getTypeText($forumType,'SUBSCRIPTION_MAIL_ACTION_MOVE_THREAD');
                                $aMailData['ACTION_NAME_WITH_SUBJECT'] = sprintf(C4GForumHelper::getTypeText($forumType,'SUBSCRIPTION_MAIL_ACTION_MOVE_THREAD_WITH_SUBJECT'), $this->MailCache ['moveThreadOldName'], $thread['threadname']);
                                $sActionType                           = "THREAD";
                                break;
                            case "newThread" : // only subforum
                                $subjectAddition                       = C4GForumHelper::getTypeText($forumType,'NEW_THREAD');
                                $aMailData['ACTION_NAME']              = C4GForumHelper::getTypeText($forumType,'SUBSCRIPTION_MAIL_ACTION_NEW_THREAD');
                                $aMailData['ACTION_NAME_WITH_SUBJECT'] = sprintf(C4GForumHelper::getTypeText($forumType,'SUBSCRIPTION_MAIL_ACTION_NEW_THREAD_WITH_SUBJECT'), $thread['threadname']);
                                $sActionType                           = "THREAD";
                                break;
                        }

                        $aMailData['ACTION_PRE'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_MAIL_ACTION_' . $sActionType . '_PRE'];

                        \System::log('[C4G] ' . $aMailData['ACTION_NAME'] . " in \"" . $thread['forumname'] . "\": " . $thread['threadname'], __METHOD__, TL_GENERAL);

                        $data            = array();
                        $data['command'] = 'sendmail';
                        $data['charset'] = 'UTF-8';

                        if ($GLOBALS ['TL_CONFIG'] ['useSMTP'] and $this->checkmail($GLOBALS ['TL_CONFIG'] ['smtpUser'])) {
                            $data['from'] = $GLOBALS ['TL_CONFIG'] ['smtpUser'];
                        } else {
                            $data['from'] = $GLOBALS ['TL_CONFIG'] ['adminEmail'];
                        }

                        $data['subject'] = sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['SUBSCRIPTION_' . $sType . '_MAIL_SUBJECT'], $subjectAddition, $GLOBALS['TL_CONFIG']['websiteTitle'], $thread['forumname'], $thread['threadname']);

                        $aMailData['USERNAME']             = $subscriber['username'];
                        $aMailData['RESPONSIBLE_USERNAME'] = $this->User->username;
                        $aMailData['FORUMNAME']            = $thread['forumname'];
                        $aMailData['THREADNAME']           = $thread['threadname'];


                        // umformatierung BBC-Quotes
                        $this->MailCache ['post'] = preg_replace('/\[quote=([^\]]+)\]([\s\S]*?)\[\/quote\]/i', '"$2" (Zitat von $1)', $this->MailCache ['post']);
                        $this->MailCache ['post'] = preg_replace('/\[quote\]([\s\S]*?)\[\/quote\]/i', '"$1" (Zitat)', $this->MailCache ['post']);
                        // umformatierung Spoiler
                        $this->MailCache ['post'] = preg_replace('/\[spoiler\]([\s\S]*?)\[\/spoiler\]/i', '(Spoiler)', $this->MailCache ['post']);
                        // umformatierung Listen
                        $this->MailCache ['post'] = preg_replace('/\[\*\]([^\[]*)/i', '\n- $1', $this->MailCache ['post']);


                        // entferne BBCodes
                        $this->MailCache ['post'] = preg_replace('/\[[^\[\]]*\]/i', '', $this->MailCache ['post']);

                        // set post subject and content
                        $aMailData['POST_SUBJECT'] = $this->MailCache ['subject'];
                        $aMailData['POST_CONTENT'] = $this->MailCache ['post'];

                        // building links
                        if ($sType == "SUBFORUM") {

                            $aMailData['DETAILS_LINK']         = $this->helper->getUrlForForum($thread['forumid'], $sUrl);
                            $aMailData['UNSUBSCRIBE_LINK']     = $this->generateUnsubscribeLinkSubforum($thread['forumid'], $subscriber['email'], $sUrl);
                            $aMailData['UNSUBSCRIBE_ALL_LINK'] = $this->generateUnsubscribeLinkAll($subscriber['email'], $sUrl);

                        } else {

                            $aMailData['DETAILS_LINK']         = $this->helper->getUrlForThread($threadId, $thread['forumid'], $sUrl);
                            $aMailData['UNSUBSCRIBE_LINK']     = $this->generateUnsubscribeLinkThread($threadId, $subscriber['email'], $sUrl);
                            $aMailData['UNSUBSCRIBE_ALL_LINK'] = $this->generateUnsubscribeLinkAll($subscriber['email'], $sUrl);
                        }



                        $mailText     = trim($thread['mail']) ?: $GLOBALS['TL_LANG']['tl_c4g_forum']['default_subscription_text'];
                        $data['text'] = $this->parseMailText($mailText, $aMailData);
                        $data['to'] = $subscriber['email'];

                        $cron[] = $data;

                        $addresses[$subscriber ['email']] = true;
                    }
                }
            }


            if ($cron) {
                // send mails via cron job, (will be triggered in Javascript part)
                $filename = md5(uniqid(mt_rand(), true));
                $objFile  = fopen(TL_ROOT . '/system/tmp/' . $filename . '.tmp', 'wb');
                fputs($objFile, serialize($cron));
                fclose($objFile);
            }

            return $filename;
        }


        /**
         * @param $sText
         * @param $aData
         * @return mixed|string
         */
        private function parseMailText($sText, $aData)
        {

            $sText = html_entity_decode($sText);

            foreach ($aData as $key => $value) {
                $sText = str_replace('##' . $key . '##', $value, $sText);
            }


            return $sText;


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
                        $message = sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['UNSUBSCRIBE_SUBFORUM_LINK_SUCCESS'], $this->helper->getForumNameFromDB($values[0]), $member->username);
                    }
                }

            }

            if (!$message) {
                $return['message'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['UNSUBSCRIBE_SUBFORUM_LINK_FAILED'];
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
                    return sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['UNSUBSCRIBE_ALL_LINK_SUCCESS'], $member->username);
                }

            }

            return $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSIONS']['UNSUBSCRIBE_ALL_LINK_FAILED'];
        }
    }

?>