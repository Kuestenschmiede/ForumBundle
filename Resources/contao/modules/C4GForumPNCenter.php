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

    namespace con4gis\ForumBundle\Resources\contao\modules;
    use con4gis\CoreBundle\Resources\contao\classes\C4GJQueryGUI;
    use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
    use Contao\Database;


    /**
     * Class C4GForumPNCenter
     * @package con4gis\ForumBundle\Resources\contao\modules
     */
    class C4GForumPNCenter extends \Module
    {

        protected $strTemplate = "mod_c4g_forum_pncenter";

        /**
         * Display a wildcard in the back end
         *
         * @return string
         */
        public function generate()
        {

            if (TL_MODE == 'BE') {
                $objTemplate = new \BackendTemplate('be_wildcard');

                $objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['c4g_forum_pncenter'][0] . ' ###';
                $objTemplate->title    = $this->headline;
                $objTemplate->id       = $this->id;
                $objTemplate->link     = $this->title;
                $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

                return $objTemplate->parse();
            }

            return parent::generate();
        }

        /**
         * @return string
         */
        public static function getClientLangVars() {
            \System::loadLanguageFile("tl_c4g_forum_pn");

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


        /**
         *
         */
        protected function compile()
        {

            \System::loadLanguageFile("tl_c4g_forum_pn");

            $aUser = \FrontendUser::getInstance()->getData();
            $iCountAll = C4gForumPn::countBy($aUser['id'],"status" , true);
            $iCountUnread = C4gForumPn::countBy($aUser['id'],"status" , 0);

            $this->Template->count_all = $iCountAll;
            $this->Template->count_unread = $iCountUnread;
            $sJsLang = $this->getClientLangVars();

            $this->Template->c4g_pn_js = $sJsLang;
            $data = array();

            // set global js var to inidcate api endpoint
            $GLOBALS['TL_HEAD'][] = "<script>var pnApiBaseUrl = 'con4gis/forumPnService';</script>";
         
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

            // initialize used Javascript Libraries and CSS files
            C4GJQueryGUI::initializeLibraries(
                true,                                                 // add c4gJQuery GUI Core LIB
                true,                                                 // add JQuery
                true,                                                // add JQuery UI
                false,                                                // add Tree Control
                false,                                                // add Table Control
                false,                                                // add history.js
                false,                                                // add simple tooltip
                false,                                                // add C4GMaps
                false,                                                // add C4GMaps - GoogleMaps
                false,                                                // add C4GMaps - MapsEditor
                true,                                                 // add WYSIWYG editor
                false);                                               // add jScrollPane

            $data['id']             = $this->id;
            $data['div']            = 'mod_c4g_forum_pncenter';
            $data['initData']       = $this->getInitData();

            if ($this->c4g_appearance_themeroller_css) {
                $objFile = \FilesModel::findByUuid($this->c4g_appearance_themeroller_css);
                $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
            } else if(!empty($this->c4g_forum_uitheme_css_select) && ($this->c4g_forum_uitheme_css_select != 'settings')) {
                $theme = $this->c4g_forum_uitheme_css_select;
                $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscoreassets/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
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
                    $objFile = \FilesModel::findByUuid($this->settings['c4g_appearance_themeroller_css']);
                    $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
                } else if ($settings && $settings['c4g_uitheme_css_select']) {
                    $theme = $settings['c4g_uitheme_css_select'];
                    $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
                } else {
                    $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/base/jquery-ui.css';
                }
            }

            $this->Template->c4gdata = $data;

        }


        /**
         * @return string
         */
        protected function getInitData()
        {

            return json_encode(array(

            ));
        }


    }