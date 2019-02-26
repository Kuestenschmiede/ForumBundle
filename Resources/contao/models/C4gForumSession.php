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

/**
 * Class C4gForumSession
 * @package con4gis\ForumBundle\Resources\contao\models
 */
class C4gForumSession extends \Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected static $sTable = 'tl_member';


    /**
     * @param $iMemberId
     * @param int $iThreshold
     * @return bool
     */
    public static function getOnlineStatusByMemberId($iMemberId, $iThreshold = 500)
    {
        $t = static::$sTable;
        $iTimeThreshold = time() - $iThreshold;

        $oDatabase = \Database::getInstance();
        $oTimeStamp = $oDatabase->prepare(
            "SELECT tstampLastAction FROM $t WHERE id = ? AND tstampLastAction > ?"
        )->execute($iMemberId, $iTimeThreshold);

        if ($oTimeStamp->numRows > 0) {
            return true;
        }

        return false;
    }

}