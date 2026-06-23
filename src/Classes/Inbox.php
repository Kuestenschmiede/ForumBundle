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
namespace con4gis\ForumBundle\Classes;

use con4gis\ForumBundle\Models\C4gForumPn;
use Contao\FrontendUser;

/**
 * Class Inbox
 * @package con4gis\ForumBundle\Classes
 */
class Inbox
{
    protected static $sTemplate = 'modal_inbox';

    public static function parse()
    {
        $oUser = \Contao\FrontendUser::getInstance();
        $aPns = C4gForumPn::getByRecipient($oUser->id);

        $oTemplate = new \FrontendTemplate(self::$sTemplate);
        $oTemplate->pns = $aPns;

        return $oTemplate->parse();
    }
}
