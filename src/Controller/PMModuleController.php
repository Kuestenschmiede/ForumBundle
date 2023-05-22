<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Controller;

use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
use con4gis\ProjectsBundle\Classes\jQuery\C4GJQueryGUI;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\Database;
use Contao\ModuleModel;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class PMModuleController extends AbstractFrontendModuleController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        if (!$this->requestStack || !method_exists($this->requestStack, 'getSession')) {
            $session = System::getContainer()->get('session');
        } else {
            $session = $this->requestStack->getSession();
        }

        $session->set('pm-forum-module', $model->pm_center_forum_module);
        $template->c4g_forum_module = $model->pm_center_forum_module;

        System::loadLanguageFile("tl_c4g_forum_pn");
        $aUser = \FrontendUser::getInstance()->getData();
        $iCountAll = C4gForumPn::countBy($aUser['id'],"status" , true);
        $iCountUnread = C4gForumPn::countBy($aUser['id'],"status" , 0);

        $template->count_all = $iCountAll;
        $template->count_unread = $iCountUnread;
        $sJsLang = $this::getClientLangVars();

        $template->c4g_pn_js = $sJsLang;
        $data = [];

        global $objPage;
        // set global js var to inidcate api endpoint
        $GLOBALS['TL_HEAD'][] = "<script>var pnApiBaseUrl = 'con4gis/forumPnService/".
            ($objPage->language ?: "de")
            ."';</script>";

        if (!array_key_exists('c4g_forum_fmd', $_GET) || !$_GET['c4g_forum_fmd']) {
            // try to get parameters from referer, if they don't exist
            $session = $request->getSession()->all();

            if (is_array($session['referer']) && array_key_exists('current', $session['referer'])) {
                list($urlpart, $qspart) = array_pad(explode('?', $session['referer']['current'], 2), 2, '');
                parse_str($qspart, $qsvars);
                if ($qsvars['c4g_forum_fmd']) {
                    $_GET['c4g_forum_fmd'] = $qsvars['c4g_forum_fmd'];
                }
                if ((!$_GET['c4g_forum_forum']) && ($qsvars['c4g_forum_forum'])) {
                    $_GET['c4g_forum_forum'] = $qsvars['c4g_forum_forum'];
                }
            }
        }

        $database = Database::getInstance();
        if (array_key_exists('c4g_forum_fmd', $_GET)) {
            $this->forumModule = $database->prepare("SELECT * FROM tl_module WHERE id=?")
                ->limit(1)
                ->execute($_GET['c4g_forum_fmd']);
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

        $data['id']             = $model->id;
        $data['div']            = 'mod_c4g_forum_pncenter';
        $data['initData']       = json_encode([]);

        if ($model->c4g_appearance_themeroller_css) {
            $objFile = \FilesModel::findByUuid($model->c4g_appearance_themeroller_css);
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
        } else if(!empty($this->c4g_forum_uitheme_css_select) && ($this->c4g_forum_uitheme_css_select != 'settings')) {
            $theme = $this->c4g_forum_uitheme_css_select;
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
        } else if ($this->forumModule && $this->forumModule->c4g_forum_uitheme_css_src) {
            $objFile = \FilesModel::findByUuid($this->forumModule->c4g_forum_uitheme_css_src);
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
        } else if($this->forumModule && !empty($this->forumModule->c4g_forum_uitheme_css_select) && ($this->forumModule->c4g_forum_uitheme_css_select != 'settings')) {
            $theme = $this->forumModule->c4g_forum_uitheme_css_select;
            $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
        } else {
            $settings = Database::getInstance()->execute("SELECT * FROM tl_c4g_settings LIMIT 1")->fetchAllAssoc();

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

        $template->c4gData = $data;

        return $template->getResponse();
    }

    /**
     * @return string
     */
    public static function getClientLangVars() {
        $currentLang = \Input::get('language');

        if (empty($currentLang)) {
            $currentLang = $GLOBALS['TL_LANGUAGE'];
        }

        $GLOBALS['TL_LANGUAGE'] = $currentLang;
        \System::loadLanguageFile('tl_c4g_forum_pn');
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