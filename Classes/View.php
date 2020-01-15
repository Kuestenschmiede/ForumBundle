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
namespace con4gis\ForumBundle\Classes;

    use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
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
            $aData = \Input::get('data');
            $oUser = FrontendUser::getInstance();
            $oPn = C4gForumPn::getById($aData['id']);

            $oTemplate = new \FrontendTemplate(self::$sTemplate);
            $oPn->setMessage($oPn->getMessage());
            $oTemplate->pn = $oPn;

            return $oTemplate->parse();
        }
    }
