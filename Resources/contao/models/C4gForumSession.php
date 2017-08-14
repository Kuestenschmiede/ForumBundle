<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 5
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
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
    protected static $sTable = 'tl_session';


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
        $oTimeStamp = $oDatabase->prepare("SELECT tstamp FROM $t WHERE pid = ? AND tstamp > ?")->execute($iMemberId, $iTimeThreshold);

        // If member present in the session table and last activity (timestamp) is within now and the given time-threshold, the user is online.
        if ($oTimeStamp->numRows > 0) {
            return true;
        }

        return false;
    }

}