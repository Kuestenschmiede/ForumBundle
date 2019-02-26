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

namespace con4gis\ForumBundle\Resources\contao\classes;


class C4GForumTicketStatus
{
    public static function getState($stateId){
        $return = $GLOBALS['TL_LANG']['C4G_FORUM']['TICKET']['STATE'][$stateId];
        return $return;
    }

}