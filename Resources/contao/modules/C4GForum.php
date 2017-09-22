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

namespace con4gis\ForumBundle\Resources\contao\modules;

    use con4gis\ForumBundle\Resources\contao\classes\C4GForumTicketStatus;
    use con4gis\MapsBundle\Resources\contao\classes\MapDataConfigurator;
    use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
    use con4gis\MapsBundle\Resources\contao\classes\ResourceLoader;
    use con4gis\GroupsBundle\Resources\contao\models\MemberModel;
    use con4gis\ForumBundle\Resources\contao\classes\C4GForumHelper;
    use con4gis\ForumBundle\Resources\contao\classes\C4GUtils;
    use con4gis\ForumBundle\Resources\contao\models\C4gForumModel;
    use con4gis\ForumBundle\Resources\contao\models\C4gForumPost;
    use con4gis\ForumBundle\Resources\contao\models\C4gForumSession;
    use Contao\FrontendUser;
    use con4gis\CoreBundle\Resources\contao\classes\C4GJQueryGUI;
    use Contao\Input;
    use Contao\Module;

    $GLOBALS['c4gForumErrors']           = array();
    $GLOBALS['c4gForumSearchParamCache'] = array();

    /**
     * to catch warnings etc. and put them into the ajax response separately
     */
    function c4gForumErrorHandler($code, $text, $file, $line)
    {

        if ($code != E_NOTICE) {
            if ($code & error_reporting()) {
                $error['code']               = $code;
                $error['text']               = $text;
                $error['file']               = $file;
                $error['line']               = $line;
                $GLOBALS['c4gForumErrors'][] = $error;
            }
        }
    }

    /**
     * Class C4GForum
     * @package con4gis\ForumBundle\Resources\contao\modules
     */
    class C4GForum extends Module
    {

        /**
         * Template
         *
         * @var string
         */
        protected $strTemplate = 'mod_c4g_forum';

        /**
         * @var bool
         */
        protected $plainhtml = false;

        /**
         * @var string
         */
        protected $action = "";

        /**
         * @var null
         */
        protected $putVars = null;

        /**
         * @var C4GForumHelper
         */
        protected $helper = null;

        /**
         * @var bool
         */
        protected $dialogs_jqui = true;

        static $url = "";

        protected static $useMaps = false;

        protected $c4g_forum_language_temp = '';

        /**
         * C4GForum constructor.
         */
        public function __construct($objModule,$strColumn='main')
        {
            parent::__construct($objModule,$strColumn='main');
            $this->helper = new C4GForumHelper($this->Database, null,FrontendUser::getInstance(),"","","UU",$this->c4g_forum_type);
            $this->User = FrontendUser::getInstance();
        }

        /**
         * Display a wildcard in the back end
         *
         * @return string
         */
        public function generate()
        {

            if (TL_MODE == 'BE') {
                $objTemplate = new \BackendTemplate('be_wildcard');

                $objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['c4g_forum'][0] . ' ###';
                $objTemplate->title    = $this->headline;
                $objTemplate->id       = $this->id;
                $objTemplate->link     = $this->title;
                $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

                return $objTemplate->parse();
            }
            if (array_key_exists('_escaped_fragment_', $_GET)) {
                $this->strTemplate = 'mod_c4g_forum_plainhtml';
                $this->plainhtml   = true;
            }

            return parent::generate();
        }


        /**
         * Generate module
         */
        protected function compile()
        {
            $this->setTempLanguage();

            if (FE_USER_LOGGED_IN) {
                \System::import('FrontendUser', 'User');
            }

            $this->initMembers();

            $enableMaps = false;
            $useGoogleMaps = false;
            if ($this->c4g_forum_enable_maps) {
                $useGoogleMaps = C4GForumHelper::isGoogleMapsUsed($this->Database);
                $enableMaps = true;
            }
            // initialize used Javascript Libraries and CSS files
            C4GJQueryGUI::initializeLibraries(
                true,                                               // add c4gJQuery GUI Core LIB
                ($this->c4g_forum_jquery_lib == true),              // add JQuery
                ($this->c4g_forum_jqui_lib == true),                // add JQuery UI
                ($this->c4g_forum_navigation == 'TREE'),            // add Tree Control
                ($this->c4g_forum_jqtable_lib == true),             // add Table Control
                ($this->c4g_forum_jqhistory_lib == true),           // add history.js
                ($this->c4g_forum_jqtooltip_lib == true),           // add simple tooltip
                ($this->c4g_forum_enable_maps == $enableMaps),      // add C4GMaps
                $useGoogleMaps,                                     // add C4GMaps - include Google Maps Javascript?
                ($this->c4g_forum_enable_maps == $enableMaps),      // add C4GMaps Feature Editor
                ($this->c4g_forum_bbcodes == true),
                ($this->c4g_forum_jqscrollpane_lib == true)         // add jScrollPane
            );

            //Override JQuery UI Default Theme CSS if defined
            if ($this->c4g_forum_uitheme_css_src) {
                $objFile                            = \FilesModel::findByUuid($this->c4g_forum_uitheme_css_src);
                if (!empty($objFile)) {
                    $GLOBALS['TL_CSS']['c4g_jquery_ui'] = $objFile->path;
                }
            } else if(!empty($this->c4g_forum_uitheme_css_select)) {
                    $theme = $this->c4g_forum_uitheme_css_select;
                    $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css';
            } else {
                $GLOBALS['TL_CSS']['c4g_jquery_ui'] = 'bundles/con4giscore/vendor/jQuery/ui-themes/themes/base/jquery-ui.css';
            }



            /**
             * change rating star color
             */
            if(!empty($this->c4g_forum_rating_color)) {
                $GLOBALS ['TL_HEAD'] [] = '<style>.rating_static > span.checked ~ label{color:#' . $this->c4g_forum_rating_color . ' !important;}</style>';
            }

            $GLOBALS ['TL_CSS'] [] = 'bundles/con4gisforum/css/c4gForum.css';
            //$GLOBALS ['TL_CSS'] [] = 'system/modules/con4gis_forum/html/css/bbcodes.css';
            $data['id']      = $this->id;
            // set global js var to inidcate api endpoint
            $data['forumAjaxUrl'] = "con4gis/api/forumService";
            $GLOBALS['TL_HEAD'][] = "<script>var pnApiBaseUrl = 'con4gis/api/forumPnService';</script>";
            $GLOBALS['TL_HEAD'][] = "<script>var uploadApiUrl = 'con4gis/api/fileUpload/';</script>";


            // $data['ajaxData'] = "action=fmd&id=".$this->id."&language=".$GLOBALS['TL_LANGUAGE']."&page=".$objPage->id;
            $data['ajaxData'] = $this->id;

            $size = deserialize($this->c4g_forum_size, true);
            $data['width'] = ($size[0] != 0) ? $size[0] . $size[2] : 'auto';
            $data['height'] = ($size[1] != 0) ? $size[1] . $size[2] : 'auto';

            if ($_GET['state']) {
                $request = $_GET['state'];
            } else {
                $request = 'initnav';
            }
            $data['initData'] = $this->generateAjax($request);

            // save forum url for linkbuilding in ajaxrequests
            $aTmpData = $this->Session->getData();
            if (stristr($aTmpData['referer']['current'], "/CoreBundle/Resources/contao/api/") === false) {
                $aTmpData['current_forum_url'] = $aTmpData['referer']['current'];
                $this->Session->setData($aTmpData);
            } else {
                $aTmpData['referer']['last']    = $aTmpData['current_forum_url'];
                $aTmpData['referer']['current'] = $aTmpData['current_forum_url'];
                $this->Session->setData($aTmpData);
            }


            $data['div'] = 'c4g_forum';
            switch ($this->c4g_forum_navigation) {
                case 'TREE':
                    $data['navPanel'] = true;
                    break;

                case 'BOXES':
                    $data['navPanel'] = false;
                    break;

                default:
                    break;
            }
            $data['jquiBreadcrumb']      = $this->c4g_forum_breadcrumb_jqui_layout;
            $data['jquiButtons']         = $this->c4g_forum_buttons_jqui_layout;
            $data['embedDialogs']        = $this->c4g_forum_dialogs_embedded;
            $data['jquiEmbeddedDialogs'] = $this->dialogs_jqui;
            $data['contaoLanguage']      = $this->c4g_forum_language_temp;

            $binImageUuid = deserialize(unserialize($this->c4g_forum_bbcodes_editor_imguploadpath));
            if ($binImageUuid) {
                $imageUploadPath = \FilesModel::findByUuid(\Contao\StringUtil::binToUuid($binImageUuid[0]));
            }
            \Session::getInstance()->set("con4gisImageUploadPath", $imageUploadPath->path.'/');

            $binFileUuid = deserialize(unserialize($this->c4g_forum_bbcodes_editor_fileuploadpath));
            if ($binFileUuid) {
                $fileUploadPath = \FilesModel::findByUuid(\Contao\StringUtil::binToUuid($binFileUuid[0]));
            }
            \Session::getInstance()->set("con4gisFileUploadPath", $fileUploadPath->path.'/');

            \Session::getInstance()->set("c4g_forum_bbcodes_editor_uploadTypes", $this->c4g_forum_bbcodes_editor_uploadTypes);
            \Session::getInstance()->set("c4g_forum_bbcodes_editor_maxFileSize", $this->c4g_forum_bbcodes_editor_maxFileSize);
            \Session::getInstance()->set("c4g_forum_bbcodes_editor_imageWidth", $this->c4g_forum_bbcodes_editor_imageWidth);
            \Session::getInstance()->set("c4g_forum_bbcodes_editor_imageHeight", $this->c4g_forum_bbcodes_editor_imageHeight);

            $aToolbarButtons = explode(",", $this->c4g_forum_bbcodes_editor_toolbaritems);


            $GLOBALS['TL_CSS'][]        = 'bundles/con4giscore/vendor/jQuery/plugins/chosen/chosen.css';
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/con4giscore/vendor/jQuery/plugins/chosen/chosen.jquery.min.js';

            if($this->c4g_forum_bbcodes != "1") {
                $GLOBALS['TL_HEAD'][] = "<script>var ckRemovePlugins = 'bbcode';</script>";
            }else{
                $GLOBALS['TL_HEAD'][] = "<script>var ckRemovePlugins = '';</script>";
            }

            if ($this->c4g_forum_editor === "ck") {
                $GLOBALS['TL_HEAD'][]       = "<script>var ckEditorItems = ['" . implode("','", $aToolbarButtons) . "'];</script>";
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/con4giscore/vendor/ckeditor/ckeditor.js';
            }

            if($this->c4g_forum_pagination_active == "1") {
                $GLOBALS['TL_JAVASCRIPT'][] = "bundles/con4gisforum/js/jquery.pagination.min.js";
                $GLOBALS['TL_JAVASCRIPT'][] = "bundles/con4gisforum/js/jquery.hashchange.min.js";
            }

            if ($enableMaps) {
                ResourceLoader::loadResources();
                ResourceLoader::loadTheme();
                static::$useMaps = $enableMaps;
                // load core resources for maps
                ResourceLoader::loadResourcesForModule('maps');
            }

            $data['breadcrumbDelim'] = $this->c4g_forum_breadcrumb_jqui_layout ? '' : '>';

            if (($this->action == 'readthread') ||
                ($this->action == $this->c4g_forum_param_forum) ||
                ($this->action == $this->c4g_forum_param_forumbox)
            ) {
                // add this for search engines
                // when search engines find this they are supposed to send a second request
                // with a "_escaped_fragment_" GET parameter
                if (!$this->plainhtml) {
                    $GLOBALS['TL_HEAD'][] = '<meta name="fragment" content="!">';
                }
            }

            $this->Template->c4gdata = $data;
        }


        /**
         *
         * Check Permissions for the current action
         */
        public function checkPermission($forumId)
        {

            return array(
                $this->helper->checkPermissionForAction($forumId, $this->action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum),
                $this->helper->permissionError
            );
        }


        /**
         *
         * Check Permissions for a given action
         */
        public function checkPermissionForAction($forumId, $action)
        {

            return array(
                $this->helper->checkPermissionForAction($forumId, $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum),
                $this->helper->permissionError
            );
        }


        /**
         * @param array $options
         *
         * @return mixed
         */
        public function addDefaultDialogOptions($options)
        {

            $options['show'] = 'fold';
            $options['hide'] = 'fold';
            $size            = deserialize($this->c4g_forum_dialogsize, true);
            if ($size[0] != 0) {
                if (!isset($options['width'])) {
                    $options['width'] = $size[0];
                }
            }
            if ($size[1] != 0) {
                if (!isset($options['height'])) {
                    $options['height'] = $size[1];
                }
            }

            return $options;
        }

        /**
         *
         * @param int $forumId
         */
        public function addForumButtons($buttons, $forumId)
        {

            if ($this->map_enabled($forumId) && $this->helper->checkPermission($forumId, 'mapview')) {
                $forum = $this->helper->getForumFromDB($forumId);
                if ($forum['enable_maps'] || $forum['enable_maps_inherited']) {
                    array_insert($buttons, 0, array(
                        array(
                            "id"   => 'viewmapforforum:' . $forumId,
                            "text" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['VIEW_MAP_FOR_FORUM']
                        )
                    ));

                }
            }

            if ($this->helper->checkPermission($forumId, 'subscribeforum')) {
                $subscriptionId = $this->helper->subscription->getSubforumSubscriptionFromDB($forumId, $this->User->id);
                if ($subscriptionId) {
                    $text = C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_SUBFORUM');
                } else {
                    $text = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIBE_SUBFORUM');
                }
                array_insert($buttons, 0, array(
                    array(
                        "id"   => 'subscribesubforumdialog:' . $forumId,
                        "text" => $text
                    )
                ));
            }

            if ($this->helper->checkPermission($forumId, 'addmember')) {
                array_insert($buttons, 0, array(
                    array(
                        "id"   => 'addmemberdialog:' . $forumId,
                        "text" => C4GForumHelper::getTypeText($this->c4g_forum_type,'ADD_MEMBER')
                    )
                ));
            }
            if ($this->helper->checkPermission($forumId, 'newthread')) {
                array_insert($buttons, 0, array(
                    array(
                        "id"   => 'newthread:' . $forumId,
                        "text" => C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_THREAD')
                    )
                ));
            }

            return $buttons;
        }


        /**
         * @param $id
         * @param $forumTree
         *
         * @return array
         */
        public function getForumInTable($id, $forumTree)
        {

            list($access, $message) = $this->checkPermissionForAction($id, $this->c4g_forum_param_forum);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $data                 = array();
            $data['aoColumnDefs'] = array(
                array(
                    'sTitle'      => 'key',
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "responsivePriority"  => array(0),
                    "aTargets"    => array(0)
                ),
                array(
                    'sTitle'                => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD'),
                    "sClass"                => 'c4g_forum_tlist_threadname',
                    "sWidth"                => '50%',
                    "aDataSort"             => array(
                        9,
                        1
                    ),
                    "aTargets"              => array(1),
                    "responsivePriority"    => array(1),
                    "c4gMinTableSizeWidths" => array(
                        array(
                            "tsize" => 500,
                            "width" => '50%'
                        ),
                        array(
                            "tsize" => 0,
                            "width" => ''
                        )
                    )
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LAST_AUTHOR_SHORT'],
                    "sClass"          => 'c4g_forum_tlist_last_author',
                    "aDataSort"       => array(
                        9,
                        2,
                        4
                    ),
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_lastperson != '1'),
                    "aTargets"        => array(2),
                    "responsivePriority" => array(2),
                    "c4gMinTableSize" => 700
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LAST_POST_SHORT'],
                    "sClass"          => 'c4g_forum_tlist_last_post',
                    "aDataSort"       => array(
                        10,
                        4
                    ),
                    "sType"           => 'de_datetime',
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_lastdate != '1'),
                    "asSorting"       => array(
                        'desc',
                        'asc'
                    ),
                    "aTargets"        => array(3),
                    "responsivePriority" => array(3),
                    "c4gMinTableSize" => 700
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(4),
                    "responsivePriority" => array(4)
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['AUTHOR'],
                    "sClass"          => 'c4g_forum_tlist_author',
                    "aDataSort"       => array(
                        9,
                        5,
                        7
                    ),
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_createperson != '1'),
                    "aTargets"        => array(5),
                    "responsivePriority" => array(5),
                    "c4gMinTableSize" => 500
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CREATED_ON'],
                    "sClass"          => 'c4g_forum_tlist_created',
                    "sType"           => 'de_datetime',
                    "aDataSort"       => array(
                        10,
                        7
                    ),
                    "asSorting"       => array(
                        'desc',
                        'asc'
                    ),
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_createdate != '1'),
                    "aTargets"        => array(6),
                    "responsivePriority" => array(6),
                    "c4gMinTableSize" => 500
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(7),
                    "responsivePriority" => array(7)
                ),
                array(
                    'sTitle'      => '#',
                    "sClass"      => 'c4g_forum_tlist_postcount',
                    "asSorting"   => array(
                        'desc',
                        'asc'
                    ),
                    "bSearchable" => false,
                    "bVisible"        => ($this->c4g_forum_remove_count != '1'),
                    "aTargets"    => array(8),
                    "responsivePriority" => array(8)
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(9),
                    "responsivePriority" => array(9)
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(10),
                    "responsivePriority" => array(10)
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(11),
                    "responsivePriority" => array(11)
                ),
            );


            if ($this->c4g_forum_rating_enabled) {

                foreach($data['aoColumnDefs'] as $key => $item){
                    if($key > 0){
                        if(isset($data['aoColumnDefs'][$key]['aTargets'])) {
                            if(is_array($data['aoColumnDefs'][$key]['aTargets'])) {
                                foreach ($data['aoColumnDefs'][$key]['aTargets'] as $i => $val) {
                                    $data['aoColumnDefs'][$key]['aTargets'][$i] += 1;
                                    $data['aoColumnDefs'][$key]['responsivePriority'][$i] += 1;
                                }
                            }
                        }
                    }
                }

                array_insert($data['aoColumnDefs'], 1, array(
                                                      array(
                                                          'sTitle'                => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATING'],
                                                          "sWidth"                => '10%',
                                                          "aDataSort"             => array(1),
                                                          "aTargets"              => array(1),
                                                          "responsivePriority"    => array(1),
                                                          "c4gMinTableSizeWidths" => array(
                                                              array(
                                                                  "tsize" => 200,
                                                                  "width" => '25%'
                                                              ),
                                                              array(
                                                                  "tsize" => 0,
                                                                  "width" => ''
                                                              )
                                                          )
                                                      )
                                                  )
                );

            }

            if ($this->c4g_forum_table_jqui_layout) {
                $data['bJQueryUI'] = true;
            }

            $scroll = deserialize($this->c4g_forum_scroll, true);
            if ($scroll[0] != 0) {
                $data['sScrollX'] = $scroll[0] . $scroll[2];
            }
            if ($scroll[1] != 0) {
                $data['sScrollY'] = $scroll[1] . $scroll[2];
            } else {
                $size = deserialize($this->c4g_forum_size, true);
                if (($size[1] >= 200) && ($size[2] == 'px')) {
                    // if height is set, but not scrollY, then try to set scrollY to a useful value
                    // note: the perfect value depends on the used jQuery UI theme
                    $data['sScrollY'] = ($size[1] - 120) . $scroll[2];
                }

            }

            $sorting = 3;
            if ($this->c4g_forum_rating_enabled) {
                $sorting = 4;
            }

            $data['aaSorting']       = array(
                array(
                    $sorting,
                    'desc'
                )
            );
            $data['responsive'] = true;
            $data['bScrollCollapse'] = true;
            $data['bStateSave']      = false;
            $data['sPaginationType'] = 'full_numbers';
            $data['oLanguage']       = array(
                "oPaginate"      => array(
                    "sFirst"    => '<<',
                    "sLast"     => '>>',
                    "sPrevious" => '<',
                    "sNext"     => '>'
                ),
                "sEmptyTable"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_EMPTY'),
                "sInfo"          => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_INFO'),
                "sInfoEmpty"     => "-",
                "sInfoFiltered"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_FILTERED'),
                "sInfoThousands" => '.',
                "sLengthMenu"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_LENGTHMENU'),
                "iDisplayLength" => 25,
                "bLengthChange"  => true,
                "sProcessing"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_PROCESSING'),
                "sSearch"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_SEARCH'),
                "sZeroRecords"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_NOTFOUND')
            );

            $threads = $this->helper->getThreadsFromDB($id);
            $forum   = $this->helper->getForumFromDB($id);
            $user = FrontendUser::getInstance();
            $userData = $user->getData();
            $userId = $userData['id'];

            foreach ($threads AS $thread) {
                /**
                 * @Todo fix Owner/Recipient
                 */
//                if($this->c4g_forum_type =="TICKET"){
//                    if($this->helper->checkPermission($id,'showsentthreads')){
//                        $threadOwner = array_flip(unserialize($thread['owner']));
//                    }
//                    $threadRecipient = array_flip(unserialize($thread['recipient']));
//                    if(!(array_key_exists($userId,$threadOwner) || array_key_exists($userId,$threadRecipient))){
//                        continue;
//                    }
//                }
                switch ($this->c4g_forum_threadclick) {
                    case 'LPOST':
                        $threadAction = 'readlastpost:' . $thread['id'];
                        break;

                    case 'FPOST':
                        $threadAction = 'readpostnumber:' . $thread['id'] . ':1';
                        break;

                    default:
                        $threadAction = 'readthread:' . $thread['id'];
                        break;
                }
                if ($thread['lastPost']) {
                    $lastPost     = $thread['lastPost'];
                    $lastUsername = $thread['lastUsername'];
                } else {
                    $lastPost     = $thread['creation'];
                    $lastUsername = $thread['username'];
                }


                switch ($this->c4g_forum_tooltip) {
                    case "title_first_post":
                        $tooltip = $this->helper->getFirstPostLimitedTextOfThreadFromDB($thread['id'], 250, true);
                        $tooltip = preg_replace('/\[[^\[\]]*\]/i', '', $tooltip);
                        break;
                    case "title_last_post":
                        $tooltip = $this->helper->getLastPostLimitedTextOfThreadFromDB($thread['id'], 250, true);
                        $tooltip = preg_replace('/\[[^\[\]]*\]/i', '', $tooltip);
                        break;
                    case "body_first_post":
                        $tooltip = $this->helper->getFirstPostLimitedTextOfThreadFromDB($thread['id'], 250);
                        $tooltip = preg_replace('/\[[^\[\]]*\]/i', '', $tooltip);
                        break;
                    case "body_last_post":
                        $tooltip = $this->helper->getLastPostLimitedTextOfThreadFromDB($thread['id'], 250);
                        $tooltip = preg_replace('/\[[^\[\]]*\]/i', '', $tooltip);
                        break;
                    case "threadtitle":
                        if ($this->c4g_forum_multilingual) {
                            $tooltip = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $thread['name']);
                        } else {
                            $tooltip = $thread['name'];
                        }
                        break;
                    case "threadbody":
                        $tooltip = $thread['threaddesc'];
                        break;
                    case "disabled":
                        $tooltip = false;
                        break;
                    default:
                        $tooltip = $thread['threaddesc'];
                        break;
                }

                if (strlen($tooltip) >= 245) {
                    $tooltip = substr($tooltip, 0, strrpos($tooltip, ' '));
                    $tooltip .= ' [...]';
                }

                if($this->c4g_forum_type == "TICKET"){
                    $title = $this->helper->getTicketTitle($thread['id'],$this->c4g_forum_type);
                }
                else{
                    $title = $thread['name'];
                }
                $plainHtmlData = false;
                if ($this->plainhtml) {
                    // for search engines: only show threadnames
                    if ($this->c4g_forum_multilingual) {
                        $plainHtmlData = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $this->helper->checkThreadname($title)) . '<br/>';
                    } else {
                        $plainHtmlData .= $this->helper->checkThreadname($title) . '<br/>';
                    }
                } else {
                    if ($this->c4g_forum_multilingual) {
                        $threadname = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $title);
                    } else {
                        $threadname = $title;
                    }

                    $aaData = array(
                        $threadAction,
                        $this->helper->checkThreadname($threadname),
                        $lastUsername,
                        $this->helper->getDateTimeString($lastPost),
                        $lastPost, // hidden column for sorting
                        $thread['username'],
                        $this->helper->getDateTimeString($thread['creation']),
                        $thread['creation'], // hidden column for sorting
                        $thread['posts'],
                        $thread['sort'], // hidden column for sorting
                        (999 - $thread['sort']), // hidden column for sorting
                        $tooltip // hidden column for tooltip
                    );    // hidden column for tooltip

                    if ($this->c4g_forum_rating_enabled) {

                        $aRating  = $this->getRating4Thread($thread,true);
                        $rating = $aRating['rating'];
                        $sRating = "";
                        if (!empty($rating)) {
                            $sRating = '
                                <div class="rating_wrapper">
                                    <fieldset class="rating_static">
                                        <span id="staticStar5" ' . (($rating == "5") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar5" title="5 stars"></label>
                                        <span id="staticStar45" ' . (($rating == "4.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar45" title="4.5 stars"></label>
                                        <span id="staticStar4" ' . (($rating == "4") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar4" title="4 stars"></label>
                                        <span id="staticStar35" ' . (($rating == "3.5") ? " class=\"checked\"" : "") . ' ></span><label class="half" for="staticStar35" title="3.5 stars"></label>
                                        <span id="staticStar3" ' . (($rating == "3") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar3" title="3 stars"></label>
                                        <span id="staticStar25" ' . (($rating == "2.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar25" title="2.5 stars"></label>
                                        <span id="staticStar2" ' . (($rating == "2") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar2" title="2 stars"></label>
                                        <span id="staticStar15" ' . (($rating == "1.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar15" title="1.5 stars"></label>
                                        <span id="staticStar1" ' . (($rating == "1") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar1" title="1 stars"></label>
                                        <span id="staticStar05" ' . (($rating == "0.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar05" title="0.5 stars"></label>
                                    </fieldset><span class="score">&#216 ' . $rating . '&nbsp;&nbsp;('.$aRating['overall'].' '.(($aRating['overall'] > 1)?$GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATINGS_MULTIPLE']:$GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATINGS_SINGLE']).')</spa>
                                </div>';
                        }
                        array_insert($aaData, 1, $sRating);
                    }

                    $data['aaData'][] = $aaData;


                }
            }


            $buttons = $this->addDefaultButtons(array(), $id);
            $buttons = $this->addForumButtons($buttons, $id);

            $tooltipcol = 11;
            if ($this->c4g_forum_rating_enabled) {
                $tooltipcol = 12;
            }

            $return = array(
                "contenttype"    => "datatable",
                "contentdata"    => $data,
                "contentoptions" => array(
                    "actioncol"     => 0,
                    "tooltipcol"    => $tooltipcol,
                    "selectOnHover" => true,
                    "clickAction"   => true
                ),
                "state"          => $this->c4g_forum_param_forum.':' . $id,
                "breadcrumb"     => $this->getBreadcrumb($id),
                "headline"       => $this->getHeadline($this->getForumLanguageConfig($forum,'headline')),
                "buttons"        => $buttons
            );
            if ($plainHtmlData) {
                $return['plainhtml'] = $plainHtmlData;
            }
            if ($forum['pretext']) {
                $return['precontent'] = $this->replaceInsertTags($forum['pretext']);
                if ($this->plainhtml) {
                    $return['metaDescription'] = $this->prepareMetaDescription($return['precontent']);
                }
            }
            if ($forum['posttext']) {
                $return['postcontent'] = $this->replaceInsertTags($forum['posttext']);
            }

            if ($forumTree) {
                if ($this->c4g_forum_navigation == 'TREE') {
                    $return['treedata'] = $this->getForumTree($id, 0);
                }
            }

            return $return;
        }


        /**
         * Generate tree data of forums for items in a jQuery-dynatree
         *
         * @param int     $pid      - ID of parent forum
         * @param boolean $actForum - ID of active forum (is automatically activated)
         *
         * @return array
         */
        public function getForums($pid, $actForum)
        {

            $return = array();
            $forums = $this->helper->getForumsFromDB($pid);
            if (count($forums) == 0) {

                return array(
                    "breadcrumb"     => $this->getBreadcrumb($pid),
                    "contenttype"    => "html",
                    "contentoptions" => array("scrollable" => false),
                    "contentdata"    => sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['NO_ACTIVE_FORUMS'], $pid)
                );

            }

            foreach ($forums AS $forum) {
                if ($forum['subforums'] > 0) {
                    $children = $this->getForums($forum['id'], $actForum);
                } else {
                    $children = array();
                }

                $expand = (sizeOf($children) > 0);

                if ($forum['use_intropage'] && (!$this->c4g_forum_hide_intropages)) {
                    $action = 'forumintro';
                } else {
                    if ($forum['subforums'] == 0) {
                        $action = $this->c4g_forum_param_forum;
                    } else {
                        $action = $this->c4g_forum_param_forumbox;
                    }
                }
                $row = array(
                    "title"    => $this->getForumLanguageConfig($forum,'name') . ' (' . $forum['threads'] . ')',
                    "key"      => $action . ':' . $forum['id'],
                    "isFolder" => true,
                    "children" => $children,
                    "expand"   => $expand,
                    "tooltip"  => nl2br(str_replace("'", '', C4GUtils::secure_ugc($this->getForumLanguageConfig($forum,'description'))))
                );
                if ($forum['id'] == $actForum) {
                    $row['activate'] = true;
                }
                if ($forum['linkurl'] != '') {
                    $row['href'] = $this->getForumLink($forum);
                    if ($forum['link_newwindow']) {
                        $row['href_newwindow'] = true;
                    }
                }
                $return[] = $row;
            }

            return $return;

        }


        /**
         * Generate tree data of forums in a jQuery-dynatree
         *
         * @param int $actForum - ID of active forum (is automatically activated)
         *
         * @return array
         */
        public function getForumTree($actForum)
        {

            $children = $this->getForums($this->c4g_forum_startforum, $actForum);

            $treedata = array(
                "children"        => $children,
                "clickFolderMode" => 1,
                "autoCollapse"    => false,
                "classNames"      => array("title" => "dynatree-title c4gGuiTooltip"),
                "fx"              => array(
                    "height"   => "toggle",
                    "duration" => 200
                )
            );

            return $treedata;
        }


        /**
         * Get initial jQuery Dynatree including buttons
         */
        public function generateForumTree()
        {

            $return = array(
                "treedata" => $this->getForumTree(0),
                "buttons"  => $this->addDefaultButtons(array(), 0)
            );

            return $return;
        }


        /**
         * @param $aThread
         *
         * @return float|int
         */
        public function getRating4Thread($aThread, $bWithOverall = false)
        {

            $rating = 0;
            $posts  = $this->helper->getPostsOfThreadFromDB($aThread['id']);

            $sSql    = "SELECT SUM(rating) as total, COUNT(id) as cnt FROM tl_c4g_forum_post WHERE pid = ? AND rating > 0";
            $oRes    = \Database::getInstance()->prepare($sSql)->execute($aThread['id']);
            $aResult = $oRes->fetchAssoc();
            if (!empty($aResult) && $aResult['cnt'] > 0) {
                $rating = $aResult['total'] / $aResult['cnt'];
                $rating *= 2;
                $rating = round($rating);
                $rating = $rating / 2;
            }

            if($bWithOverall === true){
                $rating = array(
                    "rating" => $rating,
                    "overall" => $aResult['cnt']
                );
            }

            return $rating;
        }


        /**
         * @param $thread
         *
         * @return string
         */
        public function generateThreadHeaderAsHtml($thread)
        {

            if ($thread['threaddesc'] != '') {
                if ($this->c4g_forum_posts_jqui) {
                    $data = '<div class="c4gForumThreadHeader c4gGuiAccordion ui-widget ui-widget-header ui-corner-all">';
                    $data .= '<h3><a href="#">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['THREADDESC'] . '</a></h3>';
                    $data .= '<div class="c4gForumThreadHeaderDesc">' .
                             $thread['threaddesc'];

                    if ($this->c4g_forum_rating_enabled) {

                        $rating = $this->getRating4Thread($thread);

                        if (!empty($rating)) {

                            $data .= '
                                <div class="rating_wrapper">
                                    <fieldset class="rating_static">
                                        <span id="staticStar5" ' . (($rating == "5") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar5" title="5 stars"></label>
                                        <span id="staticStar45" ' . (($rating == "4.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar45" title="4.5 stars"></label>
                                        <span id="staticStar4" ' . (($rating == "4") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar4" title="4 stars"></label>
                                        <span id="staticStar35" ' . (($rating == "3.5") ? " class=\"checked\"" : "") . ' ></span><label class="half" for="staticStar35" title="3.5 stars"></label>
                                        <span id="staticStar3" ' . (($rating == "3") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar3" title="3 stars"></label>
                                        <span id="staticStar25" ' . (($rating == "2.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar25" title="2.5 stars"></label>
                                        <span id="staticStar2" ' . (($rating == "2") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar2" title="2 stars"></label>
                                        <span id="staticStar15" ' . (($rating == "1.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar15" title="1.5 stars"></label>
                                        <span id="staticStar1" ' . (($rating == "1") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar1" title="1 stars"></label>
                                        <span id="staticStar05" ' . (($rating == "0.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar05" title="0.5 stars"></label>
                                    </fieldset><span class="score">&#216 (' . $rating . ')</spa>
                                </div>';

                        }

                    }


                    $data .= '</div>';
                    $data .= '</div>';
                } else {
                    $data = '<div class="c4gForumThreadHeader c4gForumThreadHeaderNoJqui">';
                    $data .= '<h2>' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['THREADDESC'] . '</h2>';
                    $data .= '<div class="c4gForumThreadHeaderDesc">' .
                             $thread['threaddesc'];

                    $data .= '</div>';
                    $data .= '</div><hr>';
                }


                return $data;
            } else {
                return '';
            }

        }


        /**
         *
         * Generate a given post as HTML
         *
         * @param      $post
         * @param      $singlePost
         * @param bool $preview
         *
         * @return string
         */
        public function generatePostAsHtml($post, $singlePost, $preview = false)
        {

            \System::loadLanguageFile('tl_c4g_forum_pn');

            if (!empty($post['tags'])) {
                $post['tags'] = explode(", ", $post['tags']);
            }

            //$collapse = $this->c4g_forum_collapsible_posts;
            $last               = false;
            $first              = false;
            $targetClass        = '';
            $triggerClass       = '';
            $triggerTargetClass = '';
            $hideClass          = '';
            $textClass          = '';
            switch ($this->c4g_forum_collapsible_posts) {
                case 'CL':
                    $last = true;
                    break;
                case 'CF':
                    if (!$last && $post['post_number'] == 1) {
                        $first = true;
                    } elseif ($last && !($post['post_number'] == $post['posts'])) {
                        $last = false;
                    }
                    break;
                case 'CC':
                    $hideClass = ' c4gGuiCollapsible_hide';
                case 'CO':
                    $targetClass        = ' c4gGuiCollapsible_target';
                    $triggerClass       = ' c4gGuiCollapsible_trigger';
                    $triggerTargetClass = ' c4gGuiCollapsible_trigger_target';
                    break;
                default:
                    break;
            }
            if (!$last && !$first) {
                $targetClass .= $hideClass;
                $triggerTargetClass .= $hideClass;
            }

            if ($this->c4g_forum_posts_jqui) {
                $divClass     = " ui-widget ui-widget-header ui-corner-top";
                $linkClass    = " c4gGuiButton";
                $mainDivClass = "c4gForumPost";
            } else {
                $divClass     = " c4gForumPostHeaderNoJqui";
                $linkClass    = "";
                $mainDivClass = "c4gForumPost c4gForumPostNoJqui";
            }

            $data = '<div class="' . $mainDivClass . '"><div class="c4gForumPostHeader' . $divClass . $triggerClass . '">';
            if ($singlePost) {
                if ($post['post_number'] > 1) {
                    $actionFirst = 'readpostnumber:' . $post['threadid'] . ':1;usedialog:post' . $post['id'];
                    $actionPrev  = 'readpostnumber:' . $post['threadid'] . ':' . ($post['post_number'] - 1) . ';usedialog:post' . $post['id'];
                    $addClass    = "";
                    $span        = false;
                } else {
                    $actionFirst = "";
                    $actionPrev  = "";
                    $addClass    = " c4gGuiButtonDisabled";
                    $span        = ($this->c4g_forum_posts_jqui == false);
                }
                if ($span) {
                    $data .=
                        '<span>&lt;&lt;</span>' .
                        '<span>&lt;</span>';
                } else {
                    $data .=
                        '<a href="#" data-action="' . $actionFirst . '" class="c4gGuiAction' . $linkClass . $addClass . '">&lt;&lt;</a>' .
                        '<a href="#" data-action="' . $actionPrev . '" class="c4gGuiAction' . $linkClass . $addClass . '">&lt;</a>';
                }
            }

            if (!$preview) {
                $data .= '<span class="c4g_forum_post_head_postcount_row">' . sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'POST_HEADER_COUNT'), 'class=c4g_forum_post_head_postcount_number', $post['post_number'], 'class=c4g_forum_post_head_postcount_count', $post['posts']) . '</span>';
            }

            if ((!$preview) && (!$singlePost)) {
//            if (!$preview) {
                // change buttons for post
                // change buttons for post
                $act = $this->getChangeActionsForPost($post);
                foreach ($act as $key => $value) {
                    $data .= '<a href="#" data-action="' . $key . '" class="c4gForumPostHeaderChangeButton c4gGuiAction' . $linkClass . $triggerTargetClass . '">' . $value . '</a>';
                }
            }
            $act = $this->getViewActionsForPost($post);
            foreach ($act as $key => $value) {
                $data .= '<a href="#" data-action="' . $key . '" class="c4gForumPostHeaderViewButton c4gGuiAction' . $linkClass . $triggerTargetClass . '">' . $value . '</a>';
            }

            if ($singlePost) {
                if ($post['post_number'] < $post['posts']) {
                    $actionLast = 'readpostnumber:' . $post['threadid'] . ':' . $post['posts'] . ';usedialog:post' . $post['id'];
                    $actionNext = 'readpostnumber:' . $post['threadid'] . ':' . ($post['post_number'] + 1) . ';usedialog:post' . $post['id'];
                    $addClass   = "";
                    $span       = false;
                } else {
                    $actionLast = "";
                    $actionNext = "";
                    $addClass   = " c4gGuiButtonDisabled";
                    $span       = ($this->c4g_forum_posts_jqui == false);
                }

                if ($span) {
                    $data .=
                        '<span>&gt;</span>' .
                        '<span>&gt;&gt;</span>';
                } else {
                    $data .=
                        '<a href="#" data-action="' . $actionNext . '" class="c4gGuiAction' . $linkClass . $addClass . '">&gt;</a>' .
                        '<a href="#" data-action="' . $actionLast . '" class="c4gGuiAction' . $linkClass . $addClass . '">&gt;&gt;</a>';
                }
                $data .=
                    '<a href="#" data-action="readthread:' . $post['threadid'] . ';usedialog:post' . $post['id'] .
                    '" class="c4gForumPostHeaderAll c4gGuiAction' . $linkClass . '">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ALL_POSTS'] . '</a>';
            }

            if (!$this->plainhtml) {
                // show author only when not in plainhtml-mode (=pages that will be indexed by search engines)

                if (($this->c4g_forum_remove_createperson != '1') && ($this->c4g_forum_remove_createdate != '1')) {
                    $data .= '<br><span class="c4g_forum_post_head_origin_row">' .
                        sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_CREATED'], 'class=c4g_forum_post_head_origin_author',
                            $post['username'], 'class=c4g_forum_post_head_origin_datetime', $this->helper->getDateTimeString($post['creation'])) . '</span>';
                } else if ($this->c4g_forum_remove_createperson != '1') {
                    $data .= '<br><span class="c4g_forum_post_head_origin_row">' .
                        sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_CREATED_AUTHOR'], 'class=c4g_forum_post_head_origin_author',
                            $post['username']) . '</span>';
                } else if ($this->c4g_forum_remove_createdate != '1') {
                    $data .= '<br><span class="c4g_forum_post_head_origin_row">' .
                        sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_CREATED_DATE'],
                            'class=c4g_forum_post_head_origin_datetime', $this->helper->getDateTimeString($post['creation'])) . '</span>';
                }
            }
            $data .= '<br>' .
                     sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_SUBJECT'], 'class="c4g_forum_post_head_subject_pre"',
                             'class="c4g_forum_post_head_subject"', $post['subject']) . '<br>';

            if (($post['linkname'] != '') || ($post['linkurl'] != '')) {
                $linkname = $post['linkname'];
                $linkurl  = $post['linkurl'];

                if ($linkname == '') {
                    $linkurl = $linkname;
                }
                if ($linkurl == '') {
                    $linkname = $linkurl;
                }
                if ($post['link_newwindow']) {
                    $linkcode = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_LINK_NEWWINDOW'];
                } else {
                    $linkcode = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_LINK'];
                }
                //$data .= '<span class="c4g_forum_post_head_link' .$triggerTargetClass. '">'.sprintf($linkcode,$linkurl, $linkname).'</span><br>';
                $data .= '<span class="c4g_forum_post_head_link">' . sprintf($linkcode, $linkurl, $linkname) . '</span><br>';
            }
            if (!empty($post['tags'])) {
                $data .= '<span class="c4g_forum_post_head_tags">' . sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_HEADER_TAGS'], implode(", ", $post['tags'])) . '</span><br>';
            }


            if ($this->c4g_forum_rating_enabled) {

                if (!empty($post['rating'])) {

                    $data .= '
                    <div class="rating_wrapper">
                        <fieldset class="rating_static">
                            <span id="staticStar5" ' . (($post['rating'] == "5") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar5" title="5 stars"></label>
                            <span id="staticStar45" ' . (($post['rating'] == "4.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar45" title="4.5 stars"></label>
                            <span id="staticStar4" ' . (($post['rating'] == "4") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar4" title="4 stars"></label>
                            <span id="staticStar35" ' . (($post['rating'] == "3.5") ? " class=\"checked\"" : "") . ' ></span><label class="half" for="staticStar35" title="3.5 stars"></label>
                            <span id="staticStar3" ' . (($post['rating'] == "3") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar3" title="3 stars"></label>
                            <span id="staticStar25" ' . (($post['rating'] == "2.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar25" title="2.5 stars"></label>
                            <span id="staticStar2" ' . (($post['rating'] == "2") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar2" title="2 stars"></label>
                            <span id="staticStar15" ' . (($post['rating'] == "1.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar15" title="1.5 stars"></label>
                            <span id="staticStar1" ' . (($post['rating'] == "1") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar1" title="1 stars"></label>
                            <span id="staticStar05" ' . (($post['rating'] == "0.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar05" title="0.5 stars"></label>
                        </fieldset><span class="score">(' . $post['rating'] . ')</spa>
                    </div>';

                }
            }


            if ($this->c4g_forum_posts_jqui) {
                $divClass = " ui-widget ui-widget-content ui-corner-bottom";
            } else {
                $divClass = " c4gForumPostTextNoJqui";
            }

            $text = $post['text'];
            $text = html_entity_decode($text);


            // search in the forum text for lib and replace by assets/vendor (file download compatibility)
            $text = str_replace('/lib', '/assets/vendor', $text);

            /**
             * Member data
             */
            // Get member object from post author.
            $iAuthorId = $post['authorid'];
            $oMember = \Contao\MemberModel::findOneBy('id', $iAuthorId);
            if ($this->c4g_forum_show_post_count) {
                $iUserPostCount = C4gForumPost::getMemberPostsCountById($iAuthorId);
            }

            // Create a new frontend template for the user's data.
            $oUserDataTemplate = new \Contao\FrontendTemplate('forum_user_data');

            $oUserDataTemplate->sForumType = $this->c4g_forum_type;

            // Get different member properties and hand them over to the user data template.
            $oUserDataTemplate->iUserId = $oMember->id;

            $oUserDataTemplate->c4g_forum_show_pn_button = ($this->User->id && ($this->User->id != $iAuthorId) && $this->c4g_forum_show_pn_button == '1' && !$preview);
            $oUserDataTemplate->pn_label = $GLOBALS['TL_LANG']['tl_c4g_forum_pn']['profile_compose'];

            $sJsLang = C4GForumPNCenter::getClientLangVars();
            $oUserDataTemplate->c4g_pn_js = $sJsLang;

            $oUserDataTemplate->sUserName = $oMember->username;
            $oUserDataTemplate->iUserPostCount = $iUserPostCount;
            if ($this->c4g_forum_show_avatars) {
                $sImage = C4GForumHelper::getAvatarByMemberId($iAuthorId, deserialize($this->c4g_forum_avatar_size));
                $oUserDataTemplate->sAvatarImage = $sImage;
            }

            // Get all fields from the tl_member DCA that are marked with the memberLink eval key.
            foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $sKey => $aField) {
                if ($aField['eval']['memberLink']) {
                    $aMemberLinks[$sKey] = $oMember->$sKey;
                }
            }
            // Remove empty values with "array_filter()" from aMemberLinks-array before handing it over to the template.
            if (is_array($aMemberLinks)) {
                $oUserDataTemplate->aMemberLinks = array_filter($aMemberLinks);
            }

            // Online status.
            if ($this->c4g_forum_show_online_status && !$preview) {
                $bIsOnline = C4gForumSession::getOnlineStatusByMemberId($iAuthorId, $this->c4g_forum_member_online_time);
                $oUserDataTemplate->bShowOnlineStatus = true;
                $oUserDataTemplate->bIsOnline = $bIsOnline;
            }


            // Get member rank by language and member post count.
            if ($this->c4g_forum_show_ranks) {
                // pass true as param to force return value to be array
                $aUserRanks = deserialize($this->c4g_forum_member_ranks, true);
                $sUserRank = '';
                foreach ($aUserRanks as $aUserRank) {
                    if ($iUserPostCount >= $aUserRank['rank_min'] && $this->c4g_forum_language_temp === $aUserRank['rank_language']) {
                        $sUserRank = $aUserRank['rank_name'];
                    }
                }
                $oUserDataTemplate->sUserRank = $sUserRank;
            }


            // Store generated template in a variable for later usage.
            $sUserData = $oUserDataTemplate->parse();

            // Get the members signature and store it inside a variable for later usage.
            $sSignature = $oMember->memberSignature;
            $sSignatureArea = '';
            if (!empty($sSignature)) {
                $sSignatureArea = '<div class="signature_wrapper"><hr>' . $sSignature . '</div>';
            }
            /**
             * Member data end
             */

            // Include the former generated member information in the forum's post body.
            $data .=
                '</div>' .
                '<div class="c4gForumPostBody' . $divClass . $targetClass . '">' .
                    $sUserData .
                    '<div class="c4gForumPostText' . $textClass . '">' .
                    $text .
                    '</div>' .
                    $sSignatureArea .
                '</div>' .
                '';

            $data .= '</div>';

            if ($post['edit_count']) {
                if (($this->c4g_forum_remove_lastperson != '1') && ($this->c4g_forum_remove_lastdate != '1')) {
                    $data .=
                        '<div class="c4gForumPostText c4g_forum_post_head_edit_row' . $targetClass . '">' .
                        sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_EDIT_INFO'], 'class="c4g_forum_post_head_edit_count"',
                            $post['edit_count'], 'class="c4g_forum_post_head_edit_datetime"', $this->helper->getDateTimeString($post['edit_last_time']),
                            'class="c4g_forum_post_head_edit_author"', $post['edit_username']) .
                        '</div>';

                } else if ($this->c4g_forum_remove_lastperson != '1') {
                    $data .=
                        '<div class="c4gForumPostText c4g_forum_post_head_edit_row' . $targetClass . '">' .
                        sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_EDIT_INFO_AUTHOR'], 'class="c4g_forum_post_head_edit_count"',
                            $post['edit_count'],
                            'class="c4g_forum_post_head_edit_author"', $post['edit_username']) .
                        '</div>';

                } else if ($this->c4g_forum_remove_lastdate != '1') {
                    $data .=
                        '<div class="c4gForumPostText c4g_forum_post_head_edit_row' . $targetClass . '">' .
                        sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['POST_EDIT_INFO_DATE'], 'class="c4g_forum_post_head_edit_count"',
                            $post['edit_count'], 'class="c4g_forum_post_head_edit_datetime"', $this->helper->getDateTimeString($post['edit_last_time'])) .
                        '</div>';

                }

            }



            if (!$this->c4g_forum_posts_jqui) {
                $data .= '<hr>';
            }

//            $data .=
//                '</div>';


            return $data;
        }


        /**
         * @param $post
         *
         * @return array
         */
        public function getChangeActionsForPost($post)
        {

            $return = array();
            if ($post['authorid'] == $this->User->id) {
                $delAction  = 'delownpostdialog';
                $editAction = 'editownpostdialog';
            } else {
                $delAction  = 'delpostdialog';
                $editAction = 'editpostdialog';
            }
            if ($this->helper->checkPermissionForAction($post['forumid'], $delAction, $this->User->id, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                $return[$delAction . ':' . $post['id']] = C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_POST');
            }
            if ($this->helper->checkPermissionForAction($post['forumid'], $editAction, $this->User->id, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                $return[$editAction . ':' . $post['id']] = C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_POST');
            }
            return $return;
        }


        /**
         * @param $post
         *
         * @return array
         */
        public function getViewActionsForPost($post)
        {

            $return = array();
            if (($post['loc_geox'] && $post['loc_geoy']) || $post['loc_data_content']) {
                if ($this->map_enabled($post['forumid']) && $this->helper->checkPermissionForAction($post['forumid'], 'viewmapforpost', null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                    $return['viewmapforpost:' . $post['id']] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['VIEW_MAP_FOR_POST'];
                }
            }

            return $return;
        }


        /**
         * @param $id
         *
         * @return array
         */
        public function getPostAsHtml($id)
        {
            //$this->setTempLanguage();
            $posts  = $this->helper->getPostFromDB($id);
            $thread = $this->helper->getThreadFromDB($posts[0]['threadid']);
            $data   = $this->generateThreadHeaderAsHtml($thread);
            foreach ($posts as $post) {
                $data .= $this->generatePostAsHtml($post, true);
            }
            if (count($posts) == 1) {
//                $posts = $posts[0];
            }
            list($access, $message) = $this->checkPermission($posts[0]['forumid']);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $dialogbuttons = array(
                array(
                    "action" => 'closedialog:post' . $id,
                    "type"   => 'get',
                    "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CLOSE']
                )
            );

            if ($this->helper->checkPermission($posts[0]['forumid'], 'newpost')) {
                array_insert($dialogbuttons, 0,
                             array(
                                 array(
                                     "action" => 'newpost:' . $posts[0]['threadid'] . ':post' . $id,
                                     "type"   => 'get',
                                     "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_POST')
                                 )
                             )
                );
            }

            // get edit and delete buttons
            $act = $this->getChangeActionsForPost($posts[0]);
            foreach ($act as $key => $value) {
                array_insert($dialogbuttons, 0,
                             array(
                                 array(
                                     "action" => $key,
                                     "type"   => 'get',
                                     "text"   => $value
                                 )
                             )
                );
            }

            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($posts[0]['threadid'], 'name', $this->c4g_forum_language_temp, $posts[0]['threadname']);
            } else {
                $threadname = $posts[0]['threadname'];
            }

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array("title" => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD') . ': ' . $threadname)),
                "dialogid"      => 'post' . $id,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $posts[0]['forumid'] . ";readpost:" . $id,
                "dialogbuttons" => $dialogbuttons,

            );

            return $return;
        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function getLastPostOfThreadAsHtml($threadId)
        {

            $return = $this->getPostAsHtml($this->helper->getIdOfLastPostFromDB($threadId));

            return $return;
        }


        /**
         * @param $threadId
         * @param $postNumber
         *
         * @return array
         */
        public function getPostNumberOfThreadAsHtml($threadId, $postNumber)
        {

            $return = $this->getPostAsHtml($this->helper->getIdOfPostNumberFromDB($threadId, $postNumber));

            return $return;
        }


        /**
         * @param $id
         *
         * @return array
         */
        public function getThreadAsHtml($id)
        {
//            $this->c4g_forum_pagination_active = false;
            $posts  = $this->helper->getPostsOfThreadFromDB($id, ($this->c4g_forum_postsort != 'UP'));
            $thread = $this->helper->getThreadFromDB($id);
            $data   = $this->generateThreadHeaderAsHtml($thread);
            foreach ($posts as $post) {
                $data .= $this->generatePostAsHtml($post, false);
            }

            list($access, $message) = $this->checkPermission($thread['forumid']);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $dialogbuttons = array(
                array(
                    "action" => 'closedialog:thread' . $id,
                    "type"   => 'get',
                    "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CLOSE']
                )
            );

            if (FE_USER_LOGGED_IN) {
                if ($this->helper->checkPermission($thread['forumid'], 'subscribethread')) {
                    $showButton = true;
                    if ($this->helper->checkPermission($thread['forumid'], 'subscribeforum')) {
                        if ($this->helper->subscription->getCompleteSubforumSubscriptionFromDB($thread['forumid'], $this->User->id)) {
                            // no thread subscription button when forum is already subscribed completely
                            $showButton = false;
                        }
                    }
                    if ($showButton) {
                        $subscriptionId = $this->helper->subscription->getThreadSubscriptionFromDB($id, $this->User->id);
                        if ($subscriptionId) {
                            $text = C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_THREAD');
                        } else {
                            $text = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIBE_THREAD');
                        }
                        array_insert($dialogbuttons, 0,
                                     array(
                                         array(
                                             "action" => 'subscribethreaddialog:' . $id,
                                             "type"   => 'get',
                                             "text"   => $text
                                         )
                                     )
                        );
                    }

                }
            }

            if ($this->helper->checkPermission($thread['forumid'], 'newpost') && $thread['state'] != 3) {
                array_insert($dialogbuttons, 0,
                             array(
                                 array(
                                     "action" => 'newpost:' . $id . ':thread' . $id,
                                     "type"   => 'get',
                                     "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_POST')
                                 )
                             )
                );
            }
            if ($this->helper->checkPermission($thread['forumid'], 'closethread') && $thread['state'] != 3) {
                array_insert($dialogbuttons, 0,
                    array(
                        array(
                            "action" => 'closethread:' . $id . ':thread' . $id,
                            "type"   => 'get',
                            "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'CLOSE_THREAD')
                        )
                    )
                );
            }

            if ($this->helper->checkPermission($thread['forumid'], 'movethread')) {
                array_insert($dialogbuttons, 0,
                             array(
                                 array(
                                     "action" => 'movethreaddialog:' . $id,
                                     "type"   => 'get',
                                     "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD')
                                 )
                             )
                );
            }

            if ($this->helper->checkPermission($thread['forumid'], 'delthread')) {
                array_insert($dialogbuttons, 0,
                             array(
                                 array(
                                     "action" => 'delthreaddialog:' . $id,
                                     "type"   => 'get',
                                     "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_THREAD')
                                 )
                             )
                );
            }

            if ($post['threadauthor'] == $this->User->id) {
                $editAction = 'editownthreaddialog';
            } else {
                $editAction = 'editthreaddialog';
            }

            if ($this->helper->checkPermissionForAction($thread['forumid'], $editAction, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                array_insert($dialogbuttons, 0,
                             array(
                                 array(
                                     "action" => $editAction . ':' . $id,
                                     "type"   => 'get',
                                     "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_THREAD')
                                 )
                             )
                );
            }

            if($this->c4g_forum_pagination_active == "1") {

                // $GLOBALS['TL_JAVASCRIPT'][] = "system/modules/con4gis_forum/html/js/jquery.pagination.min.js";
                // $GLOBALS['TL_JAVASCRIPT'][] = "system/modules/con4gis_forum/html/js/jquery.hashchange.min.js";

                $iPerPage = (!empty($this->c4g_forum_pagination_perpage))?$this->c4g_forum_pagination_perpage: 10;
                $sPaginatorFormat = (!empty($this->c4g_forum_pagination_format))?$this->c4g_forum_pagination_format: '[< ncn >]';

                $sFirst = $GLOBALS['TL_LANG']['tl_c4g_forum']['pagination']['first'];
                $sLast = $GLOBALS['TL_LANG']['tl_c4g_forum']['pagination']['last'];

                $sPagination = <<<JSPAGINATE

                <div class="c4g_pagination bottompagination"></div>
                <script>
                    jQuery(document).ready(function(){
                        var prev = {start: 0, stop: 0},
                            cont = jQuery('.c4gForumPost');

                        var Paging = jQuery(".c4g_pagination").paging(cont.length, {
                            format: '{$sPaginatorFormat}',
                            perpage: $iPerPage,
                            lapping: 0,
                            page: null, // we await hashchange() event
                            onSelect: function (page) {

                                var data = this.slice;

                                cont.slice(prev[0], prev[1]).css('display', 'none');
                                if (jQuery(cont.slice(prev[0], prev[1]).next()).hasClass('c4g_forum_post_head_edit_row')) {
                                    jQuery(cont.slice(prev[0], prev[1]).next()).css('display', 'none');
                                }
                                cont.slice(data[0], data[1]).fadeIn("slow");
                                if (jQuery(cont.slice(data[0], data[1]).next()).hasClass('c4g_forum_post_head_edit_row')) {
                                    jQuery(cont.slice(data[0], data[1]).next()).fadeIn("slow");
                                }
                                prev = data;

                                return true; // locate!
                            },
                            onFormat: function (type) {
                                var sUrl = 'http://' + window.location.host + window.location.pathname + window.location.search;
                                switch (type) {
                                case 'block': // n and c
                                    var isActiveClass = (this.page == this.value)?"ui-state-highlight ":"";
                                    return '<a href="'+sUrl+'#'+this.value+'" class="'+isActiveClass+' ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">' + this.value + '</a>';
                                case 'next': // >
                                    return '<a href="'+sUrl+'#'+this.value+'" class=" ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">&gt;</a>';
                                case 'prev': // <
                                    return '<a href="'+sUrl+'#'+this.value+'" class=" ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">&lt;</a>';
                                case 'first': // [
                                    return '<a href="'+sUrl+'#'+this.value+'" class=" ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">$sFirst</a>';
                                case 'last': // ]
                                    return '<a href="'+sUrl+'#'+this.value+'" class=" ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">$sLast</a>';
                                }
                            }
                        });
                        Paging.setPage(1);
                        jQuery(window).hashchange(function() {

                            if (window.location.hash)
                                Paging.setPage(window.location.hash.substr(1));
                            else
                                Paging.setPage(1); // we dropped the initial page selection and need to run it manually

                            jQuery(".c4g_pagination").css('display', '');
                        });

                        jQuery(window).hashchange();

                    });
                </script>
JSPAGINATE;
                $data .= html_entity_decode($sPagination);
            }

            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $thread['name']);

            } else {
                $threadname = $thread['name'];
            }
            if($this->c4g_forum_type == "TICKET"){
                $title = $this->helper->getTicketTitle($thread['id'],$this->c4g_forum_type);
            }
            else{
                $title = C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD') . $threadname;
            }

            $return = array(
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $thread['forumid'] . ";readthread:" . $id,
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogid"      => 'thread' . $id,
                "dialogbuttons" => $dialogbuttons,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" => $title
                                                                  ))
            );

            if ($this->plainhtml) {
                if ($thread['threaddesc']) {
                    $return['metaDescription'] = $this->prepareMetaDescription($thread['threaddesc']);
                } else {
                    if ($posts[0]) {
                        $return['metaDescription'] = $this->prepareMetaDescription($posts[0]['text']);
                    }
                }
            }

            return $return;
        }


        /**
         * @param int $forumId
         *
         * @return array
         */
        public function generateNewThreadForm($forumId)
        {

            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

           $inputThreadname = '';
           if ($this->c4g_forum_multilingual && $this->helper->checkPermission($forumId, 'alllanguages')) {
                $languages = unserialize($this->c4g_forum_multilingual_languages);
                if ($languages) {
                    foreach($languages as $language) {
                        $inputThreadname .=  C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD', $language) . ':<br/>' .
                            '<input name="thread_'.$language.'" type="text" class="formdata ui-corner-all" size="80" maxlength="255" /><br />';
                    }
                }
            }

            if (!$inputThreadname) {
               $inputThreadname .= C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD') . ':<br/>' .
                   '<input name="thread" type="text" class="formdata ui-corner-all" size="80" maxlength="255" /><br />';
            }

            $data = '<div class="c4gForumNewThread">' .
                    '<div class="c4gForumNewThreadName">' .

                    $inputThreadname .
                    '</div>';
           if ($this->c4g_forum_type =="TICKET") {
               $user = FrontendUser::getInstance();
               $groups = $this->helper->getMemberGroupsForForum($forumId,$user->getData()['id']);
               $counterRecipient = 0;
               $options ='';
               $arrOptions = [];

               if ($this->helper->checkPermission($forumId,'tickettomember')) {
                   $data .= '<select name="recipient_member" class="formdata ui-corner-all"';

                   // only check members other than the current user
                   $allMembers = $this->Database->prepare(
                       $select ="SELECT id,username,groups FROM tl_member WHERE id != '$user->id'"
                   )->execute()->fetchAllAssoc();
                   foreach ($allMembers as $member) {
                       $member['groups'] = unserialize($member['groups']);
                       $member['groups'] = array_flip($member['groups']);
                       foreach ($groups as $group) {
                           if (array_key_exists($group['id'],$member['groups']) && !$arrOptions[$member['id']]) {
                               $options .= '<option value="'.$member['id'].'">'.$member['username'].'</option>';
                               $arrOptions[$member['id']] = true;
                               $counterRecipient = $counterRecipient + 1;
                           }
                       }
                   }
                   if ($counterRecipient == 1) {
                       $data .= ' style="visibility:hidden" value="'.$member['id'].'"';
                   } elseif ($counterRecipient == 0) {
                       return null;
                   }
               } else {
                   $data .= '<select name="recipient_group" class="formdata ui-corner-all"';
                   foreach ($groups as $group) {
                       $selects =$this->Database->prepare("SELECT id,name
                FROM tl_member_group
                WHERE id=?")->execute($group['id'])->fetchAllAssoc();
                       foreach($selects as $select){
                           $options .= '<option value="'.$select['id'].'">'.$select['name'].'</option>';
                           $counterRecipient = $counterRecipient +1;
                       }
                   }
                   if($counterRecipient == 1){
                       $data .= ' style="visibility:hidden" value="'. $select['id'].'"';
                   }
                   elseif($counterRecipient == 0){
                       return null;
                   }
               }


               $data .='>'.$options.'</select>';
           }

            $data .= $this->getThreadDescForForm('c4gForumNewThreadDesc', $forumId, 'newthread', '');
            $data .= $this->getThreadSortForForm('c4gForumNewThreadSort', $forumId, 'newthread', '999');

            $editorId = '';

            if ($this->c4g_forum_editor === "bb") {
                $editorId = ' id="editor"';
            } elseif ($this->c4g_forum_editor === "ck") {
                $editorId = ' id="ckeditor"';
            } else {
                $editorId = '';
            }

            $aPost = array(
                "forumid" => $forumId,
                "tags"    => array()
            );

            $sServerName = \Environment::get("serverName");
            $sHttps      = \Environment::get("https");
            $path        = \Environment::get("path");
            $sProtocol   = !empty($sHttps) ? 'https://' : 'http://';
            $sSite       = $sProtocol . $sServerName . $path;
            if (substr($sSite, -1, 1) != "/") {
                $sSite .= "/";
            }


            $sCurrentSite = strtok(\Environment::get('httpReferer'),'?');
            $sCurrentSiteHashed = md5($sCurrentSite . \Config::get('encryptionKey'));

            $binImageUuid = deserialize(unserialize($this->c4g_forum_bbcodes_editor_imguploadpath));
            if ($binImageUuid) {
                $imageUploadPath = \FilesModel::findByUuid(\Contao\StringUtil::binToUuid($binImageUuid[0]));

            }

            $data .= $this->getTagForm('c4gForumNewThreadPostTags', $aPost, 'newthread');
            $data .= '<div class="c4gForumNewThreadContent">' .
                     C4GForumHelper::getTypeText($this->c4g_forum_type,'POST') . ':<br/>' .
                     '<input type="hidden" name="uploadEnv" value="' . $sSite . '">' .
                     '<input type="hidden" name="uploadPath" value="' . $imageUploadPath->path . '">' .
                     '<input type="hidden" name="site" class="formdata" value="' . $sCurrentSite . '">' .
                     '<input type="hidden" name="hsite" class="formdata" value="' . $sCurrentSiteHashed . '">' .
                     '<textarea' . $editorId . ' name="post" cols="80" rows="15" class="formdata ui-corner-all"></textarea><br/>' .
                     '</div>';
            $data .= $this->getPostlinkForForm('c4gForumNewThreadPostLink', $forumId, 'newthread', '', '');
            $data .= $this->getPostMapEntryForForm('c4gForumNewThreadMapData', $forumId, 'newthread', '', '', '', '', '', '', '', '');

            $data .= '</div>';

            $return = array(
                "dialogtype"    => "form",
                "dialogid"      => "newthread",
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $forumId . ";newthread:" . $forumId,
                "dialogdata"    => $data,
                "dialogbuttons" => array(
                    array(
                        "action" => 'sendthread:' . $forumId,
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEND']
                    ),
                    array(
                        "action" => 'previewthread:' . $forumId,
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['PREVIEW']
                    ),
                    array(
                        "action" => 'cancelthread:' . $forumId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" =>
                                                                          sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_THREAD_TITLE'), $this->helper->getForumNameFromDB($forumId, $this->c4g_forum_language_temp)),
                                                                      "modal" => true
                                                                  ))
            );

            return $return;
        }


        /**
         * @param int $threadId
         * @param     $parentDialog
         *
         * @return array
         */
        public function generateNewPostForm($threadId, $parentDialog)
        {

            $thread = $this->helper->getThreadAndForumNameFromDB($threadId);

            $sLastPost = "";
            if ($this->c4g_forum_show_last_post_on_new) {
                $posts = $this->helper->getPostsOfThreadFromDB($threadId, true);
                if (!empty($posts)) {
                    $aPost     = $posts[0];
                    $sLastPost = "<h3>" . C4GForumHelper::getTypeText($this->c4g_forum_type,'LAST_POST') . "</h3>";
                    $sLastPost .= $this->generatePostAsHtml($aPost, false, true);
                    $sLastPost .= "<br>";
                    $sLastPost .= "<h3>" . C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_POST') . "</h3>";
                }
            }


            list($access, $message) = $this->checkPermission($thread['forumid']);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }
            $editorId = '';
            if ($this->c4g_forum_editor === "bb") {
                $editorId = ' id="editor"';
            } elseif ($this->c4g_forum_editor === "ck") {
                $editorId = ' id="ckeditor"';
            } else {
                $editorId = '';
            }

            $aPost = array(
                "forumid" => $thread['forumid'],
                "tags"    => array()
            );

            $sServerName = \Environment::get("serverName");
            $sHttps      = \Environment::get("https");
            $path        = \Environment::get("path");
            $sProtocol   = !empty($sHttps) ? 'https://' : 'http://';
            $sSite       = $sProtocol . $sServerName . $path;
            $sCurrentSite = strtok(\Environment::get('httpReferer'),'?');
            if(empty($sCurrentSite)){
                $sCurrentSite = strtok($sSite . $_SERVER['REQUEST_URI'],'?');
            }

            $sCurrentSiteHashed = md5($sCurrentSite . \Config::get('encryptionKey'));

            if (substr($sSite, -1, 1) != "/") {
                $sSite .= "/";
            }

            $data = $sLastPost;

            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($thread['threadid'], 'name', $this->c4g_forum_language_temp, $thread['threadname']);

            } else {
                $threadname = $thread['threadname'];
            }

            $data .= '<div class="c4gForumNewPost">' .
                     '<div class="c4gForumNewPostSubject">' .
                     $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SUBJECT'] . ':<br/>' .
                     '<input name="subject" value="' . $threadname . '" type="text" class="formdata ui-corner-all" size="80" maxlength="255" /><br />' .
                     '</div>';
            $data .= $this->getTagForm('c4gForumNewPostPostTags', $aPost, 'newpost');

            if ($this->c4g_forum_rating_enabled) {
                // Rating stars
                $data .= '<div class="rating_wrapper">
                            <input type="hidden" name="rating" value="' . $aPost['rating'] . '" id="rating" class="formdata">
                            <label>' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATING'] . '</label><br>
                            <fieldset class="rating">
                                <input type="radio" id="star5" name="_rating" value="5" /><label class="full" for="star5" title="5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star4half" name="_rating" value="4.5" /><label class="half" for="star4half" title="4.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star4" name="_rating" value="4" /><label class="full" for="star4" title="4 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star3half" name="_rating" value="3.5" /><label class="half" for="star3half" title="3.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star3" name="_rating" value="3" /><label class="full" for="star3" title="' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star2half" name="_rating" value="2.5" /><label class="half" for="star2half" title="2.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star2" name="_rating" value="2" /><label class="full" for="star2" title="2 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star1half" name="_rating" value="1.5" /><label class="half" for="star1half" title="1.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star1" name="_rating" value="1" /><label class="full" for="star1" title="1 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STAR'] . '"></label>
                                <input type="radio" id="starhalf" name="_rating" value="0.5" /><label class="half" for="starhalf" title="0.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                            </fieldset><span class="reset_rating"><button onclick="resetRating();">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RESET_RATING'] . '</button></span></div><script>function resetRating(){ jQuery("input[name=\'_rating\']").removeAttr(\'checked\');jQuery("#rating").val(0); };jQuery(document).ready(function(){jQuery("input[name=\'_rating\']").on("click",function(){jQuery("#rating").val(jQuery("input[name=\'_rating\']:checked").val())})});</script>
            ';
            }

            $binImageUuid = deserialize(unserialize($this->c4g_forum_bbcodes_editor_imguploadpath));
            if ($binImageUuid) {
                $imageUploadPath = \FilesModel::findByUuid(\Contao\StringUtil::binToUuid($binImageUuid[0]));
            }

            $data .= '<div class="c4gForumNewPostContent">' .
                      C4GForumHelper::getTypeText($this->c4g_forum_type,'POST') . ':<br/>' .
                     '<input type="hidden" name="uploadEnv" value="' . $sSite . '">' .
                     '<input type="hidden" name="site" class="formdata" value="' . $sCurrentSite . '">' .
                     '<input type="hidden" name="hsite" class="formdata" value="' . $sCurrentSiteHashed . '">' .
                     '<input type="hidden" name="uploadPath" value="' . $imageUploadPath->path . '">' .
                     '<textarea' . $editorId . ' name="post" cols="80" rows="15" class="formdata ui-corner-all"></textarea>' .
                     '</div>';

            $data .= '<input type="hidden" class=formdata ui-corner-all name="recipient" value="' . htmlspecialchars($thread['owner']) . '">
                      <input type="hidden" class=formdata ui-corner-all name="owner" value="' . htmlspecialchars($thread['recipient']) . '">';

            $data .= $this->getPostlinkForForm('c4gForumNewPostPostLink', $thread['forumid'], 'newpost', '', '');
            $locstyle = "";
            if ($this->map_enabled($thread['forumid'])) {
                $locstyle = $this->helper->getDefaultLocstyleFromDB($threadId);
            }
            $data .= $this->getPostMapEntryForForm('c4gForumNewPostMapData', $thread['forumid'], 'newpost', '', '', '', $locstyle, '', '', 0, '');

            $data .=
                '<input name="parentDialog" type="hidden" class="formdata" value="' . $parentDialog . '"></input>' .
                '</div>';
            $title = $this->helper->getTicketTitle($threadId,$this->c4g_forum_type);


            $return = array(
                "dialogtype"    => "form",
                "dialogid"      => "newpost",
                "dialogdata"    => $data,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $thread['forumid'] . ";newpost:" . $threadId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'sendpost:' . $threadId,
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEND']
                    ),
                    array(
                        "action" => 'previewpost:' . $threadId,
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['PREVIEW']
                    ),
                    array(
                        "action" => 'cancelpost:' . $threadId . ':newpost',
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" => sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_POST_TITLE'), $title, $thread['forumname']),
                                                                      "modal" => true
                                                                  ))
            );

            return $return;
        }


        /**
         * @param int $threadId
         *
         * @return array
         * @throws \Exception
         */
        public function sendPost($threadId)
        {

            $sUrl = $this->putVars['site'];
            $sHashedUrl = $this->putVars['hsite'];
            $sUrlCheckValue =  md5($sUrl . \Config::get('encryptionKey'));

            if($sUrlCheckValue !== $sHashedUrl) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'ERROR_SAVE_POST');
                return $return;
            }

            $forumId = $this->helper->getForumIdForThread($threadId);
            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }
            if (!$this->putVars['post']) {
                $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'POST_MISSING');

                return $return;
            }
            if (!$this->putVars['subject']) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SUBJECT_MISSING'];

                return $return;
            }


            if (!isset($this->putVars['rating'])) {
                $this->putVars['rating'] = 0;
            } elseif (empty($this->putVars['rating'])) {
                $this->putVars['rating'] = 0;
            }

            $this->putVars['osmId'] = $this->putVars['osmIdType'] . '.' . $this->putVars['osmId'];
            $result                 = $this->helper->insertPostIntoDB($threadId, $this->User->id, $this->putVars['subject'], $this->putVars['post'], $this->putVars['tags'], $this->putVars['rating'],
                                                                      $this->putVars['linkname'], $this->putVars['linkurl'], $this->putVars['geox'], $this->putVars['geoy'],
                                                                      $this->putVars['locstyle'], $this->putVars['label'], $this->putVars['tooltip'], $this->putVars['geodata'], $this->putVars['osmId'],$this->putVars['recipient'],$this->putVars['owner']);

            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'ERROR_SAVE_POST');
            } else {
                $return = $this->getForumInTable($result['forum_id'], true);
                //$return ['dialogclose'] = array("newpost", $this->putVars['parentDialog']);
                $return ['dialogclose'] = "newpost";
                if ($this->c4g_forum_threadclick == 'THREAD') {
                    $return ['performaction'] = "readthread:" . $threadId;
                } else {
                    $return ['performaction'] = "readpost:" . $result['post_id'];
                }

                $threadSubscribers = $this->helper->subscription->getThreadSubscribersFromDB($threadId);
                $forumSubscribers  = $this->helper->subscription->getForumSubscribersFromDB($forumId, 0);
                if ($threadSubscribers || $forumSubscribers) {
                    $this->helper->subscription->MailCache ['subject']  = $this->putVars['subject'];
                    $this->helper->subscription->MailCache ['post']     = $this->putVars['post'];
                    $this->helper->subscription->MailCache ['linkname'] = $this->putVars['linkname'];
                    $this->helper->subscription->MailCache ['linkurl']  = $this->putVars['linkurl'];
                    $cronjob                                            = $this->helper->subscription->sendSubscriptionEMail(array_merge($threadSubscribers, $forumSubscribers), $threadId, 'new', $sUrl, $this->c4g_forum_type);
                    if ($cronjob) {
                        $return['cronexec'] = $cronjob;
                    }

                }

                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUCCESS_SAVE_POST');
            }

            return $return;

        }


        /**
         * @param $threadId
         * @param $title
         *
         * @return array
         */
        public function previewPost($threadId, $title)
        {

            list($access, $message) = $this->checkPermission($this->helper->getForumIdForThread($threadId));
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            if (!$this->putVars['post']) {
                $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'POST_MISSING');

                return $return;
            }


            $post             = array();
            $post['username'] = $this->User->username;
            $post['creation'] = time();
            $post['subject']  = C4GUtils::secure_ugc($this->putVars['subject']);
            $post['text']     = C4GUtils::secure_ugc($this->putVars['post']);
            $post['linkname'] = C4GUtils::secure_ugc($this->putVars['linkname']);
            $post['linkurl']  = C4GUtils::secure_ugc($this->putVars['linkurl']);
            $data             = $this->generatePostAsHtml($post, false, true);

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array("title" => $title)),
                "dialogid"      => 'previewpost',
                "dialogbuttons" => array(
                    array(
                        "action" => 'closedialog:previewpost',
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CLOSE']
                    )
                ),
            );

            return $return;
        }


        /**
         * @param $postId
         *
         * @return array
         */
        public function previewEditPost($postId)
        {

            $posts = $this->helper->getPostFromDB($postId);

            return $this->previewPost($posts[0]['threadid'], C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_POST_PREVIEW'));
        }


        /**
         * @param $threadId
         * @param $close
         *
         * @return array
         */
        public function cancelPost($threadId, $close)
        {

            //$close = preg_replace('/-/', ':', $close);

            $return = array(
                "dialogclose"   => array(
                    "readthread:" . $threadId,
                    $close
                ),
                "performaction" => "readthread:" . $threadId
            );

            return $return;
        }


        /**
         * @param $forumId
         *
         * @return array
         * @throws \Exception
         */
        public function sendThread($forumId)
        {

            $sUrl = $this->putVars['site'];
            $sHashedUrl = $this->putVars['hsite'];
            $sUrlCheckValue =  md5($sUrl . \Config::get('encryptionKey'));
            $user = FrontendUser::getInstance();

            if($sUrlCheckValue !== $sHashedUrl) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'ERROR_SAVE_POST');
                return $return;
            }

            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }
            if (!$this->putVars['thread']) {
                $found = false;
                $languages = unserialize($this->c4g_forum_multilingual_languages);
                if ($this->c4g_forum_multilingual && $languages) {
                    foreach ($languages as $language) {
                        if ($this->putVars['thread_'.$language]) {
                            $found = true;
                            break;
                        }
                    }
                }

                if (!$found) {
                    $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADNAME_MISSING');
                    return $return;
                }

            }
            if (!$this->putVars['post']) {
                $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'POST_MISSING');

                return $return;
            }

            if ($this->helper->checkPermission($forumId, 'threadsort')) {
                if (!isset($this->putVars['sort'])) {
                    $sort = 999;
                } else {
                    $sort = $this->putVars['sort'];
                }
            } else {
                $sort = 999;
            }

            if ($this->helper->checkPermission($forumId, 'threaddesc')) {
                $threaddesc = $this->putVars['threaddesc'];
            } else {
                $threaddesc = '';
            }

            if ($this->c4g_forum_multilingual && (!$this->putVars['thread'])) {
                if ($this->c4g_forum_language_temp && $this->putVars['thread_'.$this->c4g_forum_language_temp]) {
                    $this->putVars['thread'] == $this->putVars['thread_'.$this->c4g_forum_language_temp];
                }
            }
            if(isset($this->putVars['recipient_member'])){
                $recipient = serialize($this->putVars['recipient_member']);
            }
            else if(isset($this->putVars['recipient_group'])){
                $allMembers = $this->Database->prepare(
                    $select ="SELECT id, groups
                FROM tl_member")->execute()->fetchAllAssoc();
                foreach($allMembers as $allMember){
                    $allMember['groups'] = array_flip(unserialize($allMember['groups']));
                    if(array_key_exists($this->putVars['recipient_group'],$allMember['groups'])){
                        $recipient[] = $allMember['id'];
                    }
                }
                $recipient = serialize($recipient);
            }
            if($this->putVars['id'] ){
                $result = $this->helper->insertThreadIntoDB($forumId, $this->putVars['thread'], $this->User->id, $threaddesc, $sort, $this->putVars['post'], $this->putVars['tags'],
                    $this->putVars['linkname'], $this->putVars['linkurl'], $this->putVars['geox'], $this->putVars['geoy'], $this->putVars['locstyle'],
                    $this->putVars['label'], $this->putVars['tooltip'], $this->putVars['geodata'], $this->putVars['osmId'], $recipient,serialize($user->getData()['id']),$this->putVars['id']);
            }
            else{
                $result = $this->helper->insertThreadIntoDB($forumId, $this->putVars['thread'], $this->User->id, $threaddesc, $sort, $this->putVars['post'], $this->putVars['tags'],
                    $this->putVars['linkname'], $this->putVars['linkurl'], $this->putVars['geox'], $this->putVars['geoy'], $this->putVars['locstyle'],
                    $this->putVars['label'], $this->putVars['tooltip'], $this->putVars['geodata'], $this->putVars['osmId'], $recipient,serialize($user->getData()['id']));
            }

            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'ERROR_SAVE_THREAD');
            } else {
                if ($this->c4g_forum_multilingual) {
                      $globalLanguage = true;
                      if ($this->helper->checkPermission($forumId, 'alllanguages')) {
                        $languages = unserialize($this->c4g_forum_multilingual_languages);
                        if ($languages) {
                            foreach ($languages as $language) {
                                $putVar = $this->putVars['thread_' . $language];
                                if ($putVar) {
                                    $this->helper->insertLanguageEntryIntoDB($result['thread_id'], 'name', $language, $putVar);
                                    if ($language == $this->c4g_forum_language_temp) {
                                        $globalLanguage = false;
                                    }
                                }
                            }
                        }
                    }

                    if (!$this->helper->checkPermission($forumId, 'alllanguages') && $globalLanguage && $this->putVars['thread']) {
                        $this->helper->insertLanguageEntryIntoDB($result['thread_id'],'name',$this->c4g_forum_language_temp,$this->putVars['thread']);
                    }
                }

                $return                   = $this->getForumInTable($forumId, true);
                $return ['dialogclose']   = "newthread";
                $return ['performaction'] = "readthread:" . $result['thread_id'];
                $return ['usermessage']   = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUCCESS_SAVE_THREAD');

                $forumSubscribers = $this->helper->subscription->getForumSubscribersFromDB($forumId, 1);
                if ($forumSubscribers) {
                    $this->helper->subscription->MailCache ['subject']  = $this->putVars['subject'];
                    $this->helper->subscription->MailCache ['post']     = $this->putVars['post'];
                    $this->helper->subscription->MailCache ['linkname'] = $this->putVars['linkname'];
                    $this->helper->subscription->MailCache ['linkurl']  = $this->putVars['linkurl'];
                    $cronjob                                            =
                        $this->helper->subscription->sendSubscriptionEMail($forumSubscribers, $result['thread_id'], 'newThread', $sUrl, $this->c4g_forum_type);
                    if ($cronjob) {
                        $return['cronexec'][] = $cronjob;
                    }
                }

                $sitemapJob = $this->helper->generateSitemapCronjob($this, $forumId);
                if ($sitemapJob) {
                    $return['cronexec'][] = $sitemapJob;
                }

            }

            return $return;
        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function previewThread($forumId)
        {

            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $threadname = $this->putVars['thread'];
            if (!$this->putVars['thread']) {
                $found = false;
                $languages = unserialize($this->c4g_forum_multilingual_languages);
                if ($this->c4g_forum_multilingual && $languages) {
                    foreach ($languages as $language) {
                        if ($this->putVars['thread_'.$language]) {
                            if ($threadname == $this->c4g_forum_language_temp) {
                                $threadname = $this->putVars['thread_'.$language];
                            } else if (!$threadname) {
                                $threadname = $this->putVars['thread_'.$language];
                            }
                            $found = true;
                        }
                    }
                }

                if (!$found) {
                    $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADNAME_MISSING');
                    return $return;
                }
            }
            if (!$this->putVars['post']) {
                $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'POST_MISSING');

                return $return;
            }

            $thread               = array();
            $thread['threaddesc'] = $this->putVars['threaddesc'];
            $data                 = $this->generateThreadHeaderAsHtml($thread);

            $post             = array();
            $post['username'] = $this->User->username;
            $post['creation'] = time();
            $post['subject']  = C4GUtils::secure_ugc($threadname);
            $post['tags']     = C4GUtils::secure_ugc($this->putVars['tags']);
            $post['text']     = C4GUtils::secure_ugc($this->putVars['post']);
            $post['linkname'] = C4GUtils::secure_ugc($this->putVars['linkname']);
            $post['linkurl']  = C4GUtils::secure_ugc($this->putVars['linkurl']);
            $data .= $this->generatePostAsHtml($post, false, true);

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array("title" => C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_THREAD') . ': ' . C4GUtils::secure_ugc($threadname))),
                "dialogid"      => 'previewthread',
                "dialogbuttons" => array(
                    array(
                        "action" => 'closedialog:previewthread',
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CLOSE']
                    )
                ),
            );


            return $return;
        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function cancelThread($forumId)
        {
            $return = array(
                "dialogclose" => "newthread"
            );


            return $return;
        }


        /**
         * @param $dialogId
         *
         * @return array
         */
        public function closeDialog($dialogId,$threadId)
        {
            if (!$threadId) {
                $return = array(
                    "dialogclose" => $dialogId
                );
            } else {
                $return = array(
                    "dialogclose"   => array(
                        "readthread:" . $threadId,
                        $dialogId
                    ),
                    "performaction" => "readthread:" . $threadId
                );
            }

            return $return;
        }


        /**
         * @param $dialogId
         *
         * @return array
         */
        public function useDialog($dialogId)
        {

            $return = array(
                "usedialog" => $dialogId
            );

            return $return;
        }


        /**
         * @param $parentId
         *
         * @return array
         */
        public function getForumInBoxes($parentId, $bDisableCheck = false)
        {

            $forums = $this->helper->getForumsFromDB($parentId);
            if (count($forums) == 0 && $bDisableCheck === false) {
                return array(
                    "breadcrumb"     => $this->getBreadcrumb($parentId),
                    "contenttype"    => "html",
                    "contentoptions" => array("scrollable" => false),
                    "contentdata"    => sprintf("JO" . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['NO_ACTIVE_FORUMS'], $parentId)
                );

            }elseif(count($forums) == 0 && $bDisableCheck === true){
                return array();
            }

            $addClass = "";
            if ($this->c4g_forum_boxes_center) {
                $addClass = " c4gGuiCenterDiv";
            }
            $data = '<div class="c4gForumBoxes' . $addClass . '">';
            foreach ($forums as $forum) {
                if ($forum['linkurl'] != '') {
                    $action = "";
                    $href   = ' data-href="' . $this->getForumLink($forum) . '"';
                    if ($forum['link_newwindow']) {
                        $href .= ' data-href_newwindow="1"';
                    }
                } else {
                    $href = "";
                    if (($forum['use_intropage']) && (!$this->c4g_forum_hide_intropages)) {
                        $action = "forumintro:" . $forum['id'];
                    } else {
                        if ($forum['subforums'] > 0) {
                            $action = $this->c4g_forum_param_forumbox.':' . $forum['id'];
                        } else {
                            $action = $this->c4g_forum_param_forum.':' . $forum['id'];
                        }
                    }
                }
                $divId = "c4gForumBox" . $forum['id'];

                $divClass = "c4gForumBox c4gGuiAction c4gGuiTooltip ";
                if ($this->c4g_forum_boxes_jqui_layout) {
                    $divClass .= " ui-widget";
                }
// TODO

                $objFile               = \FilesModel::findByUuid($forum['box_imagesrc']);
                $forum['box_imagesrc'] = $objFile->path;

                if ($forum['box_imagesrc']) { // check if bin is empty!!!!
                    $divClass .= " c4gForumBoxWithImage";
                    $hoverClass = "c4gForumBoxWithImageHover";
                } else {
                    $divClass .= " c4gForumBoxWithoutImage";
                    $hoverClass = "c4gForumBoxHover";
                    if ($this->c4g_forum_boxes_jqui_layout) {
                        $divClass .= " ui-state-default ui-corner-all";
                        $hoverClass .= " ui-state-hover";
                    } else {
                        $divClass .= " c4gForumBoxNoJqui";
                    }
                }
                $data .= '<div class="' . $divClass . '" id="' . $divId . '" title="' . C4GUtils::secure_ugc($this->getForumLanguageConfig($forum,'description')) . '" data-action="' . $action . '" data-hoverclass="' . $hoverClass . '"' . $href . '>';
                $break = false;
// TODO
                if ($forum['box_imagesrc']) { // check if bin is empty !!!!
                    $imgClass = "c4gForumBoxImage";
                    if ($this->c4g_forum_boxes_jqui_layout) {
                        $imgClass .= " ui-corner-all";
                    }
                    $data .= '<img src="' . $forum['box_imagesrc'] . '" class="' . $imgClass . '" alt="' . $this->getForumLanguageConfig($forum,'name') . '">';
                }
                if ($this->c4g_forum_boxes_text) {
                    $name = $this->getForumLanguageConfig($forum,'name');
                    if (strlen($name) > 100) {
                        $name = substr($name, 0, 97) . '...';
                    }
                    $data .= '<div class="c4gForumBoxText">' . $name . '</div>';
                }
                if ($forum['subforums'] > 0) {

                    if ($this->c4g_forum_boxes_subtext) {
                        $data .= '<div class="c4gForumBoxSubtext">';
                        if ($forum['subforums'] == 1) {
                            $data .= $forum['subforums'] . ' ' . C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBFORUM');
                        } else {
                            $data .= $forum['subforums'] . ' ' . C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBFORUMS');
                        }
                        $data .= '</div>';
                    }

                } else {

                    if ($this->c4g_forum_boxes_subtext) {
                        $data .= '<div class="c4gForumBoxSubtext">';
                        if ($forum['threads'] > 0) {
                            $data .= $forum['threads'] . ' ';
                            if ($forum['threads'] == 1) {
                                $data .= C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD');
                            } else {
                                $data .= C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS');
                            }
                        }
                        if ($forum['posts'] > 0) {
                            $data .= '<br>' . $forum['posts'] . ' ';
                            if ($forum['posts'] == 1) {
                                $data .= C4GForumHelper::getTypeText($this->c4g_forum_type,'POST');
                            } else {
                                $data .= C4GForumHelper::getTypeText($this->c4g_forum_type,'POSTS');
                            }
                        }
                        $data .= '</div>';
                    }

                    if (($forum['posts'] > 0) && ($this->c4g_forum_boxes_lastpost)) {
                        $lastname = $this->helper->checkThreadname($forum['last_threadname']);
                        if (strlen($lastname) > 100) {
                            $lastname = substr($lastname, 0, 97) . '...';
                        }
                        $data .=
                            '<div class="c4gForumBoxLastPost">' .
                            sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'BOX_LAST_POST'),
                                    '<span class="c4gForumBoxLastDate">' . $this->helper->getDateTimeString($forum['last_post_creation']) . '</span>',
                                    '<span class="c4gForumBoxLastAuthor">' . $forum['last_username'] . '</span>',
                                    '<span class="c4gForumBoxLastThread">' . $lastname . '</span>') .
                            '</div>';
                    }
                }


                $data .= '</div>';

            }
            $data .= '</div>';

            $buttons = array();
            if ($this->map_enabled($forum['id']) && $this->helper->checkPermission($parentId, 'mapview')) {
                $forum = $this->helper->getForumFromDB($parentId);
                if ($forum['enable_maps'] || $forum['enable_maps_inherited']) {
                    array_insert($buttons, 0, array(
                        array(
                            "id"   => 'viewmapforforum:' . $parentId,
                            "text" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['VIEW_MAP_FOR_FORUM']
                        )
                    ));
                }
            }

            $return = array(
                "contenttype"    => "html",
                "contentoptions" => array("scrollable" => false),
                "contentdata"    => $data,
                "state"          => $this->c4g_forum_param_forumbox.':' . $parentId,
                "buttons"        => $this->addDefaultButtons($buttons, $parentId),
                "breadcrumb"     => $this->getBreadcrumb($parentId),
            );

            $parentForum = $this->helper->getForumFromDB($parentId);
            if ($parentForum['pretext']) {
                $return['precontent'] = $this->replaceInsertTags($parentForum['pretext']);
            }
            if ($parentForum['posttext']) {
                $return['postcontent'] = $this->replaceInsertTags($parentForum['posttext']);
            }
            $return['headline'] = $this->getHeadline($this->getForumLanguageConfig($parentForum,'headline'));

            return $return;
        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function getForumintro($forumId)
        {

            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }
            $forums = $this->helper->getForumsFromDB($forumId, false, false, 'id');
            $forum  = $forums[0];
            $data   = '<div class="c4gForumIntropage">';
            $data .= $this->replaceInsertTags($forum['intropage']);
            if ($forum['intropage_forumbtn'] != '') {
                if ($forum['subforums'] > 0) {
                    $action = $this->c4g_forum_param_forumbox.':' . $forumId;
                } else {
                    $action = $this->c4g_forum_param_forum.':' . $forumId;
                }
                $class = 'c4gGuiAction';
                if ($forum['intropage_forumbtn_jqui']) {
                    $class .= ' c4gGuiButton';
                }
                $data .= '<a href="#" data-action="' . $action . '" class="' . $class . '">' . specialchars($forum['intropage_forumbtn']) . '</a>';
            }
            $data .= '</div>';
            $return = array(
                "contenttype" => "html",
                "contentdata" => $data,
                "state"       => "forumintro:" . $forumId,
                "buttons"     => $this->addDefaultButtons(array(), $forumId),
                "breadcrumb"  => $this->getBreadcrumb($forumId),
                "headline"    => $this->getHeadline($this->getForumLanguageConfig($forum,'headline'))
            );

            if ($this->c4g_forum_navigation == 'TREE') {
                $return['treedata'] = $this->FgetForumTree($forumId, 0);
            }

            return $return;
        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function delThread($threadId)
        {

            $forumId = $this->helper->getForumIdForThread($threadId);
            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $threadSubscribers = $this->helper->subscription->getThreadSubscribersFromDB($threadId);
            $forumSubscribers  = $this->helper->subscription->getForumSubscribersFromDB($forumId, 1);


            if ($threadSubscribers || $forumSubscribers) {
                $cronexec =
                    $this->helper->subscription->sendSubscriptionEMail(
                        array_merge($threadSubscribers, $forumSubscribers), $threadId, 'delThread', false, $this->c4g_forum_type);
            }
            $result = $this->helper->deleteThreadFromDB($threadId);
            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_THREAD_ERROR');
            } else {
                $return                 = $this->getForumInTable($forumId, true);
                $return ['dialogclose'] = array(
                    "delthread" . $threadId,
                    "thread" . $threadId
                );
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_THREAD_SUCCESS');
                if ($cronexec) {
                    $return['cronexec'][] = $cronexec;
                }

                $sitemapJob = $this->helper->generateSitemapCronjob($this, $forumId);
                if ($sitemapJob) {
                    $return['cronexec'][] = $sitemapJob;
                }

            }

            return $return;
        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function delThreadDialog($threadId)
        {

            list($access, $message) = $this->checkPermission($this->helper->getForumIdForThread($threadId));
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $thread = $this->helper->getThreadAndForumNameFromDB($threadId);
            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($thread['threadid'], 'name', $this->c4g_forum_language_temp, $thread['threadname']);
            } else {
                $threadname = $thread['threadname'];
            }
            $data   = sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_THREAD_WARNING'), $threadname, $thread['forumname']);

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_THREAD'),
                                                                      "height" => 200,
                                                                      "modal"  => true
                                                                  )),
                "dialogid"      => 'delthread' . $threadId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'delthread:' . $threadId,
                        "type"   => 'get',
                        "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_THREAD')
                    ),
                    array(
                        "action" => 'closedialog:delthread:' . $threadId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function subscribeSubforumDialog($forumId)
        {

            list ($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $subscriptionId = $this->helper->subscription->getSubforumSubscriptionFromDB($forumId, $this->User->id);

            if ($subscriptionId) {
                $dialogData = sprintf($GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_SUBSCRIPTION_CANCEL'], $this->helper->getForumNameFromDB($forumId, $this->c4g_forum_language_temp));
                $buttonTxt  = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_CANCEL'];
                $title      = C4GForumHelper::getTypeText($this->c4g_forum_type,'UNSUBSCRIBE_SUBFORUM');
            } else {
                $dialogData = sprintf($GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_TEXT'], $this->helper->getForumNameFromDB($forumId,$this->c4g_forum_language_temp));
                $buttonTxt  = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIBE_SUBFORUM');

                $dialogData .= '<div>' . '<input id="c4gForumSubscriptionForumOnlyThreads"  type="checkbox" name="subscription_only_threads" class="formdata" />' . '<label for="c4gForumSubscriptionForumOnlyThreads">' .
                    C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_SUBFORUM_ONLY_THREADS') . '</label>' . '</div>';
                $title = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIBE_SUBFORUM');
            }

            $dialogbuttons = array();

            $dialogbuttons [] = array(
                "action" => 'subscribesubforum:' . $forumId . ':' . $subscriptionId,
                "type"   => 'send',
                "text"   => $buttonTxt
            );

            $dialogbuttons [] = array(
                "action" => 'closedialog:subscribesubforum' . $forumId,
                "type"   => 'get',
                "text"   => $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['CANCEL']
            );

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $dialogData,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => $title,
                                                                      "height" => 200,
                                                                      "modal"  => true
                                                                  )),
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $forumId . ";subscriptionsubforumdialog:" . $forumId,
                "dialogid"      => 'subscribesubforum' . $forumId,
                "dialogbuttons" => $dialogbuttons
            );

            return $return;

        }


        /**
         * @param $forumId
         * @param $subscriptionId
         * @param $subscriptionOnlyThreads
         *
         * @return array
         */
        public function subscribeSubforum($forumId, $subscriptionId, $subscriptionOnlyThreads)
        {

            list ($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            if ($subscriptionId) {
                $result = $this->helper->subscription->deleteSubscriptionSubforum($subscriptionId);
                if ($result) {
                    $return                 = $this->getForumInTable($forumId, true);
                    $return ['dialogclose'] = "subscribesubforum" . $forumId;
                    $return ['usermessage'] = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_CANCEL_SUCCESS'];
                } else {
                    $return ['usermessage'] = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_ERROR'];
                }

            } else {

                $subscriptionOnlyThreads = ($subscriptionOnlyThreads == 'true');

                $result = $this->helper->subscription->insertSubscriptionSubforumIntoDB($forumId, $this->User->id, $subscriptionOnlyThreads);
                if (!$result) {
                    $return ['usermessage'] = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_ERROR'];
                } else {
                    $return                 = $this->getForumInTable($forumId, true);
                    $return ['dialogclose'] = "subscribesubforum" . $forumId;
                    $return ['usermessage'] = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_SUBFORUM_SUCCESS'];
                }
            }

            return $return;
        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function subscribeThreadDialog($threadId)
        {

            $forumId = $this->helper->getForumIdForThread($threadId);
            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $thread = $this->helper->getThreadAndForumNameFromDB($threadId);
            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($thread['threadid'], 'name', $this->c4g_forum_language_temp, $thread['threadname']);
            } else {
                $threadname = $thread['threadname'];
            }

            $subscriptionId = $this->helper->subscription->getThreadSubscriptionFromDB($threadId, $this->User->id);
            if ($subscriptionId) {
                $dialogData = sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_THREAD_SUBSCRIPTION_CANCEL'), $threadname, $thread ['forumname']);
                $buttonTxt  = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['SUBSCRIPTION_THREAD_CANCEL'];
                $title      = $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['UNSUBSCRIBE_THREAD'];
            } else {
                $dialogData = sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_THREAD_TEXT'), $threadname, $thread ['forumname']);
                $buttonTxt  = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIBE_THREAD');
                $title      = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIBE_THREAD');
            }

            $dialogbuttons = array();

            $dialogbuttons [] = array(
                "action" => 'subscribethread:' . $threadId . ':' . $subscriptionId,
                "type"   => 'get',
                "text"   => $buttonTxt
            );

            $dialogbuttons [] = array(
                "action" => 'closedialog:subscribethread:' . $threadId,
                "type"   => 'get',
                "text"   => $GLOBALS ['TL_LANG'] ['C4G_FORUM'] ['CANCEL']
            );

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $dialogData,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => $title,
                                                                      "height" => 200,
                                                                      "modal"  => true
                                                                  )),
                "dialogid"      => 'subscribethread' . $threadId,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $forumId . ";subscribethreaddialog:" . $threadId,
                "dialogbuttons" => $dialogbuttons
            );

            return $return;

        }


        /**
         * @param $threadId
         * @param $subscriptionId
         *
         * @return mixed
         */
        public function subscribeThread($threadId, $subscriptionId)
        {

            list($access, $message) = $this->checkPermission($this->helper->getForumIdForThread($threadId));
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            if ($subscriptionId) {

                $result = $this->helper->subscription->deleteSubscriptionThread($subscriptionId);
                if ($result) {
                    $return ['dialogclose']   = "subscribethread" . $threadId;
                    $return ['usermessage']   = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_THREAD_CANCEL_SUCCESS');
                    $return ['performaction'] = "readthread:" . $threadId;

                } else {
                    $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_THREAD_ERROR');
                }

            } else {
                $result = $this->helper->subscription->insertSubscriptionThreadIntoDB($threadId, $this->User->id);
                if (!$result) {
                    $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_THREAD_ERROR');
                } else {
                    $return ['dialogclose']   = "subscribethread" . $threadId;
                    $return ['usermessage']   = C4GForumHelper::getTypeText($this->c4g_forum_type,'SUBSCRIPTION_THREAD_SUCCESS');
                    $return ['performaction'] = "readthread:" . $threadId;
                }
            }

            return $return;

        }


        /**
         * @param $value
         *
         * @return mixed
         */
        public function unsubscribeLinkThread($value)
        {

            $result                  = $this->helper->subscription->unsubscribeLinkThread($value, $this->c4g_forum_type);
            $return['usermessage']   = $result['message'];
            $return['performaction'] = 'initnav';

            return $return;
        }


        /**
         * @param $value
         *
         * @return mixed
         */
        public function unsubscribeLinkSubforum($value)
        {

            $result                  = $this->helper->subscription->unsubscribeLinkSubforum($value);
            $return['usermessage']   = $result['message'];
            $return['performaction'] = 'initnav';

            return $return;
        }


        /**
         * @param $value
         *
         * @return mixed
         */
        public function unsubscribeLinkAll($value)
        {

            $return['usermessage']   =
                $this->helper->subscription->unsubscribeLinkAll($value);
            $return['performaction'] = 'initnav';

            return $return;
        }


        /**
         * @param $threadId
         * @param $newForumId
         *
         * @return array
         */
        public function moveThread($threadId, $newForumId)
        {

            $forumId = $this->helper->getForumIdForThread($threadId);
            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            list($access, $message) = $this->checkPermission($newForumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $threadSubscribers   = $this->helper->subscription->getThreadSubscribersFromDB($threadId);
            $forumSubscribers    = $this->helper->subscription->getForumSubscribersFromDB($forumId, 1);
            $newForumSubscribers = $this->helper->subscription->getForumSubscribersFromDB($newForumId, 1);

            if ($threadSubscribers || $forumSubscribers || $newForumSubscribers) {
                $threadOld                                                   = $this->helper->getThreadAndForumNameFromDB($threadId);
                $this->helper->subscription->MailCache ['moveThreadOldName'] = $threadOld['forumname'];
            }

            $result = $this->helper->moveThreadDB($threadId, $newForumId);
            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD_ERROR');
            } else {
                $return                 = $this->getForumInTable($forumId, true);
                $return ['dialogclose'] = array(
                    "movethread" . $threadId,
                    "thread" . $threadId
                );
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD_SUCCESS');

                if ($threadSubscribers || $forumSubscribers || $newForumSubscribers) {
                    $cronjob =
                        $this->helper->subscription->sendSubscriptionEMail(
                            array_merge($threadSubscribers, $forumSubscribers, $newForumSubscribers), $threadId, 'moveThread', false, $this->c4g_forum_type);
                    if ($cronjob) {
                        $return['cronexec'][] = $cronjob;
                    }
                }

                $sitemapJob = $this->helper->generateSitemapCronjob($this, $forumId);
                if ($sitemapJob) {
                    $return['cronexec'][] = $sitemapJob;
                }

            }

            return $return;
        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function moveThreadDialog($threadId)
        {

            $forumId = $this->helper->getForumIdForThread($threadId);
            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $thread = $this->helper->getThreadAndForumNameFromDB($threadId);
            $thread = $this->helper->getThreadAndForumNameFromDB($threadId);
            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($thread['threadid'], 'name', $this->c4g_forum_language_temp, $thread['threadname']);
            } else {
                $threadname = $thread['threadname'];
            }
            $select = sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD_TEXT'), $threadname, $thread['forumname']);

            // get forums as flat array (without hierarchy)
            $allModules = ($this->c4g_forum_move_all == "1");
            $forums = $this->helper->getForumsFromDB($this->c4g_forum_startforum, true, true, 'pid', $allModules);
            $select .= '<select name="forum" class="formdata ui-corner-all">';
            foreach ($forums AS $forum) {
                if ($forum['subforums'] == 0) {
                    if (($forum['id'] != $forumId) && ($forum['linkurl'] == '') && ($this->helper->checkPermissionForAction($forum['id'], $this->action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum))) {
                        $select .= '<option value="' . $forum['id'] . '">' . $this->getForumLanguageConfig($forum,'name') . '</option>';
                        $entries = true;
                    }
                }
            }
            $select .= '</select>';
            $dialogbuttons = array();
            if ($entries) {
                $data            = $select;
                $dialogbuttons[] =
                    array(
                        "action" => 'movethread:' . $threadId,
                        "type"   => 'send',
                        "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD')
                    );
            } else {
                $data = C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD_NO_FORUMS');
            }
            $dialogbuttons[] =
                array(
                    "action" => 'closedialog:movethread:' . $threadId,
                    "type"   => 'get',
                    "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                );
            $return          = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'MOVE_THREAD'),
                                                                      "height" => 200,
                                                                      "modal"  => true
                                                                  )),
                "dialogid"      => 'movethread' . $threadId,
                "dialogbuttons" => $dialogbuttons,
            );

            return $return;

        }


        /**
         * @param $forumId
         *
         * @return mixed
         */
        public function addMember($forumId)
        {

            if (!$this->helper->checkPermissionForAction($forumId, $this->action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }
            if (!$this->putVars['membergroup']) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['MEMBER_GROUP_MISSING'];

                return $return;
            }
            if (!$this->putVars['member']) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['MEMBER_MISSING'];

                return $return;
            }

            $result = $this->helper->addMemberGroupDB($this->putVars['membergroup'], $this->putVars['member']);

            if (!$result) {
                $return ['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ADD_MEMBER_ERROR'];
            } else {
                $return ['dialogclose'] = array("addmember" . $forumId);
                $return ['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ADD_MEMBER_SUCCESS'];
            }

            return $return;
        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function addMemberDialog($forumId)
        {

            list($access, $message) = $this->checkPermission($forumId);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            $data = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['MEMBER_GROUP'] . ':<br/>';
            $data .= '<select name="membergroup" class="formdata ui-corner-all">';
            $groups = $this->helper->getMemberGroupsForForum($forumId);
            foreach ($groups AS $group) {
                $data .= '<option value="' . $group['id'] . '">' . $group['name'] . '</option>';
            }
            $data .= '</select><br/>';

            $data .= $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['MEMBER'] . ':<br/>';
            $data .= '<select name="member" class="formdata ui-corner-all">';
            $members = $this->helper->getNonMembersOfForum($forumId);
            foreach ($members AS $member) {
                $data .= '<option value="' . $member['id'] . '">' . $member['firstname'] . ' ' . $member['lastname'] . ' (' . $member['username'] . ')</option>';
            }
            $data .= '</select>';

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ADD_MEMBER'],
                                                                      "height" => 200,
                                                                      "modal"  => true
                                                                  )),
                "dialogid"      => 'addmember' . $forumId,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $forumId . ";addmemberdialog:" . $forumId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'addmember:' . $forumId,
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ADD_MEMBER']
                    ),
                    array(
                        "action" => 'closedialog:addmember' . $forumId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $postId
         *
         * @return array
         */
        public function delPost($postId)
        {

            $posts = $this->helper->getPostFromDB($postId);
            $post  = $posts[0];
            if ($post['authorid'] == $this->User->id) {
                $action = 'delownpost';
            } else {
                $action = 'delpost';
            }
            if (!$this->helper->checkPermissionForAction($post['forumid'], $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }

            $result = $this->helper->deletePostFromDB($postId);
            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_POST_ERROR');
            } else {
                $return                   = $this->getForumInTable($post['forumid'], true);
                $return ['dialogclose']   = array(
                    "delpost" . $postId,
                    "post" . $postId
                );
                $return ['performaction'] = "readthread:" . $post['threadid'];

                $threadSubscribers = $this->helper->subscription->getThreadSubscribersFromDB($post ['threadid']);
                $forumSubscribers  = $this->helper->subscription->getForumSubscribersFromDB($post ['forumid'], 0);

                if ($threadSubscribers || $forumSubscribers) {
                    $this->helper->subscription->MailCache ['subject']  = $post ['subject'];
                    $this->helper->subscription->MailCache ['post']     = str_replace('<br />', '', $post ['text']);
                    $this->helper->subscription->MailCache ['linkname'] = $post ['linkname'];
                    $this->helper->subscription->MailCache ['linkurl']  = $post ['linkurl'];
                    $cronjob                                            =
                        $this->helper->subscription->sendSubscriptionEMail(
                            array_merge($threadSubscribers, $forumSubscribers), $post ['threadid'], 'delete', false, $this->c4g_forum_type);
                    if ($cronjob) {
                        $return['cronexec'] = $cronjob;
                    }
                }

                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_POST_SUCCESS');
            }

            return $return;
        }


        /**
         * @param $postId
         *
         * @return array
         */
        public function delPostDialog($postId)
        {

            $posts = $this->helper->getPostFromDB($postId);
            $post  = $posts[0];
            if ($post['authorid'] == $this->User->id) {
                $action = 'delownpostdialog';
            } else {
                $action = 'delpostdialog';
            }
            if (!$this->helper->checkPermissionForAction($post['forumid'], $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }

            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($post['threadid'], 'name', $this->c4g_forum_language_temp, $post['threadname']);
            } else {
                $threadname = $post['threadname'];
            }

            $data = sprintf(C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_POST_WARNING'), $post['forumname'], $threadname, $post['username'], $post['subject']);

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_POST'),
                                                                      "height" => 300,
                                                                      "modal"  => true
                                                                  )),
                "dialogid"      => 'delpost' . $postId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'delpost:' . $postId,
                        "type"   => 'get',
                        "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'DEL_POST')
                    ),
                    array(
                        "action" => 'closedialog:delpost' . $postId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $postId
         *
         * @return array
         */
        public function editPost($postId)
        {

            $posts = $this->helper->getPostFromDB($postId);
            $post  = $posts[0];
            if ($post['authorid'] == $this->User->id) {
                $action = 'editownpost';
            } else {
                $action = 'editpost';
            }
            if (!$this->helper->checkPermissionForAction($post['forumid'], $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }
            if (!$this->putVars['post']) {
                $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'POST_MISSING');

                return $return;
            }
            if (!$this->putVars['subject']) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SUBJECT_MISSING'];

                return $return;
            }

            if (!isset($this->putVars['rating'])) {
                $this->putVars['rating'] = 0;
            } else {
                if (empty($this->putVars['rating'])) {
                    $this->putVars['rating'] = 0;
                }
            }


            $this->putVars['osmId']  = $this->putVars['osmIdType'] . '.' . $this->putVars['osmId'];
            $this->putVars['tags']   = \Contao\Input::xssClean($this->putVars['tags']);
            $this->putVars['rating'] = \Contao\Input::xssClean($this->putVars['rating']);
            $result                  = $this->helper->updatePostDB($post, $this->User->id, $this->putVars['subject'], $this->putVars['tags'], $this->putVars['rating'], $this->putVars['post'],
                                                                   $this->putVars['linkname'], $this->putVars['linkurl'], $this->putVars['geox'], $this->putVars['geoy'],
                                                                   $this->putVars['locstyle'], $this->putVars['label'], $this->putVars['tooltip'], $this->putVars['geodata'], $this->putVars['osmId']);


            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_POST_ERROR');
            } else {
                $return                 = $this->getForumInTable($post['forumid'], true);
                $return ['dialogclose'] = array(
                    "editpost" . $postId,
                    "post" . $postId
                );
                if ($this->c4g_forum_threadclick == 'THREAD') {
                    $return ['performaction'] = "readthread:" . $post['threadid'];
                } else {
                    $return ['performaction'] = "readpost:" . $postId;
                }

                $threadSubscribers = $this->helper->subscription->getThreadSubscribersFromDB($post['threadid']);
                $forumSubscribers  = $this->helper->subscription->getForumSubscribersFromDB($post['forumid'], 0);

                if ($threadSubscribers || $forumSubscribers) {
                    $this->helper->subscription->MailCache ['subject']  = $this->putVars['subject'];
                    $this->helper->subscription->MailCache ['post']     = $this->putVars['post'];
                    $this->helper->subscription->MailCache ['linkname'] = $this->putVars['linkname'];
                    $this->helper->subscription->MailCache ['linkurl']  = $this->putVars['linkurl'];
                    $cronjob                                            =
                        $this->helper->subscription->sendSubscriptionEMail(
                            array_merge($threadSubscribers, $forumSubscribers), $post['threadid'], 'edit', false, $this->c4g_forum_type);
                    if ($cronjob) {
                        $return['cronexec'] = $cronjob;
                    }
                }


                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_POST_SUCCESS');
            }

            return $return;
        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function editThread($threadId)
        {

            $thread = $this->helper->getThreadFromDB($threadId);
            if ($thread['author'] == $this->User->id) {
                $action = 'editownthread';
            } else {
                $action = 'editthread';
            }
            if (!$this->helper->checkPermissionForAction($thread['forumid'], $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }
            if (!$this->putVars['thread']) {
                $found = false;
                $languages = unserialize($this->c4g_forum_multilingual_languages);
                if ($this->c4g_forum_multilingual && $languages) {
                    foreach ($languages as $language) {
                        if ($this->putVars['thread_'.$language]) {
                            $found = true;
                            break;
                        }
                    }
                }

                if (!$found) {
                    $return['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type, 'THREADNAME_MISSING');

                    return $return;
                }
            }

            if ($this->helper->checkPermission($thread['forumid'], 'threadsort')) {
                if (isset($this->putVars['sort'])) {
                    $sort = $this->putVars['sort'];
                } else {
                    $sort = 999;
                }
            } else {
                $sort = $thread['sort'];
            }

            if ($this->helper->checkPermission($thread['forumid'], 'threaddesc')) {
                $threaddesc = $this->putVars['threaddesc'];
            } else {
                $threaddesc = $thread['threaddesc'];
            }

            if ($this->c4g_forum_multilingual && (!$this->putVars['thread'])) {
                if ($this->c4g_forum_language_temp && $this->putVars['thread_'.$this->c4g_forum_language_temp]) {
                    $this->putVars['thread'] = $this->putVars['thread_'.$this->c4g_forum_language_temp];
                }
            }

            $result = $this->helper->updateThreadDB($thread, $this->User->id, $this->putVars['thread'], $threaddesc, $sort);

            if (!$result) {
                $return ['usermessage'] = C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_THREAD_ERROR');
            } else {
                if ($this->c4g_forum_multilingual) {
                    $globalLanguage = true;
                    if ($this->helper->checkPermission($thread['forumid'], 'alllanguages')) {
                        $languages = unserialize($this->c4g_forum_multilingual_languages);
                        if ($languages) {
                            foreach ($languages as $language) {
                                $putVar = $this->putVars['thread_' . $language];
                                $updated = $this->helper->updateDBLanguageEntry($thread['id'], 'name', $language, $putVar);
                                if (!$updated) {
                                    $updated = $this->helper->insertLanguageEntryIntoDB($thread['id'], 'name', $language, $putVar);
                                }
                                if ($updated && ($language == $this->c4g_forum_language_temp)) {
                                    $globalLanguage = false;
                                }
                            }
                        }
                    }

                    if (!$this->helper->checkPermission($thread['forumid'], 'alllanguages') && $globalLanguage) {
                        $putVar = $this->putVars['thread'];
                        if ($putVar) {
                            $updated = $this->helper->updateDBLanguageEntry($thread['id'], 'name', $globalLanguage, $putVar);
                            if (!$updated) {
                                $this->helper->insertLanguageEntryIntoDB($thread['id'], 'name', $language, $putVar);
                            }
                        }
                    }
                }
                $return = $this->getForumInTable($thread['forumid'], true);
                $return ['dialogclose']   = array(
                    "editthread" . $threadId,
                    "thread" . $threadId
                );
                $return ['performaction'] = "readthread:" . $threadId;
                $return ['usermessage']   = C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_THREAD_SUCCESS');
            }

            return $return;
        }


        /**
         * @param $divname
         * @param $forumid
         * @param $dialogId
         * @param $linkname
         * @param $linkurl
         *
         * @return string
         */
        public function getPostlinkForForm($divname, $forumid, $dialogId, $linkname, $linkurl)
        {

            if ($this->helper->checkPermissionForAction($forumid, 'postlink', null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum) && false) {
                $addClass = "";
                if ($this->dialogs_jqui) {
                    $addClass = " c4gGuiButton";
                }

                return
                    '<div class="' . $divname . '">' .
                    '<a href="#" data-action="postlink:' . $forumid . ':' . $dialogId . '" class="c4gGuiAction' . $addClass . '">' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['EDIT_POST_LINK'] . '</a>' .
                    '<input name="linkname" id="' . $dialogId . '_linkname" value="' . $linkname . '" type="text" disabled class="formdata ui-corner-all" size="60">' .
                    '<input name="linkurl" id="' . $dialogId . '_linkurl" value="' . $linkurl . '" type="hidden" class="formdata" ><br/>' .
                    '</div>';
            } else {
                return '';
            }

        }


        /**
         * @param $sDivName
         * @param $aPost
         * @param $sForumId
         *
         * @return string
         */
        public function getTagForm($sDivName, $aPost, $sForumId, $label = false, $isSearch = false)
        {

            if ($label === false) {
                $label = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['TAGS'];
            }

            $aTags = $this->getTagsRecursivByParent($aPost['forumid']);
            $aTagsChilds       = $this->getTagsRecursivByChildren($aPost['forumid']);

            $aTags = array_unique(array_merge($aTags,$aTagsChilds));

            $sHtml = "";

            if (!empty($aTags) && $this->c4g_forum_use_tags_in_search) {
                $sHtml = "<div class=\"" . $sDivName . "\">";
                $sHtml .= $label . ':<br/>';
                $sHtml .= "<select name=\"tags\" class=\"formdata c4g_tags\" multiple=\"multiple\" style='width:100%;' data-placeholder='" . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SELECT_TAGS_PLACEHOLDER'] . "'>";

                foreach ($aTags as $sTag) {

                    $sHtml .= "<option";
                    if (in_array($sTag, $aPost['tags'])) {
                        $sHtml .= ' selected="selected"';
                    }
                    $sHtml .= ">" . $sTag . "</option>";
                }
                $sHtml .= "</select>";
                if ($isSearch) {
                    $sHtml .= '<br/><input type="checkbox" id="onlyTags" name="onlyTags" class="formdata ui-corner-all" value="1"/><label for="onlyTags" class="search-label">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['TAGS_CHECKBOX'] . '</label><br/>';
                }
                $sHtml .= "</div>";

                $sHtml .= "<script>jQuery(document).ready(function(){jQuery('.c4g_tags').chosen();});</script>";
            }

            return $sHtml;
        }


        public function getTagsRecursivByParent($sForumId)
        {

            $sReturn     = "";
            $aTagsResult = \Contao\Database::getInstance()->prepare("SELECT tags, pid FROM tl_c4g_forum WHERE id = %s")->execute($sForumId);
            $aTags       = $aTagsResult->row();

            if (!empty($aTags['tags'])) {
                $sReturn = $aTags['tags'];
            } else {
                if ($aTags['pid'] != '0') {
                    $sReturn = $this->getTagsRecursivByParent($aTags['pid']);
                }
            }
            $aReturn = explode(",", $sReturn);
            if (empty($aReturn)) {
                $aReturn = array();
            }
            if (count($aReturn) === 1) {
                if ($aReturn[0] === '') {
                    $aReturn = array();
                }
            }

            return $aReturn;
        }



        public function getTagsRecursivByChildren($sForumId){
            $sReturn = "";
            $aTagsResult = \Contao\Database::getInstance()->prepare("SELECT tags, pid FROM tl_c4g_forum WHERE pid = %s")->execute($sForumId);
            $aTags       = $aTagsResult->row();
            if(empty($aTags)){
                return array();
            }
            if(!empty($aTags['tags'])){
                $sReturn =  $aTags['tags'];
            }else{
                if($aTags['pid'] != '0'){
                    $sReturn = $this->getTagsRecursivByChildren($aTags['id']);
                }
            }
            $aReturn = explode(",", $sReturn);
            if (empty($aReturn)) {
                $aReturn = array();
            }
            if (count($aReturn) === 1) {
                if ($aReturn[0] === '') {
                    $aReturn = array();
                }
            }

            return $aReturn;
        }


        /**
         * @return boolean
         */
        public function map_enabled($forumId)
        {
            //TODO forum id hier übergeben, dann können wir uns das forum hier aus der db holen und abfragen
            $forum = C4gForumModel::findByPk($forumId);
            return ($GLOBALS['con4gis']['maps']['installed']) && (($forum->enable_maps) || static::$useMaps);
        }


        /**
         * @param $divname
         * @param $forumId
         * @param $dialogId
         * @param $geox
         * @param $geoy
         * @param $geodata
         * @param $locstyle
         * @param $label
         * @param $tooltip
         * @param $postId
         * @param $osmId
         *
         * @return string
         */
        public function getPostMapEntryForForm($divname, $forumId, $dialogId, $geox, $geoy, $geodata, $locstyle, $label, $tooltip, $postId, $osmId)
        {
            if ($this->map_enabled($forumId)) {
                $forum = $this->helper->getForumFromDB($forumId);
                if (($forum['enable_maps']) || ($forum['enable_maps_inherited'])) {

                    if ($forum['map_type'] == 'OSMID') {
                        // OSM-ID(-Picker)
                        //check Permission
                        if (!$this->helper->checkPermission($forumId, 'mapextend')) {
                            return '';
                        }

                        $osmId = explode('.', $osmId);
                        if ($osmId[0] == 'way') {
                            $selectWay  = ' selected';
                            $selectNode = '';
                        } else {
                            $selectWay  = '';
                            $selectNode = ' selected';
                        }

                        // part for locationstyle
                        $locstyles = '';
                        if ($forum['map_override_locationstyle']) {
                            $locstyles = $this->helper->getLocStylesForForum($forumId);
                            if (is_array($locstyles)) {
                                $locationstyle =
                                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION_STYLE'] .
                                    '<select id="' . $dialogId . '_locstyle" name="locstyle" value="' . $locstyle . '" ' .
                                    'class="formdata">';
                                foreach ($locstyles AS $locstyle) {
                                    $locationstyle .= '<option value="' . $locstyle['id'] . '">' . $locstyle['name'] . '</option>';
                                }
                                $locationstyle .=
                                    '</select></div>';
                            }
                        }

                        // end of locstyle

                        return
                            '<div class="' . $divname . '">' .
                            $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['OSM_ID'] .
                            '<select name="osmIdType" id="' . $dialogId . '_osmIdType" class="formdata">' .
                            '<option' . $selectNode . '>node</option>' .
                            '<option' . $selectWay . '>way</option>' .
                            '</select>' .
                            '<input name="osmId" id="' . $dialogId . '_osmId" value="' . $osmId[1] . '" type="text" class="formdata"> ' .
                            $locationstyle .
                            //			 			'<input name="locstyle" id="'.$dialogId.'_locstyle" value="'.$locstyle.'" type="hidden" class="formdata">'.
                            '</div>';
                    } else {
                        // GEO-Picker & Editor

                        //check Permission
                        if (!$this->helper->checkPermission($forumId, 'mapedit')) {
                            return '';
                        }

                        $addClass = "";
                        if ($this->dialogs_jqui) {
                            $addClass = " c4gGuiButton";
                        }
                        if (($geox && $geoy) || $geodata) {
                            $butText = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['EDIT_MAP_LOCATION'];
                            $add     = 0;
                        } else {
                            $butText = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ADD_MAP_LOCATION'];
                            $add     = 1;
                        }

                        return
                            '<div class="' . $divname . '">' .
                            '<a href="#" data-action="postmapentry:' . $forumId . ':' . $dialogId . ':' . $add . ':' . $postId . '" ' .
                            'class="c4gGuiAction' . $addClass . '">' .
                            sprintf($butText,
                                ($forum['map_location_label'] ? $forum['map_location_label'] : $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION'])
                            ) . '</a>' .
                            '<input name="geox" id="' . $dialogId . '_geox" value="' . $geox . '" type="text" disabled="disabled" class="formdata">' .
                            '<input name="geoy" id="' . $dialogId . '_geoy" value="' . $geoy . '" type="text" disabled="disabled" class="formdata">' .
                            '<br>' .
                            '<input name="geodata" id="' . $dialogId . '_geodata" value=\'' . $geodata . '\' type="hidden" class="formdata">' .
                            '<input name="locstyle" id="' . $dialogId . '_locstyle" value="' . $locstyle . '" type="hidden" class="formdata">' .
                            '<input name="label" id="' . $dialogId . '_label" value="' . $label . '" type="hidden" class="formdata">' .
                            '<input name="tooltip" id="' . $dialogId . '_tooltip" value="' . $tooltip . '" type="hidden" disabled class="formdata">' .
                            '</div>';
                    }
                }
            } else {
                return '';
            }

        }


        /**
         * @param $divname
         * @param $forumid
         * @param $dialogId
         * @param $sortId
         *
         * @return string
         */
        public function getThreadSortForForm($divname, $forumid, $dialogId, $sortId)
        {

            if ($this->helper->checkPermission($forumid, 'threadsort')) {
                return
                    '<div class="' . $divname . '">' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['THREADSORT'] . ':<br/>' .
                    '<input name="sort" id="' . $dialogId . '_sortid" value="' . $sortId . '" type="text" class="formdata ui-corner-all" size="3" ></input><br />' .
                    '</div>';
            } else {
                return '';
            }

        }


        /**
         * @param $divname
         * @param $forumid
         * @param $dialogId
         * @param $desc
         *
         * @return string
         */
        public function getThreadDescForForm($divname, $forumid, $dialogId, $desc)
        {

            if ($this->helper->checkPermission($forumid, 'threaddesc')) {

                $sCurrentSite = strtok(\Environment::get('httpReferer'),'?');
                $sCurrentSiteHashed = md5($sCurrentSite . \Config::get('encryptionKey'));

                return
                    '<div class="' . $divname . '">' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['THREADDESC'] . ':<br/>' .
                    '<input type="hidden" name="site" class="formdata" value="' . $sCurrentSite . '">' .
                    '<input type="hidden" name="hsite" class="formdata" value="' . $sCurrentSiteHashed . '">' .
                    '<textarea name="threaddesc" id="' . $dialogId . '_threaddesc" class="formdata ui-corner-all" cols="80" rows="3">' . strip_tags($desc) . '</textarea><br />' .
                    '</div>';
            } else {
                return '';
            }
        }


        /**
         * @param $postId
         *
         * @return array
         */
        public function editPostDialog($postId)
        {

            $dialogId = 'editpost' . $postId;
            $posts    = $this->helper->getPostFromDB($postId);

            $post = $posts[0];
            if (!empty($post['tags'])) {
                $post['tags'] = explode(", ", $post['tags']);
            }
            if ($post['authorid'] == $this->User->id) {
                $action        = 'editownpostdialog';
                $previewAction = 'previeweditownpost';
            } else {
                $action        = 'editpostdialog';
                $previewAction = 'previeweditpost';
            }
            if (!$this->helper->checkPermissionForAction($post['forumid'], $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }
            $editorId = '';
            if ($this->c4g_forum_editor === "bb") {
                $editorId = ' id="editor"';
            } elseif ($this->c4g_forum_editor === "ck") {
                $editorId = ' id="ckeditor"';
            } else {
                $editorId = '';
            }


            $sServerName = \Environment::get("serverName");
            $sHttps      = \Environment::get("https");
            $path        = \Environment::get("path");
            $sProtocol   = !empty($sHttps) ? 'https://' : 'http://';
            $sSite       = $sProtocol . $sServerName . $path;
            if (substr($sSite, -1, 1) != "/") {
                $sSite .= "/";
            }


            $data = "";

            $data .= '<div class="c4gForumEditPost">' .
                     '<div class="c4gForumEditPostSubject">' .
                     $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SUBJECT'] . ':<br/>' .
                     '<input name="subject" value="' . $post['subject'] . '" type="text" class="formdata ui-corner-all" size="80" maxlength="100" /><br />' .
                     '</div>';
            $data .= $this->getTagForm('c4gForumEditPostTags', $post, $dialogId);

            if (($post['authorid'] != $this->User->id) && $this->c4g_forum_rating_enabled) {
                // Rating stars
                $data .= '<div class="rating_wrapper">
                            <input type="hidden" name="rating" value="' . $post['rating'] . '" id="rating" class="formdata">
                            <label>' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATING'] . '</label><br>
                            <fieldset class="rating">
                                <input type="radio" id="star5" name="_rating" value="5" ' . (($post['rating'] == 5) ? " checked" : "") . '/><label class="full" for="star5" title="5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star4half" name="_rating" value="4.5" ' . (($post['rating'] == 4.5) ? " checked" : "") . '/><label class="half" for="star4half" title="4.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star4" name="_rating" value="4" ' . (($post['rating'] == 4) ? " checked" : "") . '/><label class="full" for="star4" title="4 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star3half" name="_rating" value="3.5" ' . (($post['rating'] == 3.5) ? " checked" : "") . '/><label class="half" for="star3half" title="3.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star3" name="_rating" value="3" ' . (($post['rating'] == 3) ? " checked" : "") . '/><label class="full" for="star3" title="' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star2half" name="_rating" value="2.5" ' . (($post['rating'] == 2.5) ? " checked" : "") . '/><label class="half" for="star2half" title="2.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star2" name="_rating" value="2" ' . (($post['rating'] == 2) ? " checked" : "") . '/><label class="full" for="star2" title="2 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star1half" name="_rating" value="1.5" ' . (($post['rating'] == 1.5) ? " checked" : "") . '/><label class="half" for="star1half" title="1.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                                <input type="radio" id="star1" name="_rating" value="1" ' . (($post['rating'] == 1) ? " checked" : "") . '/><label class="full" for="star1" title="1 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STAR'] . '"></label>
                                <input type="radio" id="starhalf" name="_rating" value="0.5" ' . (($post['rating'] == 0.5) ? " checked" : "") . '/><label class="half" for="starhalf" title="0.5 ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['STARS'] . '"></label>
                            </fieldset><span class="reset_rating"><button onclick="resetRating();">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RESET_RATING'] . '</button></span></div><script>function resetRating(){ jQuery("input[name=\'_rating\']").removeAttr(\'checked\');jQuery("#rating").val(0); };jQuery(document).ready(function(){jQuery("input[name=\'_rating\']").on("click",function(){jQuery("#rating").val(jQuery("input[name=\'_rating\']:checked").val())})});</script>
            ';
            }

            $sCurrentSite = strtok(\Environment::get('httpReferer'),'?');
            $sCurrentSiteHashed = md5($sCurrentSite . \Config::get('encryptionKey'));

            $binImageUuid = deserialize(unserialize($this->c4g_forum_bbcodes_editor_imguploadpath));
            if ($binImageUuid) {
                $imageUploadPath = \FilesModel::findByUuid(\Contao\StringUtil::binToUuid($binImageUuid[0]));
            }


            $data .= '<div class="c4gForumEditPostContent">' .
                     C4GForumHelper::getTypeText($this->c4g_forum_type,'POST') . ':<br/>' .
                     '<input type="hidden" name="uploadEnv" value="' . $sSite . '">' .
                     '<input type="hidden" name="uploadPath" value="' . $imageUploadPath->path . '">' .
                     '<input type="hidden" name="site" class="formdata" value="' . $sCurrentSite . '">' .
                     '<input type="hidden" name="hsite" class="formdata" value="' . $sCurrentSiteHashed . '">' .
                     '<textarea' . $editorId . ' name="post" cols="80" rows="15" class="formdata ui-corner-all">' . strip_tags($post['text']) . '</textarea>' .
                     '</div>';

            $data .= $this->getPostlinkForForm('c4gForumEditPostLink', $post['forumid'], $dialogId, $post['linkname'], $post['linkurl']);
            $data .= $this->getPostMapEntryForForm('c4gForumEditPostMapData', $post['forumid'], $dialogId,
                                                   $post['loc_geox'], $post['loc_geoy'], $post['loc_data_content'], $post['locstyle'], $post['loc_label'], $post['loc_tooltip'], $postId, $post['loc_osm_id']);

            $data .=
                '</div>';


            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" => C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_POST'),
                                                                      "modal" => true
                                                                  )),
                "dialogid"      => $dialogId,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $post['forumid'] . ";editpostdialog:" . $postId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'editpost:' . $postId,
                        "type"   => 'send',
                        "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'SAVE_POST_CHANGES')
                    ),
                    array(
                        "action" => $previewAction . ':' . $postId,
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['PREVIEW']
                    ),
                    //array( "action" => 'closedialog:'.$dialogId, "type" => 'get', "text" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL'])
                    //array( "action" => 'cancelpost:'.$post['threadid'].':editpostdialog-'.$postId, "type" => 'get', "text" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL'])
                    array(
                        "action" => 'cancelpost:' . $post['threadid'] . ':' . $dialogId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $threadId
         *
         * @return array
         */
        public function editThreadDialog($threadId)
        {
            $dialogId = 'editthread' . $threadId;
            $thread   = $this->helper->getThreadFromDB($threadId);
            if ($thread['author'] == $this->User->id) {
                $action = 'editownthreaddialog';
            } else {
                $action = 'editthreaddialog';
            }
            if (!$this->helper->checkPermissionForAction($thread['forumid'], $action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }

            if ($this->c4g_forum_multilingual) {
                $threadname = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $thread['name']);
            } else {
                $threadname = $thread['name'];
            }

            $inputThreadname = '';
            if ($this->c4g_forum_multilingual && $this->helper->checkPermission($thread['forumid'], 'alllanguages')) {
                $languages = unserialize($this->c4g_forum_multilingual_languages);
                if ($languages) {
                    foreach($languages as $language) {
                        $initialValue = '';
                        if ($thread['name']) {
                            $initialValue = $thread['name'];
                        }
                        $lgthreadname = $this->helper->translateThreadField($thread['id'], 'name', $language, $initialValue);
                        $inputThreadname .=  C4GForumHelper::getTypeText($this->c4g_forum_type, 'THREAD', $language) . ':<br/>' .
                            '<input name="thread_'.$language.'" value="' . $lgthreadname . '" type="text" class="formdata ui-corner-all" size="80" maxlength="255" /><br />';
                    }
                }
            }

            if (!$inputThreadname) {
                $inputThreadname .= C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD') . ':<br/>' .
                    '<input name="thread" type="text"  value="' . $threadname . '" class="formdata ui-corner-all" size="80" maxlength="255" /><br />';
            }


            $data = '<div class="c4gForumEditThread">' .
                     '<div class="c4gForumEditThreadName">' .
                     $inputThreadname;
            $data .= $this->getThreadDescForForm('c4gForumEditThreadDesc', $thread['forumid'], 'editthread', $thread['threaddesc']);
            $data .= '</div>';
            $data .= $this->getThreadSortForForm('c4gForumEditThreadSort', $thread['forumid'], 'editthread', $thread['sort']);
            $data .= '</div>';


            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" => C4GForumHelper::getTypeText($this->c4g_forum_type,'EDIT_THREAD'),
                                                                      "modal" => true
                                                                  )),
                "dialogid"      => $dialogId,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $thread['forumid'] . ";editthreaddialog:" . $threadId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'editthread:' . $threadId,
                        "type"   => 'send',
                        "text"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'SAVE_THREAD_CHANGES')
                    ),
                    array(
                        "action" => 'closedialog:' . $dialogId .':'.$threadId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $forumId
         * @param $dialogId
         *
         * @return array
         */
        public function postLink($forumId, $dialogId)
        {

            if (!$this->helper->checkPermissionForAction($forumId, $this->action, null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }

            $linkName = '#' . $dialogId . '_linkname';
            $linkUrl  = '#' . $dialogId . '_linkurl';

            $data = '<div class="c4gForumPostLink">' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LINKNAME'] . ':<br/>' .
                    '<input name="linkname" value="" ' .
                    'data-source="' . $linkName . '" data-srcattr="value" ' .
                    'data-target="' . $linkName . '" data-trgattr="value" type="text" class="formlink ui-corner-all" size="80" maxlength="80" /><br />' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LINKURL'] . ':<br/>' .
                    '<input name="linkurl" value="" ' .
                    'data-source="' . $linkUrl . '" data-srcattr="value" ' .
                    'data-target="' . $linkUrl . '" data-trgattr="value" type="text" class="formlink ui-corner-all" size="80" maxlength="255" /><br />' .
                    '</div>';


            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['EDIT_POST_LINK'],
                                                                      "modal"  => true,
                                                                      "height" => 300
                                                                  )),
                "dialogid"      => 'postlink' . $forumId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'submit',
                        "type"   => 'submit',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SUBMIT']
                    ),
                    array(
                        "action" => 'clear',
                        "type"   => 'submit',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETE_LINK']
                    ),
                    array(
                        "action" => 'closedialog:postlink' . $forumId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $forumId
         * @param $dialogId
         * @param $add
         * @param $postId
         *
         * @return array
         */
        public function postMapEntry($forumId, $dialogId, $add, $postId)
        {

            if ((!$this->map_enabled($forumId)) ||
                (!$this->helper->checkPermission($forumId, 'mapedit'))
            ) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }

            $forum      = $this->helper->getForumFromDB($forumId);
            $geox       = '#' . $dialogId . '_geox';
            $geoy       = '#' . $dialogId . '_geoy';
            $geodata    = '#' . $dialogId . '_geodata';
            $locstyleId = '#' . $dialogId . '_locstyle';
            $label      = '#' . $dialogId . '_label';
            $tooltip    = '#' . $dialogId . '_tooltip';

            $data = '<div class="c4gForumPostMapEntry">';

            if ($forum['map_type'] == 'PICK') {
                // GEO Picker
                $data .= '<div class="c4gForumPostMapGeoCoords">' .
                         '<div class="c4gForumColumn1">' .
                         $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['GEO_COORDS'] .
                         '</div>' .
                         '<input id="c4gForumPostMapEntryGeoX" name="geox" value="" ' .
                         'data-source="' . $geox . '" data-srcattr="value" ' .
                         'data-target="' . $geox . '" data-trgattr="value" disabled type="text" class="formlink ui-corner-all" size="20" maxlength="20" />' .
                         '<input id="c4gForumPostMapEntryGeoY" name="geoy" value="" ' .
                         'data-source="' . $geoy . '" data-srcattr="value" ' .
                         'data-target="' . $geoy . '" data-trgattr="value" disabled type="text" class="formlink ui-corner-all" size="20" maxlength="20" />' .
                         '</div>';
            } else {
                // Feature Editor
                $data .= '<input id="c4gForumPostMapEntryGeodata" name="geodata" value="" ' .
                         'data-source="' . $geodata . '" data-srcattr="value" ' .
                         'data-target="' . $geodata . '" data-trgattr="value" type="hidden" class="formlink"></input>';

            }
            $disabled = "";
            if (!$this->helper->checkPermission($forumId, 'mapedit_style')) {
                $disabled = "disabled ";

            }

            if ($forum['map_type'] == 'PICK' || $forum['map_type'] == 'OSMID') {
                // $locstyles = C4GMaps::getLocStyles($this->Database);
                $locstyles = $this->helper->getLocStylesForForum($forumId);
                if (is_array($locstyles)) {
                    $data .=
                        '<div class="c4gForumPostMapLocStyle">' .
                        '<div class="c4gForumColumn1">' .
                        $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION_STYLE'] .
                        '</div>' .
                        '<select id="c4gForumPostMapEntryLocStyle" name="locstyle" value="" ' . $disabled .
                        'data-source="' . $locstyleId . '" data-srcattr="value" ' .
                        'data-target="' . $locstyleId . '" data-trgattr="value" class="formlink ui-corner-all">';
                    foreach ($locstyles AS $locstyle) {
                        $data .= '<option value="' . $locstyle['id'] . '">' . $locstyle['name'] . '</option>';
                    }
                    $data .=
                        '</select></div>';
                }
            }

            if ($forum['map_label'] == 'CUST') {
                $data .=
                    '<div class="c4gForumPostMapLocLabel">' .
                    '<div class="c4gForumColumn1">' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION_LABEL'] .
                    '</div>' .
                    '<input id="c4gForumPostMapEntryLabel" name="label" value="" ' .
                    'data-source="' . $label . '" data-srcattr="value" ' .
                    'data-target="' . $label . '" data-trgattr="value" type="text" class="formlink ui-corner-all" size="50" maxlength="100" />' .
                    '</div>';
            }

            if ($forum['map_tooltip'] == 'CUST') {
                $data .=
                    '<div class="c4gForumPostMapLocTooltip">' .
                    '<div class="c4gForumColumn1">' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION_TOOLTIP'] .
                    '</div>' .
                    '<input id="c4gForumPostMapEntryTooltip" name="tooltip" value="" ' .
                    'data-source="' . $tooltip . '" data-srcattr="value" ' .
                    'data-target="' . $tooltip . '" data-trgattr="value"  type="text" class="formlink ui-corner-all" size="50" maxlength="100" />' .
                    '</div>';
            }
            $data .=
                '</div>';

            $this->c4g_map_id                    = $forum['map_id'];
            C4GForumHelper::$postIdToIgnoreInMap = $postId;

            $mapData = MapDataConfigurator::prepareMapData($this, $this->Database);
            if ($forum['map_type'] == 'PICK') {
                // GEO Picker
                $mapData['geopicker'] = array
                (
                    'type' => 'frontend',
                    'input_geo_x' => '[name="geox"]',
                    'input_geo_y' => '[name="geoy"]'
                );

                $mapData['mapDiv'] = 'c4gForumPostMap';
                $mapData['addIdToDiv'] = false;
                $data .= '<div id="c4gForumPostMapGeocoding" class="c4gForumPostMapGeocoding"></div>';
                $data .= '<div id="c4gForumPostMap" class="c4gForumPostMap mod_c4g_maps"></div>';
            } else {
                // Feature Editor
                $mapData['editor']        = true;
                $mapData['editor_labels'] = $GLOBALS['TL_LANG']['c4g_maps']['editor_labels'];
                $mapData['editor_field']  = '#c4gForumPostMapEntryGeodata';
                $mapData['geosearch']['enable']   = true;

                $mapData['mapDiv'] = 'c4gForumPostMap';
                $mapData['addIdToDiv'] = false;
                $data .= '<div id="c4gForumPostMapGeosearch" class="c4gForumPostMapGeosearch"></div>';
                $data .= '<div id="c4gForumPostMap" class="c4gForumPostMap mod_c4g_maps"></div>';
            }

            if ($add) {
                $title = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ADD_MAP_LOCATION'];
            } else {
                $title = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['EDIT_MAP_LOCATION'];
            }

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "mapdata"       => $mapData,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" =>
                                                                          sprintf($title,
                                                                              ($forum['map_location_label'] ? $forum['map_location_label'] : $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION'])
                                                                          )
                                                                      ,
                                                                      "modal" => true
                                                                  )),
                "dialogid"      => 'postmapentry' . $forumId,
            );

            $return['dialogbuttons'][] = array(
                "action" => 'submit',
                "type"   => 'submit',
                "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SUBMIT']
            );
            if (!$add) {
                $return['dialogbuttons'][] =
                    array(
                        "action" => 'clear',
                        "type"   => 'submit',
                        "text"   =>
                            sprintf($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['DELETE_MAP_LOCATION'],
                                ($forum['map_location_label'] ? $forum['map_location_label'] : $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LOCATION'])
                            )
                    );

            }
            $return['dialogbuttons'][] = array(
                "action" => 'closedialog:postmapentry' . $forumId,
                "type"   => 'get',
                "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
            );

            return $return;

        }


        /**
         * @param $postId
         *
         * @return array
         */
        public function viewMapForPost($postId)
        {

            $posts = $this->helper->getPostFromDB($postId);
            $post  = $posts[0];

            $forum = $this->helper->getForumFromDB($post['forumid']);
            if ((!$this->map_enabled($post['forumid'])) ||
                (!$this->helper->checkPermissionForAction($post['forumid'], 'viewmapforpost', null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum))
            ) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }
            if ((!$forum['enable_maps']) && (!$forum['enable_maps_inherited'])) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ERROR_MAP_NOT_ACTIVE'];

                return $return;
            }

            $this->c4g_map_id = $forum['map_id'];
            $locations        = array();
            $locations[]      = $this->helper->getMapLocationForPost($post);

            $mapData = MapDataConfigurator::prepareMapData($this, $this->Database);
            if (($post['loc_geox'] != '') && ($post['loc_geoy'] != '')) {
                $mapData['calc_extent'] = 'CENTERZOOM';
                $mapData['center']['lon'] = $post['loc_geox'];
                $mapData['center']['lat'] = $post['loc_geoy'];
                $mapData['center']['zoom'] = 14;
            } else {
                // $mapData['calc_extent']    = 'ID';
                // $mapData['calc_extent_id'] = $locations[0]['id'];
            }
            $mapData['mapDiv'] = 'c4gForumPostMap';
            $mapData['addIdToDiv'] = false;
            $data           = '<div id="c4gForumPostMap" class="c4gForumPostMap mod_c4g_maps"></div>';

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "mapdata"       => $mapData,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['VIEW_MAP_FOR_POST'],
                                                                      "modal" => true
                                                                  )),
                "dialogid"      => 'viewmapforpost' . $postId,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $post['forumid'] . ";viewmapforpost:" . $postId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'closedialog:viewmapforpost' . $postId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CLOSE']
                    )
                ),
            );

            return $return;

        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function viewMapForForum($forumId)
        {

            $forum = $this->helper->getForumFromDB($forumId);
            if ((!$this->map_enabled($forumId)) ||
                (!$this->helper->checkPermissionForAction($forumId, 'viewmapforforum', null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum))
            ) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }
            if ((!$forum['enable_maps']) && (!$forum['enable_maps_inherited'])) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['ERROR_MAP_NOT_ACTIVE'];

                return $return;
            }

            $this->c4g_map_id = $forum['map_id'];
            $locations        = $this->helper->getMapLocationsForForum($forumId, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum);
            $mapData = MapDataConfigurator::prepareMapData($this, $this->Database);
            $mapData['mapDiv'] = 'c4gForumPostMap';
            $mapData['addIdToDiv'] = false;


            $data = '<div id="c4gForumPostMap" class="c4gForumPostMap mod_c4g_maps"></div>';

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "mapdata"       => $mapData,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['VIEW_MAP_FOR_FORUM'],
                                                                      "modal" => true
                                                                  )),
                "dialogid"      => 'viewmapforforum' . $forumId,
                "dialogstate"   => $this->c4g_forum_param_forum.':' . $forumId . ";viewmapforforum:" . $forumId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'closedialog:viewmapforforum' . $forumId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CLOSE']
                    )
                ),
            );

            return $return;

        }


        /**
         * Dialog for the global and temporary forumsearch
         *
         * @param unknown_type $forumId
         *
         * @return multitype:string multitype:multitype:string NULL   Ambigous <string, unknown, multitype:>
         */
        public function searchDialog($forumId)
        {

            $dialogId = 'search';

            //check permissions
            if (!$this->helper->checkPermissionForAction($forumId, 'search', null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                return $this->getPermissionDenied($this->helper->permissionError);
            }

            $c4g_forum_search_onlythreads = '';
            if ($this->c4g_forum_search_onlythreads) {
                $c4g_forum_search_onlythreads = '<input type="checkbox" id="onlyThreads" name="onlyThreads" class="formdata ui-corner-all" /><label for="onlyThreads" class="search-label">' . C4GForumHelper::getTypeText($this->c4g_forum_type,'SEARCHDIALOG_CB_ONLYTHREADS') . '</label><br/>';
            }

            $c4g_forum_search_wholewords = '';
            if ($this->c4g_forum_search_wholewords) {
                $c4g_forum_search_wholewords = '<input type="checkbox" id="wholeWords" name="wholeWords" class="formdata ui-corner-all" /><label for="wholeWords" class="search-label">' . C4GForumHelper::getTypeText($this->c4g_forum_type,'SEARCHDIALOG_CB_WHOLEWORDS') . '</label><br/>';
            }

            $divBegin = '';
            $divEnd   = '';
            if ($c4g_forum_search_onlythreads || $c4g_forum_search_wholewords) {
                $divBegin = '<div>';
                $divEnd   = '</div>';
            }

            //build dialog layout
            $data = '<div class="c4gForumSearch">' .
                    //start of upper-div
                    '<div>' .
                    $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_LBL_SEARCH_FOR'] . ':<br/>' .
                    '<input name="search" value="" type="text" class="formdata ui-corner-all" style="width:95%;"> </input> ' .
                    '<span onClick="return false" class="c4gGuiTooltip" style="text-decoration:none; cursor:help" title="' . nl2br(C4GUtils::secure_ugc($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH_HELPTEXT_SEARCHFIELD'])) . '">(?)</span>' .
                    '<br/> ' .
                    $divBegin .
                    $c4g_forum_search_onlythreads .
                    $c4g_forum_search_wholewords .
                    $divEnd;

            // show tag field in search form
            if ($this->c4g_forum_use_tags_in_search) {
                $aTags = $this->getTagForm("search_tags", array("forumid" => $forumId, "tags" => array()), $forumId, false, true);
                if (!empty($aTags)) {
                    $data .= '<br /><div>';
                    $data .= $aTags;
                    $data .= '</div><br />';
                }
            }

            if ($this->c4g_forum_search_forums) {
                $data .= '<br /> ' .
                    C4GForumHelper::getTypeText($this->c4g_forum_type, 'SEARCHDIALOG_LBL_SEARCH_ALL_THEMES') . ' ';
                $data .= $this->helper->getForumsAsHTMLDropdownMenuFromDB($this->c4g_forum_startforum, $forumId, ' - ');
            }

            if ($this->c4g_forum_search_displayonly) {
            $data .= ' <span onClick="return false" class="c4gGuiTooltip" style="text-decoration:none; cursor:help" title="' . nl2br(C4GUtils::secure_ugc($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH_HELPTEXT_AREA'])) . '">(?)</span>' .
                     '<br />' .
                     //end of upper-div
                     '</div>' .

                     '<br /><hr>' .

                     //start lower-div
                     '<div>' .
                     C4GForumHelper::getTypeText($this->c4g_forum_type,'SEARCHDIALOG_LBL_DISPLAY_ONLY') . ' <br />' .
                     '<input name="author" value="" type="text" class="formdata ui-corner-all" style="width:95%"></input> ' .
                     '<span onClick="return false" class="c4gGuiTooltip" style="text-decoration:none; cursor:help" title="' . nl2br(C4GUtils::secure_ugc($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH_HELPTEXT_AUTHOR'])) . '">(?)</span><br />' .
                     $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_LBL_AND_WHICH'] .
                     ' <div>' .
                     '<select name="dateRelation" class="formdata ui-corner-all">' .
                     '<option value="dateOfBirth">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_CREATIONDATE'] . '</option>' .
                     '<option value="dateOfLastPost">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_LASTPOST'] . '</option>' .
                     '</select> ' .
                     '<select name="timeDirection" class="formdata ui-corner-all">' .
                     '<option value=">">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_NOTPRIOR'] . '</option>' .
                     '<option value="<">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_PRIOR'] . '</option>' .
                     '</select> ' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_LBL_IS_THAN'] .
                     ' <input name="timePeriod" value="0" type="number" class="formdata ui-corner-all" style="width:50px;"></input> ' .
                     '<select name="timeUnit" class="formdata ui-corner-all">' .
                     '<option value="hour">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_HOUR'] . '</option>' .
                     '<option selected value="day">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_DAY'] . '</option>' .
                     '<option value="week">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_WEEK'] . '</option>' .
                     '<option value="month">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_MONTH'] . '</option>' .
                     '<option value="year">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHDIALOG_DDL_YEAR'] . '</option>' .
                     '</select> ' .
                     '<span onClick="return false" class="c4gGuiTooltip" style="text-decoration:none; cursor:help" title="' . nl2br(C4GUtils::secure_ugc($GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH_HELPTEXT_TIMEPERIOD'])) . '">(?)</span>' .
                     '</div>' .
                     //end of lower-div
                     '</div>' .

                     '</div>' .
                     '<br/>';
            }

            $forum = $this->helper->getForumFromDB($forumId);

            if ($forum['subforums'] > 0) {
                $action = $this->c4g_forum_param_forumbox.':' . $forumId;
            } else {
                $action = $this->c4g_forum_param_forum.':' . $forumId;
            }

            $return = array(
                "dialogtype"    => "html",
                "dialogdata"    => $data,
                "dialogoptions" => $this->addDefaultDialogOptions(array(
                                                                      "title"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'SEARCHDIALOG_HEADLINE'),
                                                                      "modal"  => true,
                                                                      "width"  => 470,
                                                                      "height" => 325
                                                                  )),
                "dialogid"      => $dialogId,
                "dialogstate"   => $action . ";searchDialog:" . $forumId,
                "dialogbuttons" => array(
                    array(
                        "action" => 'search:' . $forumId,
                        'class'  => 'c4gGuiDefaultAction',
                        "type"   => 'send',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH']
                    ),
                    array(
                        "action" => 'closedialog:' . $dialogId,
                        "type"   => 'get',
                        "text"   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CANCEL']
                    )
                ),
            );

            return $return;

        }


        /**
         *
         * @param int   $forumId
         * @param array $searchParam
         *
         * @return unknown|multitype:string multitype:number boolean  multitype:multitype:string NULL   Ambigous <multitype:boolean string multitype:multitype:number string   multitype:NULL string multitype:string   multitype:multitype:string boolean multitype:number   multitype:NULL string multitype:number   multitype:NULL boolean number multitype:number   multitype:NULL boolean number multitype:number  multitype:string   multitype:boolean multitype:number   multitype:string boolean multitype:string  multitype:number   multitype:NULL boolean multitype:number   multitype:NULL string multitype:number  multitype:multitype:number string     , multitype:number string unknown NULL >
         */
        public function search($forumId, $searchParam)
        {

            list($access, $message) = $this->checkPermissionForAction($forumId, 'search', null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }

            //prompt a message if search-field is empty
            if (!$this->putVars['search'] && !$this->putVars['tags']) {
                $return['usermessage'] = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH_MESSAGE_NO_SEARCH_ENTRY'];

                return $return;
            }

            //save parameters for the resultevaluation
            // TODO delete This!!! This doesn't work!
            // store search in Session!
            $GLOBALS['c4gForumSearchParamCache'] = $searchParam;

            //prepare all given information-data
            //searchLocation
            if ($this->c4g_forum_search_forums == "1") {
                $searchLocations               = array($searchParam['searchLocation']);
                $searchLocations               = array_merge($searchLocations, $this->helper->getForumsIdsFromDB($searchParam['searchLocation'], true));
                $searchParam['searchLocation'] = implode(", ", $searchLocations);
            }

            //search
            $threads = array();
            $threads = array_merge($threads, $this->helper->searchSpecificThreadsFromDB($searchParam));


            /*************************************************************************************************************************************\
             * building datatable
             *****************************************************************************************************************************************/
            $data                 = array();


            $data['aoColumnDefs'] = array(
                array(
                    'sTitle'      => 'key',
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(0),
                    "responsivePriority"    => array(0),
                ),
                array(
                    'sTitle'                => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD'),
                    "sClass"                => 'c4g_forum_searchres_threadname',
                    "sWidth"                => '30%',
                    "aDataSort"             => array(
                        10,
                        1
                    ),
                    "aTargets"              => array(1),
                    "aaResponsivePriority"   => array(1),
                    "c4gMinTableSizeWidths" => array(
                        array(
                            "tsize" => 500,
                            "width" => '50%'
                        ),
                        array(
                            "tsize" => 0,
                            "width" => ''
                        )
                    )
                ),
                array(
                    'sTitle'   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_DATATABLE_AREA'],
                    "sClass"   => 'c4g_forum_searchres_area',
                    "sWidth"   => '20%',
                    "aTargets" => array(2),
                    "aaResponsivePriority" => array(2),

                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LAST_AUTHOR_SHORT'],
                    "sClass"          => 'c4g_forum_searchres_last_author',
                    "aDataSort"       => array(
                        10,
                        3,
                        5
                    ),
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_lastperson != '1'),
                    "aTargets"        => array(3),
                    "responsivePriority"    => array(3),
                    "c4gMinTableSize" => 700
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LAST_POST_SHORT'],
                    "sClass"          => 'c4g_forum_searchres_last_post',
                    "aDataSort"       => array(
                        11,
                        5
                    ),
                    "sType"           => 'de_datetime',
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_lastdate != '1'),
                    "asSorting"       => array(
                        'desc',
                        'asc'
                    ),
                    "aTargets"        => array(4),
                    "responsivePriority"    => array(4),
                    "c4gMinTableSize" => 700
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(5),
                    "responsivePriority"    => array(5)
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['AUTHOR'],
                    "sClass"          => 'c4g_forum_searchres_author',
                    "aDataSort"       => array(
                        10,
                        6,
                        8
                    ),
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_createperson != '1'),
                    "aTargets"        => array(6),
                    "responsivePriority"    => array(6),
                    "c4gMinTableSize" => 500
                ),
                array(
                    'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CREATED_ON'],
                    "sClass"          => 'c4g_forum_searchres_created',
                    "aDataSort"       => array(
                        11,
                        8
                    ),
                    "asSorting"       => array(
                        'desc',
                        'asc'
                    ),
                    "bSearchable"     => false,
                    "bVisible"        => ($this->c4g_forum_remove_createdate != '1'),
                    "aTargets"        => array(7),
                    "responsivePriority"    => array(7),
                    "c4gMinTableSize" => 500
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(8),
                    "responsivePriority"    => array(8),
                ),
                array(
                    'sTitle'      => '#',
                    "sClass"      => 'c4g_forum_searchres_postcount',
                    "asSorting"   => array(
                        'desc',
                        'asc'
                    ),
                    "bSearchable" => false,
                    "bVisible"        => ($this->c4g_forum_remove_count != '1'),
                    "aTargets"    => array(9),
                    "responsivePriority"    => array(9),
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(10),
                    "responsivePriority"    => array(10),
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(11),
                    "responsivePriority"    => array(11),
                ),
                array(
                    'sTitle'      => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_DATATABLE_HITS'],
                    "sClass"      => 'c4g_forum_searchres_hits',
                    "bVisible"    => true,
                    "bSearchable" => false,
                    "aTargets"    => array(12),
                    "responsivePriority"    => array(12),
                ),
                array(
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(13),
                    "responsivePriority"    => array(13),
                ),
            );


            if ($this->c4g_forum_rating_enabled) {

                foreach($data['aoColumnDefs'] as $key => $item){
                    if($key > 0){
                        if(isset($data['aoColumnDefs'][$key]['aTargets'])) {
                            if(is_array($data['aoColumnDefs'][$key]['aTargets'])) {
                                foreach ($data['aoColumnDefs'][$key]['aTargets'] as $i => $val) {
                                    $data['aoColumnDefs'][$key]['aTargets'][$i] += 1;
                                    $data['aoColumnDefs'][$key]['responsivePriority'][$i] += 1;
                                }
                            }
                        }
                    }
                }

                array_insert($data['aoColumnDefs'], 1, array(
                                                      array(
                                                          'sTitle'                => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATING'],
                                                          "sWidth"                => '10%',
                                                          "aDataSort"             => array(1),
                                                          "aTargets"              => array(1),
                                                          "responsivePriority"    => array(1),
                                                          "c4gMinTableSizeWidths" => array(
                                                              array(
                                                                  "tsize" => 200,
                                                                  "width" => '25%'
                                                              ),
                                                              array(
                                                                  "tsize" => 200,
                                                                  "width" => '25%'
                                                              )
                                                          )
                                                      )
                                                  )
                );

            }



            if ($this->c4g_forum_table_jqui_layout) {
                $data['bJQueryUI'] = true;
            }

            $data['aaSorting']       = array(
                array(
                    12,
                    'desc'
                )
            );
            $data['responsive']      = true;
            $data['bScrollCollapse'] = true;
            $data['bStateSave']      = true;
            $data['sPaginationType'] = 'full_numbers';
            $data['oLanguage']       = array(
                "oPaginate"      => array(
                    "sFirst"    => '<<',
                    "sLast"     => '>>',
                    "sPrevious" => '<',
                    "sNext"     => '>'
                ),
                "sEmptyTable"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_EMPTY'),
                "sInfo"          => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_INFO'),
                "sInfoEmpty"     => "-",
                "sInfoFiltered"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_FILTERED'),
                "sInfoThousands" => '.',
                "sLengthMenu"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_LENGTHMENU'),
                "iDisplayLength" => 25,
                "bLengthChange"  => true,
                "sProcessing"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_PROCESSING'),
                "sSearch"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_SEARCH'),
                "sZeroRecords"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_NOTFOUND')
            );

            foreach ($threads as $thread) {
                switch ($this->c4g_forum_threadclick) {
                    case 'LPOST':
                        $threadAction = 'readlastpost:' . $thread['id'];
                        break;

                    case 'FPOST':
                        $threadAction = 'readpostnumber:' . $thread['id'] . ':1';
                        break;

                    default:
                        $threadAction = 'readthread:' . $thread['id'];
                        break;
                }
                if ($thread['lastPost']) {
                    $lastPost     = $thread['lastPost'];
                    $lastUsername = $thread['lastUsername'];
                } else {
                    $lastPost     = $thread['creation'];
                    $lastUsername = $thread['username'];
                }

                if ($thread['threaddesc']) {
                    $tooltip = $thread['threaddesc'];
                } else {
                    //$tooltip = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['THREADS_NODESC'];
                    $tooltip = $this->helper->getFirstPostLimitedTextOfThreadFromDB($thread['id'], 250);
                    $tooltip = preg_replace('/\[[^\[\]]*\]/i', '', $tooltip);
                }
                if (strlen($tooltip) >= 245) {
                    $tooltip = substr($tooltip, 0, strrpos($tooltip, ' '));
                    $tooltip .= ' [...]';
                }

                if ($this->c4g_forum_multilingual) {
                    $threadname = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $thread['name']);
                } else {
                    $threadname = $thread['name'];
                }


                $aaData = array(
                    $threadAction,
                    $this->helper->checkThreadname($threadname),
                    $this->helper->getForumNameForThread($thread['id'], $this->c4g_forum_language_temp),
                    $lastUsername,
                    $this->helper->getDateTimeString($lastPost),
                    $lastPost,
                    // hidden column for sorting
                    $thread['username'],
                    $this->helper->getDateTimeString($thread['creation']),
                    $thread['creation'],
                    // hidden column for sorting
                    $thread['posts'],
                    $thread['sort'],
                    // hidden column for sorting
                    999 - $thread['sort'],
                    // hidden column for sorting
                    $thread['hits'],
                    $tooltip
                );    // hidden column for tooltip



                if ($this->c4g_forum_rating_enabled) {
                    $aRating  = $this->getRating4Thread($thread,true);
                    $rating = $aRating['rating'];
                    $sRating = "";
                    if (!empty($rating)) {
                        $sRating = '
                                <div class="rating_wrapper">
                                    <fieldset class="rating_static">
                                        <span id="staticStar5" ' . (($rating == "5") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar5" title="5 stars"></label>
                                        <span id="staticStar45" ' . (($rating == "4.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar45" title="4.5 stars"></label>
                                        <span id="staticStar4" ' . (($rating == "4") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar4" title="4 stars"></label>
                                        <span id="staticStar35" ' . (($rating == "3.5") ? " class=\"checked\"" : "") . ' ></span><label class="half" for="staticStar35" title="3.5 stars"></label>
                                        <span id="staticStar3" ' . (($rating == "3") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar3" title="3 stars"></label>
                                        <span id="staticStar25" ' . (($rating == "2.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar25" title="2.5 stars"></label>
                                        <span id="staticStar2" ' . (($rating == "2") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar2" title="2 stars"></label>
                                        <span id="staticStar15" ' . (($rating == "1.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar15" title="1.5 stars"></label>
                                        <span id="staticStar1" ' . (($rating == "1") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar1" title="1 stars"></label>
                                        <span id="staticStar05" ' . (($rating == "0.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar05" title="0.5 stars"></label>
                                    </fieldset><span class="score">&#216 ' . $rating . '&nbsp;&nbsp;('.$aRating['overall'].' '.(($aRating['overall'] > 1)?$GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATINGS_MULTIPLE']:$GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATINGS_SINGLE']).')</spa>
                                </div>';
                    }
                    array_insert($aaData, 1, $sRating);
                }



                $data['aaData'][] = $aaData;
            }

            $forum = $this->helper->getForumFromDB($forumId);

            if ($forum['subforums'] > 0) {
                $action = $this->c4g_forum_param_forumbox.':' . $forumId;
            } else {
                $action = $this->c4g_forum_param_forum.':' . $forumId;
            }

            $tooltipcol = 13;
            if ($this->c4g_forum_rating_enabled) {
                $tooltipcol = 14;
            }

            $return = array(
                "dialogclose"    => "search",
                "contenttype"    => "datatable",
                "contentdata"    => $data,
                "contentoptions" => array(
                    "actioncol"     => 0,
                    "tooltipcol"    => $tooltipcol,
                    "selectOnHover" => true,
                    "clickAction"   => true
                ),
                "state"          => $action . ";searchDialog:" . $forumId,
                "headline"       => '<div class="ui-widget-header search-results">' . $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_HEADLINE'] . '</div>' .
                                    '<div class="ui-widget-content search-results">' . $GLOBALS['c4gForumSearchParamCache']['search'] . ' </div>',
                "buttons"        => array(
                    array(
                        "id"   => 'searchDialog:' . $forumId,
                        "text" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_BUTTON_START_NEW_SEARCH']
                    )
                )
            );

            return $return;
        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function getThreadlist($forumId)
        {

            list($access, $message) = $this->checkPermissionForAction($forumId, 'latestthreads',null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum);
            if (!$access) {
                return $this->getPermissionDenied($message);
            }


            //search
            $threads = $this->helper->getThreadsFromDBWithSubforums($forumId);


            /** *************************************************************************************************************************************\
             * |* building datatable
             * \****************************************************************************************************************************************/
            $data                 = array();
            $data['aoColumnDefs'] = array(
                array(
                    'sTitle'      => 'key',
                    "bVisible"    => false,
                    "bSearchable" => false,
                    "aTargets"    => array(0),
                    "responsivePriority"    => array(0)
                )
            );

            $data['aoColumnDefs'][] = array(
                'sTitle'                => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREAD'),
                "sWidth"                => '30%',
                "aDataSort"             => array(1),
                "aTargets"              => array(1),
                "responsivePriority"    => array(1),
                "c4gMinTableSizeWidths" => array(
                    array(
                        "tsize" => 500,
                        "width" => '50%'
                    ),
                    array(
                        "tsize" => 0,
                        "width" => ''
                    )
                )
            );
            $data['aoColumnDefs'][] = array(
                'sTitle'   => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCHRESULTPAGE_DATATABLE_AREA'],
                "sWidth"   => '20%',
                "aTargets" => array(2),
                "responsivePriority" => array(2),
            );
            $data['aoColumnDefs'][] = array(
                'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LAST_AUTHOR_SHORT'],
                "aDataSort"       => array(
                    3,
                    5
                ),
                "bSearchable"     => false,
                "bVisible"        => ($this->c4g_forum_remove_lastperson != '1'),
                "aTargets"        => array(3),
                "responsivePriority"    => array(3),
                "c4gMinTableSize" => 700
            );
            $data['aoColumnDefs'][] = array(
                'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LAST_POST_SHORT'],
                "aDataSort"       => array(5),
                "bSearchable"     => false,
                "bVisible"        => ($this->c4g_forum_remove_lastdate != '1'),
                "asSorting"       => array(
                    'desc',
                    'asc'
                ),
                "sType"           => 'de_datetime',
                "aTargets"        => array(4),
                "responsivePriority"    => array(4),
                "c4gMinTableSize" => 700
            );
            $data['aoColumnDefs'][] = array(
                "bVisible"    => false,
                "bSearchable" => false,
                "aTargets"    => array(5),
                "responsivePriority"    => array(5),
            );
            $data['aoColumnDefs'][] = array(
                'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['AUTHOR'],
                "aDataSort"       => array(
                    6,
                    8
                ),
                "bSearchable"     => false,
                "bVisible"        => ($this->c4g_forum_remove_createperson != '1'),
                "aTargets"        => array(6),
                "responsivePriority"    => array(6),
                "c4gMinTableSize" => 500
            );
            $data['aoColumnDefs'][] = array(
                'sTitle'          => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['CREATED_ON'],
                "aDataSort"       => array(8),
                "asSorting"       => array(
                    'desc',
                    'asc'
                ),
                "sType"           => 'de_datetime',
                "bSearchable"     => false,
                "bVisible"        => ($this->c4g_forum_remove_createdate != '1'),
                "aTargets"        => array(7),
                "responsivePriority"    => array(7),
                "c4gMinTableSize" => 500
            );
            $data['aoColumnDefs'][] = array(
                "bVisible"    => false,
                "bSearchable" => false,
                "aTargets"    => array(8),
                "responsivePriority"    => array(8),
            );
            $data['aoColumnDefs'][] = array(
                'sTitle'      => '#',
                "asSorting"   => array(
                    'desc',
                    'asc'
                ),
                "bSearchable" => false,
                "bVisible"        => ($this->c4g_forum_remove_count != '1'),
                "aTargets"    => array(9),
                "responsivePriority"    => array(9),
            );
            $data['aoColumnDefs'][] = array(
                "bVisible"    => false,
                "bSearchable" => false,
                "aTargets"    => array(10),
                "responsivePriority"    => array(10),
            );

            if ($this->c4g_forum_rating_enabled) {
                $data['aoColumnDefs'][] = array(
                    'sTitle'                => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['RATING'],
                    "sWidth"                => '10%',
                    "aDataSort"             => array(1),
                    "aTargets"              => array(11),
                    "responsivePriority"    => array(11),
                    "c4gMinTableSizeWidths" => array(
                        array(
                            "tsize" => 500,
                            "width" => '50%'
                        ),
                        array(
                            "tsize" => 0,
                            "width" => ''
                        )
                    )
                );
            }


            if ($this->c4g_forum_table_jqui_layout) {
                $data['bJQueryUI'] = true;
            }

            $data['aaSorting']       = array(
                array(
                    4,
                    'desc'
                )
            );
            $data['responsive'] = true;
            $data['bScrollCollapse'] = true;
            $data['bStateSave']      = true;
            $data['sPaginationType'] = 'full_numbers';
            $data['oLanguage']       = array(
                "oPaginate"      => array(
                    "sFirst"    => '<<',
                    "sLast"     => '>>',
                    "sPrevious" => '<',
                    "sNext"     => '>'
                ),
                "sEmptyTable"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_EMPTY'),
                "sInfo"          => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_INFO'),
                "sInfoEmpty"     => "-",
                "sInfoFiltered"  => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_FILTERED'),
                "sInfoThousands" => '.',
                "sLengthMenu"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_LENGTHMENU'),
                "iDisplayLength" => 25,
                "bLengthChange"  => true,
                "sProcessing"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_PROCESSING'),
                "sSearch"    => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_SEARCH'),
                "sZeroRecords"   => C4GForumHelper::getTypeText($this->c4g_forum_type,'THREADS_NOTFOUND')
            );

            foreach ($threads as $thread) {
                switch ($this->c4g_forum_threadclick) {
                    case 'LPOST':
                        $threadAction = 'readlastpost:' . $thread['id'];
                        break;

                    case 'FPOST':
                        $threadAction = 'readpostnumber:' . $thread['id'] . ':1';
                        break;

                    default:
                        $threadAction = 'readthread:' . $thread['id'];
                        break;
                }
                if ($thread['lastPost']) {
                    $lastPost     = $thread['lastPost'];
                    $lastUsername = $thread['lastUsername'];
                } else {
                    $lastPost     = $thread['creation'];
                    $lastUsername = $thread['username'];
                }
                if ($thread['threaddesc']) {
                    $tooltip = $thread['threaddesc'];
                } else {
                    //$tooltip = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['THREADS_NODESC'];
                    $tooltip = $this->helper->getFirstPostLimitedTextOfThreadFromDB($thread['id'], 250);
                    $tooltip = preg_replace('/\[[^\[\]]*\]/i', '', $tooltip);
                }
                if (strlen($tooltip) >= 245) {
                    $tooltip = substr($tooltip, 0, strrpos($tooltip, ' '));
                    $tooltip .= ' [...]';
                }

                if ($this->c4g_forum_multilingual) {
                    $threadname = $this->helper->translateThreadField($thread['id'], 'name', $this->c4g_forum_language_temp, $thread['name']);
                } else {
                    $threadname = $thread['name'];
                }

                $aaData = array(
                    $threadAction,
                    $this->helper->checkThreadname($threadname),
                    $this->helper->getForumNameForThread($thread['id'], $this->c4g_forum_language_temp),
                    $lastUsername,
                    $this->helper->getDateTimeString($lastPost),
                    $lastPost,
                    // hidden column for sorting
                    $thread['username'],
                    $this->helper->getDateTimeString($thread['creation']),
                    $thread['creation'],
                    // hidden column for sorting
                    $thread['posts'],
                    $tooltip
                );    // hidden column for tooltip


                if ($this->c4g_forum_rating_enabled) {


                    $rating  = $this->getRating4Thread($thread);
                    $sRating = "";
                    if (!empty($rating)) {
                        $sRating = '
                                <div class="rating_wrapper">
                                    <fieldset class="rating_static">
                                        <span id="staticStar5" ' . (($rating == "5") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar5" title="5 stars"></label>
                                        <span id="staticStar45" ' . (($rating == "4.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar45" title="4.5 stars"></label>
                                        <span id="staticStar4" ' . (($rating == "4") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar4" title="4 stars"></label>
                                        <span id="staticStar35" ' . (($rating == "3.5") ? " class=\"checked\"" : "") . ' ></span><label class="half" for="staticStar35" title="3.5 stars"></label>
                                        <span id="staticStar3" ' . (($rating == "3") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar3" title="3 stars"></label>
                                        <span id="staticStar25" ' . (($rating == "2.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar25" title="2.5 stars"></label>
                                        <span id="staticStar2" ' . (($rating == "2") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar2" title="2 stars"></label>
                                        <span id="staticStar15" ' . (($rating == "1.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar15" title="1.5 stars"></label>
                                        <span id="staticStar1" ' . (($rating == "1") ? " class=\"checked\"" : "") . '></span><label class="full" for="staticStar1" title="1 stars"></label>
                                        <span id="staticStar05" ' . (($rating == "0.5") ? " class=\"checked\"" : "") . '></span><label class="half" for="staticStar05" title="0.5 stars"></label>
                                    </fieldset><span class="score">&#216 (' . $rating . ')</spa>
                                </div>';

                    }


                    $aaData[] = $sRating;
                }

                $data['aaData'][] = $aaData;
            }


            $forum = $this->helper->getForumFromDB($forumId);

            if ($forum['subforums'] > 0) {
                $action = $this->c4g_forum_param_forumbox.':' . $forumId;
            } else {
                $action = $this->c4g_forum_param_forum.':' . $forumId;
            }

            $tooltipcol = 10;
            if ($this->c4g_forum_rating_enabled) {
                $tooltipcol = 11;
            }

            $return = array(
                "dialogclose"    => "search",
                "contenttype"    => "datatable",
                "contentdata"    => $data,
                "contentoptions" => array(
                    "actioncol"     => 0,
                    "tooltipcol"    => $tooltipcol,
                    "selectOnHover" => true,
                    "clickAction"   => true
                ),
                "state"          => $action . ";threadlist:" . $forumId,
                "headline"       => '<div class="ui-widget-header ui-corner-all c4g_forum_header_center">' . C4GForumHelper::getTypeText($this->c4g_forum_type,'LATESTTHREADS_HEADLINE') . '</div>',
                "buttons"        => array()
                //array(array( id=>'threadlist:'.$forumId, text=>$GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['LATESTTHREADS']))
            );

            return $return;
        }


        /**
         * @param $buttons
         * @param $forumId
         *
         * @return array
         */
        public function addDefaultButtons($buttons, $forumId)
        {

            //$buttons[] = array( id=>'search', text=>$GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH']);
            //if ($this->c4g_forum_comf_navigation=='BOXES') {
            //	$buttons[] = array( id=>'recalculate', text=>'Neuberechnung (Debug)');
            //}
            if ($this->helper->checkPermissionForAction($forumId, 'search',null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum)) {
                $buttons[] = array(
                    "id"   => 'searchDialog:' . $forumId,
                    "text" => $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['SEARCH']
                );
            }

            if ($this->helper->checkPermissionForAction($forumId, 'latestthreads',null, $this->c4g_forum_param_forumbox, $this->c4g_forum_param_forum) && ($this->action == "forumbox")) {
                $buttons[] = array(
                    "id"   => 'threadlist:' . $forumId,
                    "text" => C4GForumHelper::getTypeText($this->c4g_forum_type,'LATESTTHREADS')
                );
            }

            return $buttons;
        }


        /**
         * @param $forumId
         *
         * @return array
         */
        public function getBreadcrumb($forumId)
        {

            if (($this->c4g_forum_navigation == 'TREE') || (!$this->c4g_forum_breadcrumb)) {
                return array();
            }
            $path = $this->helper->getForumPath($forumId, $this->c4g_forum_startforum);

            $data = array();
            foreach ($path as $value) {
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
                $pathname = $this->repInsertTags($pathname);
                if (($value['use_intropage']) && (!$this->c4g_forum_hide_intropages)) {
                    $data[] = array(
                        "id"   => 'forumintro:' . $value['id'],
                        "text" => $pathname
                    );
                } else {
                    if ($value['subforums'] == 0) {
                        $data[] = array(
                            "id"   => $this->c4g_forum_param_forum.':' . $value['id'],
                            "text" => $pathname
                        );
                    } else {
                        $data[] = array(
                            "id"   => $this->c4g_forum_param_forumbox.':' . $value['id'],
                            "text" => $pathname
                        );
                    }
                }
            }

            return $data;
        }


        /**
         * @param $headline
         *
         * @return string
         */
        public function getHeadline($headline)
        {

            $headline = deserialize($headline);
            if (($headline) && ($headline['value'] != '')) {
                $unit = $headline['unit'];

                return '<' . $unit . '>' . $headline['value'] . '</' . $unit . '>';
            } else {
                return '';
            }
        }


        /**
         * @param $forum
         *
         * @return string
         */
        public function getForumLink($forum)
        {

            return C4GUtils::addParametersToURL(
                $this->replaceInsertTags($forum['linkurl']),
                array(
                    'c4g_forum_fmd'   => $this->id,
                    'c4g_forum_forum' => $forum['id']
                ));
        }


        /**
         * @param $e
         *
         * @return mixed
         */
        public function showException($e)
        {

            $message = $GLOBALS['TL_LANG']['C4G_FORUM']['DISCUSSION']['PHP_ERROR'];
            if ($GLOBALS['TL_CONFIG']['displayErrors']) {
                $message .= ' Message: ' . $e->getMessage();
            }
            $return ['usermessage'] = $message;
            try {
                if ($GLOBALS['TL_CONFIG']['logErrors']) {
                    log_message($e->getMessage() . ' File: ' . $e->getFile() . ' Line ' . $e->getLine() . ' (Code: ' . $e->getCode() . ')');
                    $this->log('C4G-Forum PHP-Error: ' . $e->getMessage(), $e->getFile() . ' Line ' . $e->getLine() . ' (Code: ' . $e->getCode() . ')', TL_ERROR);
                }
            } catch (Exception $exc) {
            }

            return $return;
        }


        /**
         * @param $action
         *
         * @return array
         */
        public function performAction($action)
        {
            $this->setTempLanguage();
            //delete cache -- Übergangslösung bis alles läuft.
//            \c4g\Core\C4GAutomator::purgeApiCache();

            $values       = explode(':', $action, 5);
            $this->action = $values[0];
            switch ($values[0]) {
                case 'forumtree':
                    $return = $this->generateForumTree();
                    break;
                case $this->c4g_forum_param_forumbox:
                    $return = $this->getForumInBoxes($values[1],true);
                    if(count($return) <= 0){
                        $return = $this->getForumInTable($values[1], $values[2]);
                    }
                    break;
                case 'forumintro':
                    $return = $this->getForumintro($values[1]);
                    break;
                case $this->c4g_forum_param_forum:
                    $return = $this->getForumInTable($values[1], $values[2]);
                    break;
                case 'readthread':
                    $return = $this->getThreadAsHtml($values[1]);
                    break;
                case 'readpost':
                    $return = $this->getPostAsHtml($values[1]);
                    break;
                case 'readlastpost':
                    $return = $this->getLastPostOfThreadAsHtml($values[1]);
                    break;
                case 'readpostnumber':
                    $return = $this->getPostNumberOfThreadAsHtml($values[1], $values[2]);
//                    $return = $this->getPostAsHtml($values[1]);
                    break;
                case 'newpost':
                    $return = $this->generateNewPostForm($values[1], $values[2]);
                    break;
                case 'newthread':
                    $return = $this->generateNewThreadForm($values[1]);
                    break;
                case 'sendpost':
                    $return = $this->sendPost($values[1]);
                    break;

                case 'previewpost':
                    $return = $this->previewPost($values[1], C4GForumHelper::getTypeText($this->c4g_forum_type,'NEW_POST_PREVIEW'));
                    break;
                case 'cancelpost':
                    $return = $this->cancelPost($values[1], $values[2]);
                    break;
                case 'sendthread':
                    $return = $this->sendThread($values[1]);
                    break;
                case 'previewthread':
                    $return = $this->previewThread($values[1]);
                    break;
                case 'cancelthread':
                    $return = $this->cancelThread($values[1]);
                    break;
                case 'closedialog':
                    $return = $this->closeDialog($values[1], $values[2]);
                    break;
                case 'usedialog':
                    $return = $this->useDialog($values[1]);
                    break;
                case 'delthreaddialog':
                    $return = $this->delThreadDialog($values[1]);
                    break;
                case 'delthread':
                    $return = $this->delThread($values[1]);
                    break;
                case 'movethreaddialog':
                    $return = $this->moveThreadDialog($values[1]);
                    break;
                case 'movethread':
                    $return = $this->moveThread($values[1], $this->putVars[$this->c4g_forum_param_forum]);
                    break;
                case 'editownthreaddialog':
                case 'editthreaddialog':
                    $return = $this->editThreadDialog($values[1]);
                    break;
                case 'editthread':
                    $return = $this->editThread($values[1]);
                    break;
                case 'delownpostdialog':
                case 'delpostdialog':
                    $return = $this->delPostDialog($values[1]);
                    break;
                case 'delpost':
                    $return = $this->delPost($values[1]);
                    break;
                case 'editownpostdialog':
                case 'editpostdialog':
                    $return = $this->editPostDialog($values[1]);
                    break;
                case 'previeweditpost':
                case 'previeweditownpost':
                    $return = $this->previewEditPost($values[1]);
                    break;
                case 'editpost':
                    $return = $this->editPost($values[1]);
                    break;
                case 'postlink':
                    $return = $this->postLink($values[1], $values[2]);
                    break;
                case 'postmapentry':
                    $return = $this->postMapEntry($values[1], $values[2], $values[3], $values[4]);
                    break;
                case 'addmemberdialog':
                    $return = $this->addMemberDialog($values[1]);
                    break;
                case 'addmember':
                    $return = $this->addMember($values[1]);
                    break;
                case 'recalculate':
                    $this->helper->recalculateHelperData();
                    $return = $this->getForumInBoxes($this->c4g_forum_startforum);
                    break;
                case 'subscribethreaddialog':
                    $return = $this->subscribeThreadDialog($values[1]);
                    break;
                case 'subscribethread':
                    $return = $this->subscribeThread($values[1], $values[2]);
                    break;
                case 'subscribesubforumdialog':
                    $return = $this->subscribeSubforumDialog($values[1]);
                    break;
                case 'subscribesubforum':
                    $return = $this->subscribeSubforum($values[1], $values[2], $this->putVars['subscription_only_threads']);
                    break;
                case 'unsubscribethread':
                    $return = $this->unsubscribeLinkThread($values[1]);
                    break;
                case 'unsubscribesubforum':
                    $return = $this->unsubscribeLinkSubforum($values[1]);
                    break;
                case 'unsubscribeall':
                    $return = $this->unsubscribeLinkAll($values[1]);
                    break;
                case 'viewmapforpost':
                    $return = $this->viewMapForPost($values[1]);
                    break;
                case 'viewmapforforum':
                    $return = $this->viewMapForForum($values[1]);
                    break;
                case 'cron':
                    $this->helper->performCron($values[1]);
                    break;
                case 'searchDialog':
                    $return = $this->searchDialog($values[1]);
                    break;
                case 'search':
                    if (isset($values[2])) {
                        $return = $this->search($values[1], $values[2]);
                    } else {
                        if (!isset($this->putVars['tags'])) {
                            $this->putVars['tags'] = array();
                        }
                        $varArr = array();
                        $varArr["search"] = $this->putVars['search'];

                        $varArr["searchLocation"] = $values[1];
                        if ($this->c4g_forum_search_forums == "1") {
                            $varArr["searchLocation"] = $this->putVars['searchLocation'];
                        }

                        $varArr["searchOnlyThreads"] = 'false';
                        if ($this->c4g_forum_search_onlythreads == "1") {
                            $varArr["searchOnlyThreads"] = $this->putVars['onlyThreads'];
                        }

                        $varArr["searchWholeWords"] = 'false';
                        if ($this->c4g_forum_search_wholewords == "1") {
                            $varArr["searchWholeWords"] = $this->putVars['wholeWords'];
                        }

                        $varArr["tags"] = '';
                        $varArr["onlyTags"] = 'false';
                        if ($this->c4g_forum_use_tags_in_search == "1") {
                            $varArr["tags"] = $this->putVars['tags'];
                            $varArr["onlyTags"] = $this->putVars['onlyTags'];
                        }

                        $varArr["author"] = '';
                        $varArr["dateRelation"] = '';
                        $varArr["timeDirection"] = '';
                        $varArr["timePeriod"] = '';
                        $varArr["timeUnit"] = '';
                        if ($this->c4g_forum_search_displayonly == "1") {
                            $varArr["author"]            = $this->putVars['author'];
                            $varArr["dateRelation"]      = $this->putVars['dateRelation'];
                            $varArr["timeDirection"]     = $this->putVars['timeDirection'];
                            $varArr["timePeriod"]        = $this->putVars['timePeriod'];
                            $varArr["timeUnit"]          = $this->putVars['timeUnit'];
                        }

                        $return = $this->search($values[1],$varArr);
                    }
                    break;
                case 'threadlist':
                    $return = $this->getThreadlist($values[1]);
                    break;
                case 'ticketcall':
                    $return = $this->ticket($values[1],$values[2],$values[3],$values[4]);
                    break;
                case 'closethread':
                    $return = $this->closethread($values[1]);
                    break;
                default:
                    break;
            }
            // HOOK: for enhancements to change the result
            if (isset($GLOBALS['TL_HOOKS']['C4gForumAfterAction']) && is_array($GLOBALS['TL_HOOKS']['C4gForumAfterAction'])) {
                foreach ($GLOBALS['TL_HOOKS']['C4gForumAfterAction'] as $callback) {
                    $this->import($callback[0]);
                    $return = $this->$callback[0]->$callback[1]($return, $this, $this->helper, $this->putVars, $values[0], $values[1], $values[2], $values[3]);
                }
            }

            if (isset($return)) {
                return $return;
            } else {
                return;
            }

        }


        /**
         * @param $historyAction
         *
         * @return array
         */
        public function performHistoryAction($historyAction)
        {

            $values       = explode(':', $historyAction);
            $this->action = $values[0];
            switch ($values[0]) {
                case $this->c4g_forum_param_forum:
                    $result = $this->getForumInTable($values[1], true);
                    break;
                default:
                    $result = $this->performAction($historyAction);
            }

            // close all dialogs that have been open to avoid conflicts
            $result['dialogcloseall'] = true;

            return $result;

        }
        public function ticket($forumId,$concerning,$groupId,$subject)
        {
            $this->action = 'newthread';
            $subforum = $this->Database->prepare("SELECT * FROM tl_c4g_forum WHERE pid=? AND member_id=?")->execute($forumId,$groupId)->fetchAssoc();
            if(!$subforum){
                $subforum = $this->helper->createNewSubforum($forumId,$groupId);
            }
            $ticketforums = $this->Database->prepare("SELECT * FROM tl_c4g_forum WHERE pid=?")->execute($subforum['id'])->fetchAllAssoc();
            foreach($ticketforums as $ticketforum)
            {
                if($ticketforum['concerning'] == $concerning)
                {
                    $return = $this->getForumInTable($ticketforum['id']);
                }
            }
            if(!$return){
                $ticketforum = $this->helper->createNewTicketForum($subforum['id'],$concerning,$subject);
                $return = $this->generateNewThreadForm($ticketforum['id']);
            }
            return $return;
        }
        public function autoTicket($forumId, $groupId, $subject,$text ,$concerning)
        {
            $subforum = $this->Database->prepare("SELECT * FROM tl_c4g_forum WHERE pid=? AND member_id=?")->execute($forumId,$groupId)->fetchAssoc();
            if(!$subforum) {
                $subforum = $this->helper->createNewSubforum($forumId,$groupId);
            }
            $author =$subforum['default_author'];
            $owner = serialize(array($subforum['default_author']));
            $threads = $this->helper->getThreadsFromDB($subforum['id']);
            $group = $this->Database->prepare('SELECT cg_member FROM tl_member_group WHERE id=?')->execute($groupId)->fetchAssoc();
            $recipient = $group['cg_member'];
            foreach($threads as $thread){
                if($thread['concerning'] == $concerning && $thread['state'] != 3){
                    $return = $this->helper->insertPostIntoDB($thread['id'],$author,$subject,$text,null,null,null,null,null,null,null,null,null,null,null,$recipient,$owner);
                }
            }
            if(!$return){
                $return = $this->helper->insertThreadIntoDB($subforum['id'],$subject,$author,null,'999',$text,null,null,null,null,null,null,null,null,null,null,$recipient,$owner,$concerning);
            }
            return $return;
        }
        public function closethread($threadId)
        {
            $thread = $this->Database->prepare('SELECT * FROM tl_c4g_forum_thread WHERE id=?')->execute($threadId)->fetchAssoc();
            $newposts = $thread['posts']+1;
            $this->Database->prepare('UPDATE tl_c4g_forum_thread SET state = 3, posts ='.$newposts.' WHERE id=?')->execute($threadId);
            $set = array(
                'text'      => C4GForumTicketStatus::getState(3),
                'subject'      => C4GForumTicketStatus::getState(3),
                'state'     => 3,
                'creation'  => time(),
                'pid'       => $threadId,
                'author'    => $this->User->id,
                'post_number'     => $newposts
            );
            $this->Database->prepare('INSERT INTO tl_c4g_forum_post %s')->set($set)->execute();
            return $this->getThreadAsHtml($threadId);
        }


        /**
         * get reaction for denied permission
         *
         * @param string $message
         */
        public function getPermissionDenied($message)
        {

            if ($this->c4g_forum_jumpTo) {

                // redirect to defined page
                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
                    ->limit(1)
                    ->execute($this->c4g_forum_jumpTo);

                if ($objPage->numRows) {
                    $return['jump_to_url'] = $this->generateFrontendUrl($objPage->fetchAssoc());
                }

            }

            if (!$return['jump_to_url']) {
                // no redirect -> show message
                $return['usermessage'] = $message;
            }

            return $return;
        }


        /**
         * @return bool|string
         */
        public function getForumPageUrl()
        {

            $id           = $this->c4g_forum_sitemap_root;
            $sFrontendUrl = false;
            if (!empty($id)) {
                $oPage = \Contao\PageModel::findPublishedById($id);
                $sFrontendUrl = $this->Environment->url . TL_PATH . '/';
                $sFrontendUrl .= $this->getFrontendUrl($oPage->row());
            }

            return $sFrontendUrl;
        }

        /**
         *
         */
        private function setTempLanguage() {
            //several ways to set/get language ...

            if (trim($this->c4g_forum_language) == '') {
                $this->c4g_forum_language_temp = '';

                //language get param or request_uri for language switcher sites
                $getLang  = \Input::get('language');
                if ($getLang) {
                    $this->c4g_forum_language_temp = $getLang;
                } else if ($_SERVER["REQUEST_URI"]) {
                    //$uri = str_replace('.html','',substr($_SERVER['REQUEST_URI'],1));
                    $uri = $_SERVER['REQUEST_URI'];
                    $uri = explode('/',$uri);
                    if ($uri && $uri[0] && strlen($uri[0]) == 2) {
                        $this->c4g_forum_language_temp = $uri[0];
                    } else if ($uri && $uri[1] && strlen($uri[1]) == 2) {
                        $this->c4g_forum_language_temp = $uri[1];
                    }
                }

                //four other ways to get current language
                if ($this->c4g_forum_language_temp == '') {
                    /** @var \PageModel $objPage */
                    global $objPage;

                    $pageLang = \Controller::replaceInsertTags('{{page::language}}');
                    if ($pageLang) {
                        $this->c4g_forum_language_temp = $pageLang;
                    } else if ($objPage && $objPage->language) {
                        $this->c4g_forum_language_temp = $objPage->language;
                    } else if ($GLOBALS['TL_LANGUAGE']) {
                        $this->c4g_forum_language_temp = $GLOBALS['TL_LANGUAGE'];
                    } else if ($_SESSION["TL_LANGUAGE"]) {
                        $this->c4g_forum_language_temp = $_SESSION['TL_LANGUAGE'];
                    }
                }
            } else {
                $this->c4g_forum_language_temp = $this->c4g_forum_language;
            }

            if ($this->c4g_forum_language_temp != '') {
                $this->loadLanguageFile('frontendModules', $this->c4g_forum_language_temp);
                $this->loadLanguageFile('stopwords', $this->c4g_forum_language_temp);
            } else {
                //should not happen, but ...
                $this->loadLanguageFile('frontendModules', 'de');
                $this->loadLanguageFile('stopwords', 'de');
            }
        }

        /**
         * @param $forum
         * @param $fieldname
         * @return mixed
         */
        private function getForumLanguageConfig($forum, $fieldname) {

            switch($fieldname) {
                case 'name':
                    $names = unserialize($forum['optional_names']);
                    if ($names) {
                        foreach ($names as $name) {
                            if ($name['optional_language'] == $this->c4g_forum_language_temp) {
                                return $name['optional_name'];
                            }
                        }
                    }
                    break;
                case 'headline':
                    $headlines = unserialize($forum['optional_headlines']);
                    if ($headlines) {
                        foreach ($headlines as $headline) {
                            if ($headline['optional_headline_language'] == $this->c4g_forum_language_temp) {
                                return $headline['optional_headline'];
                            }
                        }
                    }
                    break;
                case 'description':
                    $descriptions = unserialize($forum['optional_descriptions']);
                    if ($descriptions) {
                        foreach ($descriptions as $description) {
                            if ($description['optional_description_language'] == $this->c4g_forum_language_temp) {
                                return $description['optional_description'];
                            }
                        }
                    }
                    break;
            }

            return $forum[$fieldname];
        }


        /**
         * function is called by every Ajax requests
         */
        public function generateAjax($request = null, $user = null)
        {

            $this->User = $user;

            // auf die benutzerdefinierte Fehlerbehandlung umstellen
            $old_error_handler = \set_error_handler("c4gForumErrorHandler");
            if ($request == null) {

                // Ajax Request: read get parameter "req"
                $request = $_GET['req'];

                if ($request != 'undefined') {
                    // replace "state" parameter in Session-Referer to force correct
                    // handling after login with "redirect back" set
                    $session                       = $this->Session->getData();
                    $session['referer']['last']    = $session['referer']['current'];
                    $session['referer']['current'] = C4GUtils::addParametersToURL(
                        $session['referer']['last'],
                        array('state' => $request));
                    $this->Session->setData($session);
                }
            }

            $this->setTempLanguage();

            //$this->loadLanguageFile('frontendModules', $this->c4g_forum_language_temp);
            //$this->loadLanguageFile('stopwords', $this->c4g_forum_language_temp);

            try {

                $this->initMembers();
                $session = $this->Session->getData();
                $frontendUrl = $this->Environment->url . TL_PATH . '/' . $session['current_forum_url'];

                $this->helper = new C4GForumHelper($this->Database, $this->Environment, $this->User, $this->headline,
                                                   $frontendUrl, $this->c4g_forum_show_realname);

                if (($_SERVER['REQUEST_METHOD']) == 'PUT') {
                    parse_str(file_get_contents("php://input"), $this->putVars);
                    foreach ($this->putVars as $key => $value) {
                        $tmpVal = Input::xssClean($value, true);
                        $tmpVal = str_replace('<script>', '', $tmpVal);
                        $tmpVal = str_replace('</script>', '', $tmpVal);
                        $tmpVal = str_replace('onclick=', '', $tmpVal);
                        $this->putVars[$key] = $tmpVal;
                    }
                }

                // if there was an initial get parameter "state" then use it for jumping directly
                // to the refering function
                if (($request == 'initnav') && $_GET['initreq']) {
                    $_GET['historyreq'] = $_GET['initreq'];
                }

                // History navigation
                if ($_GET['historyreq']) {
                    $actions = explode(';', $_GET['historyreq']);
                    $result  = array();
                    foreach ($actions AS $action) {
                        $r = $this->performHistoryAction($action);
                        array_insert($result, 0, $r);
                    }

                } else {
                    switch ($request) {
                        case 'initnav' :
                            switch ($this->c4g_forum_navigation) {
                                case 'TREE':
                                    $result = $this->performAction('forumtree');
                                    break;

                                case 'BOXES':
                                    $forum = $this->helper->getForumFromDB($this->c4g_forum_startforum);
                                    if (($forum['use_intropage']) && (!$this->c4g_forum_hide_intropages)) {
                                        $this->action = 'forumintro';
                                        $result       = $this->performAction('forumintro:' . $this->c4g_forum_startforum);
                                    } else {
                                        $this->action = $this->c4g_forum_param_forum;
                                        $result       = $this->performAction($this->c4g_forum_param_forumbox.':' . $this->c4g_forum_startforum);
                                    }
                                    break;

                                default:
                                    break;

                            }
                            break;
                        default:
                            $actions = explode(';', $request);
                            $result  = array();
                            foreach ($actions AS $action) {
                                $r = $this->performAction($action);
                                if (is_array($r)) {
                                    $result = array_merge($result, $r);
                                }
                            }
                            break;
                    }
                }
            } catch (Exception $e) {
                $result = $this->showException($e);
            }
            \set_error_handler($old_error_handler);
            if (count($GLOBALS['c4gForumErrors']) > 0) {
                $result['phpErrors'] = $GLOBALS['c4gForumErrors'];
            }
            if (($this->c4g_forum_sitemap_updated == 0) && ($this->c4g_forum_sitemap)) {
                $sitemapJob = $this->helper->generateSitemapCronjob($this, 0);
                if ($sitemapJob) {
                    $result['cronexec'][] = $sitemapJob;
                }
            }
//            return $result;
            if ($this->plainhtml) {
                return $result;
            } else {
                return json_encode($result);
            }
        }


        /**
         * Needed for C4G-Maps integration
         */
        public function repInsertTags($str)
        {

            return parent::replaceInsertTags($str);
        }


        /**
         * Needed for C4G-Maps integration
         */
        public function import($strClass, $strKey = false, $blnForce = false)
        {

            parent::import($strClass, $strKey, $blnForce);
        }


        /**
         * Needed for C4G-Maps integration
         */
        public function getInput()
        {

            return $this->Input;
        }


        /**
         * Needed for C4G-Maps integration
         */
        public function getFrontendUrl($arrRow)
        {

            return parent::generateFrontendUrl($arrRow);
        }


        /**
         *
         */
        protected function initMembers()
        {

            if (!$this->c4g_forum_jqui) {
                // jQuery UI is deactivated -> automatically deactivate all jQuery UI dependant options
                $this->c4g_forum_jqui_lib               = false;
                $this->c4g_forum_uitheme_css_src        = '';
                $this->c4g_forum_dialogs_embedded       = true;  // real dialogs only with jQuery UI
                $this->c4g_forum_embdialogs_jqui        = false;
                $this->c4g_forum_breadcrumb_jqui_layout = false;
                $this->c4g_forum_buttons_jqui_layout    = false;
                $this->c4g_forum_table_jqui_layout      = false;
                $this->c4g_forum_posts_jqui             = false;
                $this->c4g_forum_boxes_jqui_layout      = false;
                //$this->c4g_forum_enable_scrollpane = false;
            }

            $this->dialogs_jqui = ((!$this->c4g_forum_dialogs_embedded) || ($this->c4g_forum_embdialogs_jqui));
//            \System::import('FrontendUser', 'User');
//            $this->import('FrontendUser', 'User');

        }
    }
