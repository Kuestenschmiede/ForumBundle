<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 15.09.17
 * Time: 15:46
 */

namespace con4gis\ForumBundle\Controller;


use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
use con4gis\ForumBundle\Resources\contao\modules\C4GForum;
use Contao\FrontendUser;
use Contao\ModuleModel;
use Contao\System;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends Controller
{
    public function ajaxAction(Request $request, $id, $req)
    {
        $response = new JsonResponse();
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
        if ($objModule->guests && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$objModule->protected)
        {
            $response->setData('Forbidden');
            $response->setStatusCode(403);
        }

        // Protected element
        if (!BE_USER_LOGGED_IN && $objModule->protected)
        {
            if (!FE_USER_LOGGED_IN)
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
        if (!FE_USER_LOGGED_IN) {
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
                    $sUrl = \Input::post('url');
                    if (empty($iRecipientId) && !empty($sRecipient)) {
                        $aRecipient = C4gForumPn::getMemberByUsername($sRecipient);
                        if(empty($aRecipient)){
                            throw new \Exception($GLOBALS['TL_LANG']['tl_c4g_forum_pn']['member_not_found']);
                        }
                        $iRecipientId = $aRecipient['id'];
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
                    $oPn->send($sUrl);
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