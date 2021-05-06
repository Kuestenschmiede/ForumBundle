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