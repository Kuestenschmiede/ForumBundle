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

use con4gis\CoreBundle\Classes\C4GUtils;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\System;
use Throwable;

class BackendCallback
{
    public function getUserStatisticOptions(DataContainer $dc): array
    {
        \Contao\System::loadLanguageFile('tl_member');
        (new \Contao\DcaLoader('tl_member'))->load();
        $database = \Contao\Database::getInstance();
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

            $dcaField = $GLOBALS['TL_DCA']['tl_member']['fields'][$field['name']] ?? null;
            if (!is_array($dcaField) || ($dcaField['inputType'] ?? '') !== 'text') {
                continue;
            }

            try {
                if (is_string($dcaField['sql'] ?? null)) {
                    if (\con4gis\CoreBundle\Classes\C4GUtils::stringContainsAny($dcaField['sql'], ['blob', 'binary', 'text'])) {
                        continue;
                    }
                } elseif (is_array($dcaField['sql'] ?? null) && is_string($dcaField['sql']['type'] ?? null)) {
                    switch ($dcaField['sql']['type']) {
                        case 'blob':
                        case 'binary':
                        case 'text':
                            continue 2;
                    }
                } else {
                    continue;
                }
            } catch (Throwable $throwable) {
                continue;
            }
            if (($dcaField['eval']['datepicker'] ?? false) === true) {
                continue;
            }
            if (($dcaField['eval']['allowHtml'] ?? false) === true || ($dcaField['eval']['preserveTags'] ?? false) === true) {
                continue;
            }

            $translation = ($GLOBALS['TL_DCA']['tl_module']['fields'][$field['name']]['label'][0] ?? null) ?:
                ($GLOBALS['TL_LANG']['tl_member'][$field['name']][0] ?? null) ?: '';
            if (is_string($translation) && $translation !== '') {
                $options[$field['name']] = $translation;
            }
        }

        return $options;
    }
}
