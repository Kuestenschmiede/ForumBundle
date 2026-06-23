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
     * Class View
     * @package con4gis\ForumBundle\Classes
     */
    class View
    {
        protected static $sTemplate = 'modal_view_message';

        public static function parse()
        {
            $aData = \Contao\Input::get('data');
            $oUser = \Contao\FrontendUser::getInstance();
            $oPn = C4gForumPn::getById($aData['id']);

            $oTemplate = new \FrontendTemplate(self::$sTemplate);
            $oPn->setMessage($oPn->getMessage());
            $oTemplate->pn = $oPn;

            return $oTemplate->parse();
        }
    }
