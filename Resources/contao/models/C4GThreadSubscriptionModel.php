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

class C4GThreadSubscriptionModel extends \Contao\Model
{
    protected static $strTable = 'tl_c4g_forum_thread_subscription';

    /**
     * @param $threadId
     * @param $memberId
     * @return mixed
     */
    public function findByThreadAndMember($threadId, $memberId)
    {
        $arrColumns = array(
            self::$strTable . '.pid=?',
            self::$strTable . '.member=?'
        );
        $arrValues = array(
            $threadId,
            $memberId
        );

        return static::findOneBy($arrColumns, $arrValues);
    }
}