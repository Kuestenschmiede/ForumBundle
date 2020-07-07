<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
namespace con4gis\ForumBundle\Resources\contao\models;

use Contao\Model;

/**
 * Class C4gForumPost
 * @package con4gis\ForumBundle\Resources\contao\models
 */
class C4gForumPost extends Model
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