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

namespace con4gis\ForumBundle\Resources\contao\models;

    use Contao\Email;
    use Contao\MemberModel;
    use Contao\UserModel;

    /**
     * Class C4gForumPn
     * @package con4gis\ForumBundle\Resources\contao\models
     */
    class C4gForumPn{

        /**
         * @var string
         */
        protected static $sTable = 'tl_c4g_forum_pn';

        /**
         * @var
         */
        private $id;
        /**
         * @var
         */
        private $recipient;
        /**
         * @var
         */
        private $recipient_id;
        /**
         * @var
         */
        private $sender;
        /**
         * @var
         */
        private $sender_id;
        /**
         * @var
         */
        private $subject;
        /**
         * @var
         */
        private $message;
        /**
         * @var
         */
        private $status;

        /**
         * @var
         */
        private $dt_created;

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getRecipient()
        {
            return $this->recipient;
        }

        /**
         * @param mixed $recipient
         */
        public function setRecipient($recipient)
        {
            $this->recipient = $recipient;
        }
        /**
         * @return mixed
         */
        public function getRecipient_id()
        {
            return $this->recipient_id;
        }

        /**
         * @param mixed $recipient_id
         */
        public function setRecipient_id($recipient_id)
        {
            $this->recipient_id = $recipient_id;
        }
        /**
         * @return mixed
         */
        public function getRecipientId()
        {
            return $this->recipient_id;
        }

        /**
         * @param mixed $recipient_id
         */
        public function setRecipientId($recipient_id)
        {
            $this->recipient_id = $recipient_id;
        }

        /**
         * @return mixed
         */
        public function getSender()
        {
            return $this->sender;
        }

        /**
         * @param mixed $sender
         */
        public function setSender($sender)
        {
            $this->sender = $sender;
        }
        /**
         * @return mixed
         */
        public function getSenderId()
        {
            return $this->sender_id;
        }

        /**
         * @param mixed $sender_id
         */
        public function setSenderId($sender_id)
        {
            $this->sender_id = $sender_id;
        }
        /**
         * @return mixed
         */
        public function getSender_id()
        {
            return $this->sender_id;
        }

        /**
         * @param mixed $sender_id
         */
        public function setSender_id($sender_id)
        {
            $this->sender_id = $sender_id;
        }

        /**
         * @return mixed
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param mixed $subject
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;
        }
        /**
         * @return mixed
         */
        public function getStatus()
        {
            if(empty($this->status)){
                $this->setStatus(0);
            }
            return $this->status;
        }

        /**
         * @param mixed $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return mixed
         */
        public function getMessage()
        {
            return $this->message;
        }

        /**
         * @param mixed $message
         */
        public function setMessage($message)
        {
            $this->message = $message;
        }

        /**
         * @return mixed
         */
        public function getDtCreated()
        {
            if(empty($this->dt_created)){
                $this->dt_created = time();
            }
            return $this->dt_created;
        }

        /**
         * @param mixed $dt_created
         */
        public function setDtCreated($dt_created)
        {
            $this->dt_created = $dt_created;
        }
        /**
         * @return mixed
         */
        public function getDt_Created()
        {
            if(empty($this->dt_created)){
                $this->dt_created = time();
            }
            return $this->dt_created;
        }

        /**
         * @param mixed $dt_created
         */
        public function setDt_Created($dt_created)
        {
            $this->dt_created = $dt_created;
        }


        /**
         * @param $id
         * @return C4gForumPn|false
         */
        public static function getById($id){
            $aPn = self::getByField('id', $id)->fetchAssoc();
            self::fillUserData($aPn, true);
            $oPn = self::create($aPn);
            return $oPn;
        }


        /**
         * @param $field
         * @param $value
         * @return \Database\Result
         */
        private static function getByField($field, $value){
            return \Database::getInstance()->prepare('SELECT * FROM '.self::$sTable." WHERE ".$field." = '".$value."' ORDER BY dt_created DESC;")->execute();
        }


        /**
         * @param $recipient_id
         * @return array
         */
        public static function getByRecipient($recipient_id){

            $aPns = self::getByField('recipient_id', $recipient_id)->fetchAllAssoc();

            self::fillUserData($aPns);


            return $aPns;
        }


        /**
         * @param      $data
         * @param bool $bSingle
         */
        private static function fillUserData(&$data, $bSingle = false){
            $aUsers = array();
            if($bSingle === false) {
                if (!empty($data)) {

                    foreach ($data as $key => $pn) {
                        if (!isset($aUsers[$pn['sender_id']])) {
                            $aUsers[$pn['sender_id']] = self::getMemberById($pn['sender_id']);
                        }

                        $data[$key]['sender'] = $aUsers[$pn['sender_id']];
                    }
                }
            }else{
                $data['sender'] = self::getMemberById($data['sender_id']);
            }
        }

        /**
         * @param $sender_id
         * @return array
         */
        public static function getBySender($sender_id){
            $aPns = self::getByField('sender_id', $sender_id)->fetchAllAssoc();
            self::fillUserData($aPns);
            return $aPns;
        }


        /**
         * @param $status
         * @return array
         */
        public static function getByStatus($status){
            $aPns = self::getByField('status', $status)->fetchAllAssoc();
            self::fillUserData($aPns);
            return $aPns;
        }


        /**
         * @param $attributes
         * @return C4gForumPn
         */
        public static function create($attributes){
            $oPn = new C4gForumPn();
            foreach($attributes as $key => $value){
                if(property_exists($oPn,$key)){
                    $oPn->{"set".ucfirst($key)}($value);
                }
            }
            return $oPn;
        }


        /**
         * @param $id
         * @param $field
         * @return array|false
         */
        public static function getField($id, $field)
        {
            return \Database::getInstance()->prepare('SELECT ? FROM ' . self::$sTable . " WHERE id= ?;")->execute($id, $field)->fetchAssoc();
        }


        /**
         * @param $user_id
         * @param $field
         * @param $value
         * @return int
         */
        public static function countBy($user_id, $field, $value){
            $operator = "=";
            if($value === true){
                $value = "''";
                $operator = "!=";
            }

            $aResult = \Database::getInstance()->prepare('SELECT COUNT(id) as cnt FROM ' . self::$sTable . " WHERE recipient_id = ? AND ".$field." ".$operator." ?;")->execute($user_id, $value)->fetchAssoc();
            if(!isset($aResult['cnt'])){
                $aResult['cnt'] = 0;
            }
            return intval($aResult['cnt']);
        }


        /**
         * @param $sUrl
         */
        public function send($sUrl){
            $this->_save(false);
            $this->notifyRecipient($sUrl);
        }


        /**
         * @return bool
         */
        private function validate(){
            $bResult = true;

            if(empty($this->getMessage())){
                $bResult = false;
            }

            if(empty($this->getRecipientId())){
                $bResult = false;
            }

            if(empty($this->getSubject())){
                $bResult = false;
            }

            if(empty($this->getSenderId())){
                $bResult = false;
            }

            return $bResult;
        }


        /**
         * @param bool $update
         * @throws \Exception
         */
        private function _save($update = false){
            if($this->validate()) {
                if ($update === false) {
                    $sSql = "INSERT INTO " . self::$sTable . " (recipient_id, sender_id, subject, message, status, dt_created) VALUES (?,?,?,?,?,?);";
                    \Database::getInstance()->prepare($sSql)->execute($this->getRecipientId(), $this->getSenderId(), $this->getSubject(), $this->getMessage(), $this->getStatus(), $this->getDtCreated());
                } else {
                    $sSql = "UPDATE " . self::$sTable . " SET recipient_id = ?, sender_id = ?, subject = ?, message = ?, status = ?, dt_created = ? WHERE id = ?;";
                    \Database::getInstance()->prepare($sSql)->execute($this->getRecipientId(), $this->getSenderId(), $this->getSubject(), $this->getMessage(), $this->getStatus(), $this->getDtCreated(), $this->getId());
                }
            }else{
                throw new \Exception("validation_error");
            }
        }


        /**
         * @param $id
         * @return array|false
         */
        private static function getMemberById($id){
            return \Database::getInstance()->prepare('SELECT id, firstname, lastname, email, username FROM tl_member WHERE id= ?;')->execute($id)->fetchAssoc();
        }


        /**
         * @param $sUsername
         * @return array|false
         */
        public static function getMemberByUsername($sUsername){

            return \Database::getInstance()->prepare('SELECT id, firstname, lastname, email, username FROM tl_member WHERE username = ?;')->execute($sUsername)->fetchAssoc();
        }


        /**
         * @param string $sUrl
         */
        private function notifyRecipient($sUrl = ""){
            \System::loadLanguageFile('tl_c4g_forum_pn');

            $aRecipient = self::getMemberById($this->getRecipientId());
            $aSender = self::getMemberById($this->getSenderId());

            $data['charset'] = 'UTF-8';

            $eMail = new Email();
            if ($GLOBALS ['TL_CONFIG'] ['useSMTP'] and (filter_var($GLOBALS ['TL_CONFIG'] ['smtpUser'], FILTER_VALIDATE_EMAIL))) {
                $eMail->from = $GLOBALS ['TL_CONFIG'] ['smtpUser'];
            } else {
                $eMail->from = $GLOBALS ['TL_CONFIG'] ['adminEmail'];
            }

            $eMail->fromName = $aSender['username'];
            $eMail->subject = $GLOBALS['TL_LANG']['tl_c4g_forum_pn']['notify_subject'];
            $eMail->html = str_replace("##LINK##",$sUrl,$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['notify_text']);
            $eMail->sendTo($aRecipient['email']);
            unset($eMail);
        }


        /**
         *
         */
        public function update(){
            $this->_save(true);
        }


        /**
         *
         */
        public function delete(){
            try {
                \Database::getInstance()->prepare('DELETE FROM ' . self::$sTable . " WHERE id = ? LIMIT 1;")->execute($this->getId());
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }