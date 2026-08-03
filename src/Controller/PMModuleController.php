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

namespace con4gis\ForumBundle\Controller;

use con4gis\ForumBundle\Models\C4gForumPn;
use con4gis\ProjectsBundle\Classes\jQuery\C4GJQueryGUI;
use Contao\Module;
use Contao\System;

class PMModuleController extends Module
{
    protected $strTemplate = 'mod_c4g_forum_pncenter';

    public function generate()
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest())) {
            $objTemplate = new \Contao\BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['c4g_forum_pncenter'][0] . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        $requestStack = System::getContainer()->get('request_stack');
        $request = $requestStack->getCurrentRequest();
        
        if (!method_exists($requestStack, 'getSession')) {
            $session = System::getContainer()->get('session');
        } else {
            $session = $requestStack->getSession();
        }

        $session->set('pm-forum-module', $this->pm_center_forum_module);
        $this->Template->c4g_forum_module = $this->pm_center_forum_module;

        System::loadLanguageFile("tl_c4g_forum_pn");
        $aUser = \Contao\FrontendUser::getInstance()->getData();
        $iCountAll = C4gForumPn::countBy($aUser['id'],"status" , true);
        $iCountUnread = C4gForumPn::countBy($aUser['id'],"status" , 0);

        $this->Template->count_all = $iCountAll;
        $this->Template->count_unread = $iCountUnread;
        $sJsLang = $this::getClientLangVars();

        $this->Template->c4g_pn_js = $sJsLang;
        $data = [];

        global $objPage;
        // set global js var to inidcate api endpoint
        $GLOBALS['TL_HEAD'][] = "<script>var pnApiBaseUrl = 'con4gis/forumPnService/".
            ($objPage->language ?: "de")
            ."';</script>";

        if (!array_key_exists('c4g_forum_fmd', $_GET) || !$_GET['c4g_forum_fmd']) {
            // try to get parameters from referer, if they don't exist
            $sessionData = $request->getSession()->all();

            if (is_array($sessionData['referer']) && array_key_exists('current', $sessionData['referer'])) {
                list($urlpart, $qspart) = array_pad(explode('?', $sessionData['referer']['current'], 2), 2, '');
                parse_str($qspart, $qsvars);
                if ($qsvars['c4g_forum_fmd']) {
                    $_GET['c4g_forum_fmd'] = $qsvars['c4g_forum_fmd'];
                }
                if ((!$_GET['c4g_forum_forum']) && ($qsvars['c4g_forum_forum'])) {
                    $_GET['c4g_forum_forum'] = $qsvars['c4g_forum_forum'];
                }
            }
        }

        $database = \Contao\Database::getInstance();
        $forumModule = null;
        if (array_key_exists('c4g_forum_fmd', $_GET)) {
            $forumModule = $database->prepare("SELECT * FROM tl_module WHERE id=?")
                ->limit(1)
                ->execute(...[$_GET['c4g_forum_fmd']]);
        }

        C4GJQueryGUI::initializeLibraries(
            true,
            true,
            true,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            true
            );

        $data['id']             = $this->id;
        $data['div']            = 'mod_c4g_forum_pncenter';
        $data['initData']       = json_encode([]);

        if ($this->c4g_appearance_themeroller_css) {
            $objFile = \FilesModel::findByUuid($this->c4g_appearance_themeroller_css);
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
        } else if(!empty($this->c4g_forum_uitheme_css_select) && ($this->c4g_forum_uitheme_css_select != 'settings')) {
            $theme = $this->c4g_forum_uitheme_css_select;
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
        } else if ($forumModule && $forumModule->c4g_forum_uitheme_css_src) {
            $objFile = \FilesModel::findByUuid($forumModule->c4g_forum_uitheme_css_src);
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
        } else if($forumModule && !empty($forumModule->c4g_forum_uitheme_css_select) && ($forumModule->c4g_forum_uitheme_css_select != 'settings')) {
            $theme = $forumModule->c4g_forum_uitheme_css_select;
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
        } else {
            $settings = \Contao\Database::getInstance()->execute("SELECT * FROM tl_c4g_settings LIMIT 1")->fetchAllAssoc();

            if ($settings) {
                $settings = $settings[0];
            }
            if ($settings && $settings['c4g_appearance_themeroller_css']) {
                $objFile = \FilesModel::findByUuid($settings['c4g_appearance_themeroller_css']);
                $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
            } else if ($settings && $settings['c4g_uitheme_css_select']) {
                $theme = $settings['c4g_uitheme_css_select'];
                $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
            } else {
                $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/base/jquery-ui.css';
            }
        }

        $this->Template->c4gData = $data;
    }

    /**
     * @return string
     */
    public static function getClientLangVars() {
        $currentLang = \Contao\Input::get('language');

        if (empty($currentLang)) {
            $currentLang = $GLOBALS['TL_LANGUAGE'];
        }

        $GLOBALS['TL_LANGUAGE'] = $currentLang;
        \Contao\System::loadLanguageFile('tl_c4g_forum_pn');
        return '<script>
            var C4GLANG = {
                send_error: "'.$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['send_error'].'",
                send: "'.$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['send'].'",
                delete: "'.$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['delete'].'",
                close: "'.$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['close'].'",
                reply: "'.$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['reply'].'",
                delete_confirm: "'.$GLOBALS['TL_LANG']['tl_c4g_forum_pn']['delete_confirm'].'"
             };
            
            var sCurrentLang = "'.$GLOBALS['TL_LANGUAGE'].'";
            
        </script>';

    }

}
