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
 * Class C4gForumPost
 * @package con4gis\ForumBundle\Resources\contao\models
 */
class C4gForumPost extends \Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected static $sTable = 'tl_c4g_forum_post';


    /**
     * Get posts count by member id.
     *
     * @param $iMemberId
     * @return mixed|null
     */
    public static function getMemberPostsCountById($iMemberId)
    {
        $t = static::$sTable;
        $oDatabase = \Database::getInstance();
        $oResult = $oDatabase->prepare("SELECT id FROM $t WHERE author=?")->execute($iMemberId);

        return $oResult->numRows;
    }

}