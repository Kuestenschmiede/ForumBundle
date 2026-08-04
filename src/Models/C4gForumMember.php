<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2026, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\ForumBundle\Models;

use Contao\Database;
use Contao\Model;

/**
 * Class C4gForumMember
 * @package con4gis\ForumBundle\Models;
 */
class C4gForumMember extends Model
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
        if (!$iMemberId) {
            return '';
        }
        $t = static::$sTable;
        $oDatabase = \Contao\Database::getInstance();
        $sQuery = "SELECT memberImage";
        if ($oDatabase->fieldExists('avatar', $t)) {
            $sQuery .= ", avatar";
        }
        $res = $oDatabase->prepare("$sQuery FROM $t WHERE id=?")->execute($iMemberId);
        if ($res->numRows < 1) {
            return '';
        }
        $sMemberImagePath = $res->memberImage;
        if (!$sMemberImagePath && isset($res->avatar)) {
            $sMemberImagePath = $res->avatar;
        }
        if (!$sMemberImagePath) {
            return '';
        }

        $val = \Contao\StringUtil::deserialize($sMemberImagePath);
        if (is_array($val)) {
            $val = $val[0];
        }

        if ($val !== '' && \strlen($val) === 16) {
            $objFile = \Contao\FilesModel::findByUuid($val);
            if ($objFile) {
                return $objFile->path;
            }

            return \Contao\StringUtil::binToUuid($val);
        }

        return (string) $val;
    }

}