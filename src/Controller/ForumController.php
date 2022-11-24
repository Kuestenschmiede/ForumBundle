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


use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\ForumBundle\Classes\C4GForumNotification;
use con4gis\ForumBundle\Resources\contao\models\C4gForumPn;
use con4gis\ForumBundle\Resources\contao\modules\C4GForum;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FrontendUser;
use Contao\Input;
use Contao\ModuleModel;
use Contao\System;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends AbstractController
{
    public function __construct(ContaoFramework $framework)
    {
        $framework->initialize();
    }

    public function ajaxAction(Request $request, $id, $req = '')
    {
        $response = new JsonResponse();
        $post = $request->request->get('post');
        if ($post) {
            $post = Input::xssClean($post);
            $post = C4GUtils::cleanHtml($post, false, ['/<pre(.*?)<\/pre>/is']);
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

    public function personalMessageAction(Request $request, string $language, string $action, string $modifier)
    {
        $response = new JsonResponse();
        $feUser = FrontendUser::getInstance();
        $feUser->authenticate();
        if (!C4GUtils::isFrontendUserLoggedIn()) {
            $response->setStatusCode(400);
            return $response;
        }
        if ($language !== 'de' && $language !== 'en') {
            $language = 'de';
        }
        System::loadLanguageFile("tl_c4g_forum_pn", $language);
        try {
            switch($action) {
                case "modal":
                    if (!empty($modifier)) {
                        $sType      = $modifier;
                        $aReturn    = array();
                        $sClassName = "con4gis\\ForumBundle\\Classes\\" . ucfirst($sType);
                        if (class_exists($sClassName)) {
                            $aData = \Input::get('data');

                            $aReturn['template'] = $sClassName::parse($aData);
                        }
                        $response->setData($aReturn);
                    } else {
                        $response->setStatusCode(400);
                    }
                    return $response;
                case "delete":
                    $iId = $modifier;
                    $oPn = C4gForumPn::getById($iId);
                    $res = $oPn->delete();
                    $response->setData(['success' => $res]);
                    return $response;
                case "mark":
                    $iStatus = intval(\Input::post('status'));
                    $iId = intval(\Input::post('id'));

                    $oPn = C4gForumPn::getById($iId);
                    $oPn->setStatus($iStatus);
                    $oPn->update();
                    $response->setData(['success' => true]);
                    return $response;
                case "send":
                    $iRecipientId = \Input::post('recipient_id');
                    $sRecipient = \Input::post('recipient');
                    $forumModule = \Input::post('target');
                    if (!$forumModule || $forumModule === 'null') {
                        $session = $request->getSession();
                        $forumModule = $session->get('pm-forum-module');
                    }
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
                    $message = htmlentities($_POST['message']);
                    $message = str_replace(['&lt;div&gt;', '&lt;/div&gt;'], '', $message);
                    $message = trim($message);
                    $aData = array(
                        "subject"      => \Input::post('subject'),
                        "message"      => $message,
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
                    $route = \Contao\Controller::replaceInsertTags('{{link_url::' . $result['new_pm_redirect'] . '}}');
                    $user = FrontendUser::getInstance();

                    try {
                        $notification = new C4GForumNotification(C4GForumNotification::MAIL_NEW_PM);
                        $notification->setTokenValue('user_name', $aRecipient['username']);
                        $notification->setTokenValue('user_email', $aRecipient['email']);
                        $notification->setTokenValue('responsible_username', $user->username);
                        $notification->setTokenValue('link', $_SERVER['SERVER_NAME'] . '/' . $route);
                        $notification->setTokenValue('admin_email', $GLOBALS['TL_CONFIG']['adminEmail']);
                        $notification->setTokenValue('subject', $aData['subject']);
                        $notification->setTokenValue('message', html_entity_decode($aData['message']));
                        $notification->send(\Contao\StringUtil::deserialize($result['mail_new_pm']));
                    } catch (\Throwable $e) {
                        C4gLogModel::addLogEntry('forum', $e->getMessage());
                    }
                    $response->setData(['success' => true]);
                    return $response;
                default:
                    $response->setStatusCode(400);
                    return $response;
            }
        } catch (\Exception $e) {
            $response->setData(['success' => false, "message" => $e->getMessage()]);
            return $response;
        }
    }
}