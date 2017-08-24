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

/**
 * Class C4gForumMember
 * @package con4gis\ForumBundle\Resources\contao\models;
 */
class C4gForumMember extends \Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected static $sTable = 'tl_member';


    /**
     * Return an avatar by member id.
     *
     * @param $iMemberId
     * @return mixed
     */
    public static function getAvatarByMemberId($iMemberId)
    {
        $t = static::$sTable;
        $oDatabase = \Database::getInstance();
        $aMemberImage = $oDatabase->prepare("SELECT memberImage FROM $t WHERE id=?")->execute($iMemberId)->fetchAssoc();
        $sMemberImagePath = $aMemberImage['memberImage'];

        return $sMemberImagePath;
    }

}