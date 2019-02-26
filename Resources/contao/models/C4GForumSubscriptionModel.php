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