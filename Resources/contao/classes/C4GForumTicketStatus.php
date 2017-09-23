<?php
/**
 * Created by PhpStorm.
 * User: fsc
 * Date: 08.09.17
 * Time: 11:10
 */

namespace con4gis\ForumBundle\Resources\contao\classes;


class C4GForumTicketStatus
{
    public static function getState($stateId){
        $return = $GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][$stateId];
        return $return;
    }

}