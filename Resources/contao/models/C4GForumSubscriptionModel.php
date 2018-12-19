<?php
/**
 * Created by PhpStorm.
 * User: rro
 * Date: 15.11.18
 * Time: 14:59
 */

namespace con4gis\ForumBundle\Resources\contao\models;


use Contao\Database;

class C4GForumSubscriptionModel extends \Contao\Model
{
    protected static $strTable = 'tl_c4g_forum_subforum_subscription';

    public function findByForumAndMember($forumId, $memberId) {
        return new self(
            Database::getInstance()->prepare(
                "SELECT * FROM ".
                self::$strTable.
                " WHERE pid = ? AND member = ?")
                ->execute($forumId, $memberId)
        );
    }
}