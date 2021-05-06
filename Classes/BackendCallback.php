<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Classes;

use con4gis\CoreBundle\Classes\C4GUtils;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\System;

class BackendCallback
{
    public function getUserStatisticOptions(DataContainer $dc): array
    {
        System::loadLanguageFile('tl_member');
        Controller::loadDataContainer('tl_member');
        $database = Database::getInstance();
        $fields = $database->listFields('tl_member');
        $options = [];
        foreach ($fields as $field) {
            switch ($field['name']) {
                case 'id':
                case 'tstamp':
                case 'password':
                case 'secret':
                case 'PRIMARY':
                case 'memberImage':
                case 'memberSignature':
                case 'memberHomepageLink':
                case 'memberFacebookLink':
                case 'memberTwitterLink':
                case 'memberGooglePlusLink':
                case 'session':
                case 'locked':
                case 'firstname':
                case 'lastname':
                case 'username':
                case 'useTwoFactor':
                    continue 2;
            }

            $dcaField = $GLOBALS['TL_DCA']['tl_member']['fields'][$field['name']];
            if (!is_array($dcaField) || $dcaField['inputType'] !== 'text') {
                continue;
            }
            if (C4GUtils::stringContainsAny($dcaField['sql'], ['blob', 'binary', 'text'])) {
                continue;
            }
            if ($dcaField['eval']['datepicker'] === true) {
                continue;
            }
            if ($dcaField['eval']['allowHtml'] === true || $dcaField['eval']['preserveTags'] === true) {
                continue;
            }

            $translation = $GLOBALS['TL_DCA']['tl_module']['fields'][$field['name']]['label'][0] ?:
                $GLOBALS['TL_LANG']['tl_member'][$field['name']][0] ?: '';
            if (is_string($translation) && $translation !== '') {
                $options[$field['name']] = $translation;
            }
        }

        return $options;
    }
}
