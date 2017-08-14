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

namespace con4gis\ForumBundle\Resources\contao\classes;


    use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
    use Contao\FrontendUser;
    use Contao\User;

    /**
     * Class View
     * @package con4gis\ForumBundle\Resources\contao\classes
     */
    class View
    {

        protected static $sTemplate = "modal_view_message";


        public static function parse(){

            $aData = \Input::get("data");
            $oUser = FrontendUser::getInstance();
            $oPn = C4gForumPn::getById($aData['id']);

            $oTemplate = new \FrontendTemplate(self::$sTemplate);
            $oTemplate->pn = $oPn;

            return $oTemplate->parse();
        }


    }