<?php
/**
 * Created by PhpStorm.
 * User: rro
 * Date: 15.11.18
 * Time: 14:59
 */

namespace con4gis\ForumBundle\Resources\contao\models;


use Contao\Database;

class C4GThreadSubscriptionModel extends \Contao\Model
{
    protected static $strTable = 'tl_c4g_forum_thread_subscription';

    public function findByThreadAndMember($threadId, $memberId) {
        return new self(
            Database::getInstance()->prepare(
                "SELECT * FROM ".
                self::$strTable.
                " WHERE pid = ? AND member = ?")
                ->execute($threadId, $memberId)
        );
    }
}