<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

    namespace con4gis\ForumBundle\Resources\contao\classes;


    use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
    use Contao\FrontendUser;
    use Contao\User;

    /**
     * Class Inbox
     * @package con4gis\ForumBundle\Resources\contao\classes
     */
    class Inbox
    {

        protected static $sTemplate = "modal_inbox";


        public static function parse(){

            $oUser = FrontendUser::getInstance();
            $aPns = C4gForumPn::getByRecipient($oUser->id);

            $oTemplate = new \FrontendTemplate(self::$sTemplate);
            $oTemplate->pns = $aPns;

            return $oTemplate->parse();
        }


    }