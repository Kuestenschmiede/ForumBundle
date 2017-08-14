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
namespace con4gis\ForumBundle\Resources\contao\modules\api;
    // index.php is a frontend script
    define('TL_MODE', 'FE');
    // Start the session so we can access known request tokens
    @session_start();

    // Allow to bypass the token check
    if (!isset($_POST['REQUEST_TOKEN'])) {
        /**
         *
         */
        define('BYPASS_TOKEN_CHECK', true);
    }


    // Initialize the system
    //require(TL_ROOT . '/system/initialize.php');

    /**
     * Class C4gForumPnApi
     */
    class C4gForumPnApi extends \Frontend
    {


        /**
         * @var string
         */
        private $_sApiUrl = 'system/modules/con4gis_core/api/index.php';


        /**
         * Initialize the object
         */
        public function __construct()
        {

            // Load user object before calling the parent constructor
            $this->import('FrontendUser', 'User');
            $this->User->authenticate();
            parent::__construct();
            // check if we need contao 4 routing
            if (class_exists('\con4gis\ApiBundle\Controller\ApiController') &&  (version_compare( VERSION, '4', '>=' ))) {
                $this->_sApiUrl = 'con4gis/api/c4g_forum_pn_api';
            }

            // Check whether a user is logged in
            define('BE_USER_LOGGED_IN', $this->getLoginStatus('BE_USER_AUTH'));
            define('FE_USER_LOGGED_IN', $this->getLoginStatus('FE_USER_AUTH'));
        }

        /**
         * Generate response
         */
        public function generate($arrFragments)
        {
            if(!FE_USER_LOGGED_IN){
                header('HTTP/1.1 400 Bad Request');
                exit;
            }

            \Contao\System::loadLanguageFile("tl_c4g_forum_pn");

            // Set default headers for api
            header('Content-Type: application/json');
            try {
                if ($arrFragments[0] == "modal") {
                    if (!empty($arrFragments[1])) {
                        $sType      = $arrFragments[1];
                        $aReturn    = array();
                        $sClassName = "con4gis\\ForumBundle\\Resources\\contao\\classes\\" . ucfirst($sType);
                        if (class_exists($sClassName)) {
                            $aData = \Input::get('data');

                            $aReturn['template'] = $sClassName::parse($aData);
                        }
                        return json_encode($aReturn);
//                        exit();

                    } else {
                        header('HTTP/1.1 400 Bad Request');
                        exit;
                    }



                } elseif ($arrFragments[0] == "delete") {
                    $iId = $arrFragments[1];
                    $oPn = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::getById($iId);
                    $res = $oPn->delete();
                    return json_encode(array('success' => $res));
//                    exit();



                } elseif ($arrFragments[0] == "mark") {

                    $iStatus = intval(\Input::post('status'));
                    $iId = intval(\Input::post('id'));

                    $oPn = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::getById($iId);
                    $oPn->setStatus($iStatus);
                    $oPn->update();
                    return json_encode(array('success' => true));
//                    exit();




                } elseif ($arrFragments[0] == "send") {

                    $iRecipientId = \Input::post('recipient_id');
                    $sRecipient = \Input::post('recipient');
                    $sUrl = \Input::post('url');
                    if(empty($iRecipientId) && !empty($sRecipient)) {
                        $aRecipient = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::getMemberByUsername($sRecipient);
                        if(empty($aRecipient)){
                            throw new \Exception($GLOBALS['TL_LANG']['tl_c4g_forum_pn']['member_not_found']);
                        }
                        $iRecipientId = $aRecipient['id'];
                    }

                    $aData = array(
                        "subject"      => \Input::post('subject'),
                        "message"      => htmlentities($_POST['message']),
                        "sender_id"    => $this->User->id,
                        "recipient_id" => $iRecipientId,
                        "dt_created"   => time(),
                        "status"       => 0
                    );




                    $oPn = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::create($aData);
                    $oPn->send($sUrl);
                    return json_encode(array('success' => true));
//                    exit();

                }else{
                    header('HTTP/1.1 400 Bad Request');
                    exit;
                }
            } catch (\Exception $e) {
                return json_encode(array('success' => false, "message" => $e->getMessage()));
//                exit();
            }
        }

        /**
         * Run the controller
         */
        public function run()
        {
            if(!FE_USER_LOGGED_IN){
                header('HTTP/1.1 400 Bad Request');
                exit;
            }

            \Contao\System::loadLanguageFile("tl_c4g_forum_pn");

            // Set default headers for api
            header('Content-Type: application/json');

            try {// Maintenance mode
                if ($GLOBALS['TL_CONFIG']['maintenanceMode'] && !BE_USER_LOGGED_IN) {
                    header('HTTP/1.1 503 Service Unavailable');
                    exit;
                }

                // Get path
                if ($this->_sApiUrl == 'con4gis/api/c4g_forum_pn_api') {
                    $arrFragments = $this->getFragments();
                } else {
                    $arrFragments = $this->getFragmentsFromUrl();
                }

                // Stop on empty path
                if (empty($arrFragments)) {
                    header('HTTP/1.1 400 Bad Request');
                    exit;
                }


                if ($arrFragments[0] == "modal") {
                    if (!empty($arrFragments[1])) {
                        $sType      = $arrFragments[1];
                        $aReturn    = array();
                        $sClassName = "con4gis\\ForumBundle\\Resources\\contao\\classes\\" . ucfirst($sType);
                        if (class_exists($sClassName)) {
                            $aData = \Input::get('data');
                            
                            $aReturn['template'] = $sClassName::parse($aData);
                        }

                        return json_encode($aReturn);
                        exit();

                    } else {
                        header('HTTP/1.1 400 Bad Request');
                        exit;
                    }



                } elseif ($arrFragments[0] == "delete") {
                    $iId = $arrFragments[1];
                    $oPn = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::getById($iId);
                    $res = $oPn->delete();
                    echo json_encode(array('success' => $res));
                    exit();



                } elseif ($arrFragments[0] == "mark") {

                    $iStatus = intval(\Input::post('status'));
                    $iId = intval(\Input::post('id'));

                    $oPn = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::getById($iId);
                    $oPn->setStatus($iStatus);
                    $oPn->update();
                    echo json_encode(array('success' => true));
                    exit();


                    
                    
                } elseif ($arrFragments[0] == "send") {

                    $iRecipientId = \Input::post('recipient_id');
                    $sRecipient = \Input::post('recipient');
                    $sUrl = \Input::post('url');
                    if(empty($iRecipientId) && !empty($sRecipient)) {
                        $aRecipient = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::getMemberByUsername($sRecipient);
                        if(empty($aRecipient)){
                            throw new \Exception($GLOBALS['TL_LANG']['tl_c4g_forum_pn']['member_not_found']);
                        }
                        $iRecipientId = $aRecipient['id'];
                    }

                    $aData = array(
                        "subject"      => \Input::post('subject'),
                        "message"      => htmlentities($_POST['message']),
                        "sender_id"    => $this->User->id,
                        "recipient_id" => $iRecipientId,
                        "dt_created"   => time(),
                        "status"       => 0
                    );




                    $oPn = \con4gis\ForumBundle\Resources\contao\models\C4gForumPn::create($aData);
                    $oPn->send($sUrl);
                    echo json_encode(array('success' => true));
                    exit();

                }else{
                    header('HTTP/1.1 400 Bad Request');
                    exit;
                }
            } catch (\Exception $e) {
                echo json_encode(array('success' => false, "message" => $e->getMessage()));
                exit();
            }
        }


        /**
         * Split the request into fragments and find the api resource
         */
        protected function getFragmentsFromUrl()
        {

            // Return null on empty request path
            if (\Environment::get('request') == '') {
                return null;
            }

            $test = \Environment::get('request');

            // Get the request string without the index.php fragment
            if (\Environment::get('request') == $this->_sApiUrl . 'index.php') {
                $strRequest = '';
            } else {
                list($strRequest) = explode('?', str_replace($this->_sApiUrl . 'index.php/', '', \Environment::get('request')), 2);
            }

            // Remove api fragment
            if (substr($strRequest, 0, strlen($this->_sApiUrl)) == $this->_sApiUrl) {
                $strRequest = substr($strRequest, strlen($this->_sApiUrl));
            }

            // URL decode here
            $strRequest = rawurldecode($strRequest);
            $strRequest = substr($strRequest,1);

            // return the fragments
            return explode('/', $strRequest);
        }

        /**
         * Returns the fragments when con4gis/api/ is used (Contao 4)
         */
        private function getFragments()
        {
            // Return null on empty request path
            if (\Environment::get('request') == '') {
                return null;
            }

            $request = \Environment::get('request');
            $strRequest = substr($request, strlen($this->_sApiUrl), strlen($request));
            $strRequest = rawurldecode($strRequest);
            if (substr($strRequest, 0, 1) == '/') {
                $strRequest = substr($strRequest, 1);
            }
            return explode('/', $strRequest);

        }
    }

    /**
     * Instantiate controller
     */
//    $objApi = new C4gForumPnApi();
//    $objApi->run();