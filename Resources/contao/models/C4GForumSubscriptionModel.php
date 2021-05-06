<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Resources\contao\models;


use Contao\Database;

class C4GForumSubscriptionModel extends \Contao\Model
{
    protected static $strTable = 'tl_c4g_forum_subforum_subscription';

    /**
     * @param $forumId
     * @param $memberId
     * @return mixed
     */
    public static function findByForumAndMember($forumId, $memberId)
    {
        $arrColumns = [
            self::$strTable . '.pid=?',
            self::$strTable . '.member=?'
        ];
        $arrValues = [
            $forumId,
            $memberId
        ];

        return static::findOneBy($arrColumns, $arrValues);
    }

}