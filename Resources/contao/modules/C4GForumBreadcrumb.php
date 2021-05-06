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

namespace con4gis\ForumBundle\Resources\contao\modules;

use con4gis\ProjectsBundle\Classes\jQuery\C4GJQueryGUI;
use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\ForumBundle\Classes\C4GForumHelper;
use Contao\Database;


/**
     * Class C4GForumBreadcrumb
     * @package con4gis\ForumBundle\Resources\contao\modules
     */
    class C4GForumBreadcrumb extends \Module
    {

        /**
         * Template
         *
         * @var string
         */
        protected $strTemplate = 'mod_c4g_forum_breadcrumb';

        /**
         * @var null
         */
        protected $forumModule = null;


        protected $c4g_forum_language_temp = '';

        /**
         * Display a wildcard in the back end
         *
         * @return string
         */
        public function generate()
        {

            if (TL_MODE == 'BE') {
                $objTemplate = new \BackendTemplate('be_wildcard');

                $objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['c4g_forum_breadcrumb'][0] . ' ###';
                $objTemplate->title    = $this->headline;
                $objTemplate->id       = $this->id;
                $objTemplate->link     = $this->title;
                $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

                return $objTemplate->parse();
            }

            return parent::generate();
        }


        /**
         *
         */
        protected function compile()
        {
            if (trim($this->c4g_forum_language) == '') {

                //language get param or request_uri for language switcher sites
                $getLang  = \Input::get('language');
                if ($getLang) {
                    $this->c4g_forum_language_temp = $getLang;
                } else if ($_SERVER["REQUEST_URI"]) {
                    $uri = str_replace('.html','',substr($_SERVER['REQUEST_URI'],1));
                    $uri = explode('/',$uri);
                    if ($uri && $uri[0] && strlen($uri[0]) == 2) {
                        $this->c4g_forum_language_temp = $uri[0];
                    }
                }

                if ($this->c4g_forum_language_temp == '') {
                    /** @var \PageModel $objPage */
                    global $objPage;

                    //three other ways to get current language
                    $pageLang = \Controller::replaceInsertTags('{{page::language}}');
                    if ($pageLang) {
                        $this->c4g_forum_language_temp = $pageLang;
                    } else if ($objPage && $objPage->language) {
                        $this->c4g_forum_language_temp = $objPage->language;
                    } else if ($GLOBALS['TL_LANGUAGE']) {
                        $this->c4g_forum_language_temp = $GLOBALS['TL_LANGUAGE'];
                    }
                }
            } else {
                $this->c4g_forum_language_temp = $this->c4g_forum_language;
            }

            $data = array();
            $this->loadLanguageFile('frontendModules', $this->c4g_forum_language_temp);

            if (!$_GET['c4g_forum_fmd']) {
                // try to get parameters from referer, if they don't exist
                $session = $this->Session->getData();
                list($urlpart, $qspart) = array_pad(explode('?', $session['referer']['current'], 2), 2, '');
                parse_str($qspart, $qsvars);
                if ($qsvars['c4g_forum_fmd']) {
                    $_GET['c4g_forum_fmd'] = $qsvars['c4g_forum_fmd'];
                }
                if ((!$_GET['c4g_forum_forum']) && ($qsvars['c4g_forum_forum'])) {
                    $_GET['c4g_forum_forum'] = $qsvars['c4g_forum_forum'];
                }

            }
            $this->forumModule = $this->Database->prepare("SELECT * FROM tl_module WHERE id=?")
                ->limit(1)
                ->execute($_GET['c4g_forum_fmd']);

            if ($this->forumModule->numRows) {

                // initialize used Javascript Libraries and CSS files
                C4GJQueryGUI::initializeLibraries(
                    true,
                    ($this->forumModule->c4g_forum_jquery_lib == true),
                    ($this->forumModule->c4g_forum_jqui_lib == true),
                    ($this->forumModule->c4g_forum_navigation == 'TREE'),
                    ($this->forumModule->c4g_forum_jqtable_lib == true),
                    ($this->forumModule->c4g_forum_jqhistory_lib == true),
                    ($this->forumModule->c4g_forum_jqtooltip_lib == true),
                    ($this->forumModule->false),
                    ($this->forumModule->false),
                    ($this->forumModule->false),
                    ($this->forumModule->c4g_forum_bbcodes == true),
                    ($this->forumModule->c4g_forum_jqscrollpane_lib == true));


                $data['id']             = $this->id;
                $data['div']            = 'c4g_forum_navigation';
                $data['initData']       = $this->getInitData();
                $data['jquiBreadcrumb'] = $this->forumModule->c4g_forum_breadcrumb_jqui_layout;
                if (!$this->forumModule->c4g_forum_breadcrumb_jqui_layout) {
                    $data['breadcrumbDelim'] = '>';
                }

                //Override JQuery UI Default Theme CSS if defined
                if ($this->forumModule->c4g_forum_uitheme_css_src) {
                    $objFile = \FilesModel::findByUuid($this->forumModule->c4g_forum_uitheme_css_src);
                    if (!empty($objFile)) {
                        $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
                    }
                } else if(!empty($this->forumModule->c4g_forum_uitheme_css_select) && ($this->forumModule->c4g_forum_uitheme_css_select != 'settings')) {
                    $theme = $this->forumModule->c4g_forum_uitheme_css_select;
                    $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
                } else {
                    $settings = Database::getInstance()->execute("SELECT * FROM tl_c4g_settings LIMIT 1")->fetchAllAssoc();

                    if ($settings) {
                        $settings = $settings[0];
                    }
                    if ($settings && $settings['c4g_appearance_themeroller_css']) {
                        $objFile = \FilesModel::findByUuid($this->settings['c4g_appearance_themeroller_css']);
                        $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
                    } else if ($settings && $settings['c4g_uitheme_css_select']) {
                        $theme = $settings['c4g_uitheme_css_select'];
                        $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
                    } else {
                        $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/base/jquery-ui.css';
                    }
                }

                $GLOBALS ['TL_CSS'][] = 'bundles/con4gisforum/css/c4gForum.css';

            }

            $this->Template->c4gdata = $data;

        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function getBreadcrumb($forumId)
        {

            $url      = false;
            $headline = deserialize($this->forumModule->headline);
            $helper   = new C4GForumHelper($this->Database, null, null, $headline['value']);
            $path     = $helper->getForumPath($forumId, $this->forumModule->c4g_forum_startforum);

            // redirect to defined page
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
                ->limit(1)
                ->execute($this->c4g_forum_breadcrumb_jumpTo);

            if ($objPage->numRows) {
                $url = $this->generateFrontendUrl($objPage->fetchAssoc());
            }

            $data = array();
            if ($url) {
                $i = 0;
                foreach ($path as $value) {
                    if (($value['use_intropage']) && (!$this->c4g_forum_hide_intropages)) {
                        $action = 'forumintro';
                    } else {
                        if ($value['subforums'] == 0) {
                            $action = $this->forumModule->c4g_forum_param_forum;
                        } else {
                            $action = $this->forumModule->c4g_forum_param_forumbox;
                        }
                    }

                    $pathname = $value['name'];
                    $names = unserialize($value['optional_names']);
                    if ($names) {
                        foreach ($names as $name) {
                            if ($name['optional_language'] == $this->c4g_forum_language_temp) {
                                $pathname = $name['optional_name'];
                                break;
                            }
                        }
                    }

                    if (++$i === count($path)) {
                        // last button without functionality (id is empty)
                        $data[] = array(
                            "id"   => '',
                            "text" => $pathname
                        );

                    } else {
                        $data[] = array(
                            "url"  => C4GUtils::addParametersToURL($url, array('state' => $action . ':' . $value['id'])),
                            "text" => $pathname
                        );
                    }
                }
            }

            return $data;
        }


        /**
         * @return string
         */
        protected function getInitData()
        {

            return json_encode(array(
                                   "breadcrumb" => $this->getBreadcrumb($_GET['c4g_forum_forum']),
                               ));
        }
    }

?>
