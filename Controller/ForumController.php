<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 15.09.17
 * Time: 15:46
 */

namespace con4gis\ForumBundle\Controller;


use con4gis\CoreBundle\Resources\contao\classes\C4GUtils;
use con4gis\CoreBundle\Resources\contao\classes\notification\C4GNotification;
use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
use con4gis\ForumBundle\Resources\contao\modules\C4GForum;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabase;
use Contao\FrontendUser;
use Contao\Input;
use Contao\ModuleModel;
use Contao\System;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends Controller
{
    public function ajaxAction(Request $request, $id, $req = '')
    {
        $response = new JsonResponse();
        $post = $request->request->get('post');
        if ($post) {
            $post = Input::xssClean($post);
            $post = C4GUtils::cleanHtml($post);
            $post = C4GUtils::secure_ugc($post);
            $request->request->set('post', $post);
        }
        $feUser = FrontendUser::getInstance();
        $feUser->authenticate();
        if (!isset( $id ) || !is_numeric( $id )) {
            $response->setStatusCode(400);
        }
        if (!strlen($id) || $id < 1)
        {
            $response->setData('Missing frontend module ID');
            $response->setStatusCode(412);
        }
        $objModule = ModuleModel::findByPk($id);

        if (!$objModule)
        {
            $response->setData('Frontend module not found');
            $response->setStatusCode(404);
        }

        // Show to guests only
        if ($objModule->guests && C4GUtils::isFrontendUserLoggedIn() && !C4GUtils::isBackendUserLoggedIn() && !$objModule->protected)
        {
            $response->setData('Forbidden');
            $response->setStatusCode(403);
        }

        // Protected element
        if (!C4GUtils::isBackendUserLoggedIn() && $objModule->protected)
        {
            if (!C4GUtils::isFrontendUserLoggedIn())
            {
                $response->setData('Forbidden');
                $response->setStatusCode(403);
            }
            $groups = deserialize($objModule->groups);
            if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $feUser->groups)) < 1)
            {
                $response->setData('Forbidden');
                $response->setStatusCode(403);
            }
        }

        // Return if the class does not exist
        if (!class_exists(C4GForum::class))
        {
//            $this->log('Module class "'.$GLOBALS['FE_MOD'][$objModule->type].'" (module "'.$objModule->type.'") does not exist', 'Ajax getFrontendModule()', TL_ERROR);
            $response->setData('Frontend module class does not exist');
            $response->setStatusCode(404);
        }

        $objModule->typePrefix = 'mod_';
        $objModule = new C4GForum($objModule);
        $return = $objModule->generateAjax($req, $feUser);
        $response->setData($return);
        return $response;
    }

    public function personalMessageAction(Request $request, $actionFragment)
    {
        $response = new JsonResponse();
        $feUser = FrontendUser::getInstance();
        $feUser->authenticate();
        if (!C4GUtils::isFrontendUserLoggedIn()) {
            $response->setStatusCode(400);
            return $response;
        }
        $arrFragments = explode('/', $actionFragment);
        System::loadLanguageFile("tl_c4g_forum_pn");
        try {
            // check which service is requested
            switch($arrFragments[0]) {
                case "modal":
                    if (!empty($arrFragments[1])) {
                        $sType      = $arrFragments[1];
                        $aReturn    = array();
                        $sClassName = "con4gis\\ForumBundle\\Resources\\contao\\classes\\" . ucfirst($sType);
                        if (class_exists($sClassName)) {
                            $aData = \Input::get('data');

                            $aReturn['template'] = $sClassName::parse($aData);
                        }
                        $response->setData($aReturn);
                        return $response;
                    } else {
                        $response->setStatusCode(400);
                        return $response;
                    }
                    break;
                case "delete":
                    $iId = $arrFragments[1];
                    $oPn = C4gForumPn::getById($iId);
                    $res = $oPn->delete();
                    $response->setData(['success' => $res]);
                    return $response;
                    break;
                case "mark":
                    $iStatus = intval(\Input::post('status'));
                    $iId = intval(\Input::post('id'));

                    $oPn = C4gForumPn::getById($iId);
                    $oPn->setStatus($iStatus);
                    $oPn->update();
                    $response->setData(['success' => true]);
                    return $response;
                    break;
                case "send":
                    $iRecipientId = \Input::post('recipient_id');
                    $sRecipient = \Input::post('recipient');
                    $forumModule = \Input::post('target');
                    if (empty($iRecipientId) && !empty($sRecipient)) {
                        $aRecipient = C4gForumPn::getMemberByUsername($sRecipient);
                        if(empty($aRecipient)){
                            throw new \Exception($GLOBALS['TL_LANG']['tl_c4g_forum_pn']['member_not_found']);
                        }
                        $iRecipientId = $aRecipient['id'];
                    } elseif (!empty($iRecipientId)) {
                        $db = \Database::getInstance();
                        $stmt = $db->prepare("SELECT * FROM tl_member WHERE id = ?");
                        $result = $stmt->execute($iRecipientId);
                        $aRecipient = $result->fetchAssoc();
                    }
                    $aData = array(
                        "subject"      => \Input::post('subject'),
                        "message"      => htmlentities($_POST['message']),
                        "sender_id"    => $feUser->id,
                        "recipient_id" => $iRecipientId,
                        "dt_created"   => time(),
                        "status"       => 0
                    );
                    $oPn = C4gForumPn::create($aData);
                    $oPn->_save();

                    /** Notification Center */
                    /** Get forum module settings  */

                    $db = \Database::getInstance();
                    $stmt = $db->prepare("SELECT new_pm_redirect, mail_new_pm FROM tl_module WHERE id = ?");
                    $result = $stmt->execute($forumModule)->fetchAssoc();
                    $this->container->get('contao.framework')->initialize();
                    $route = \Contao\Controller::replaceInsertTags('{{link_url::'.$result['new_pm_redirect'].'}}');

                    $notification = new C4GNotification($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum']['mail_new_pm']);
                    $notification->setTokenValue('user_name', $aRecipient['username']);
                    $notification->setTokenValue('user_email', $aRecipient['email']);
                    $notification->setTokenValue('responsible_username', $this->getUser()->username);
                    $notification->setTokenValue('link', $_SERVER['SERVER_NAME'].'/'.$route);
                    $notification->setTokenValue('admin_email', $GLOBALS['TL_CONFIG']['adminEmail']);
                    $notification->setTokenValue('subject', $aData['subject']);
                    $notification->setTokenValue('message', $aData['message']);
                    $notification->send(unserialize($result['mail_new_pm']));

                    $response->setData(['success' => true]);
                    return $response;
                    break;
                default:
                    $response->setStatusCode(400);
                    return $response;
                    break;
            }
        } catch (\Exception $e) {
            $response->setData(['success' => false, "message" => $e->getMessage()]);
            return $response;
        }
    }
}