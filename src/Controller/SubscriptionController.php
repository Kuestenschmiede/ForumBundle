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
namespace con4gis\ForumBundle\Controller;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\ForumBundle\Resources\contao\models\C4GForumSubscriptionModel;
use con4gis\ForumBundle\Resources\contao\models\C4GThreadSubscriptionModel;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\Frontend;
use Contao\FrontendUser;
use Contao\ModuleModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionController extends AbstractController
{
    public function __construct(ContaoFramework $framework)
    {
        $framework->initialize();
    }

    public function changeForumSubscriptionAction(Request $request, string $language) {
        try {
            if (!C4GUtils::isFrontendUserLoggedIn()) {
                throw new \Exception("Frontenduser is not logged in.");
            }
            $userId = FrontendUser::getInstance()->id;
            $forumId = $request->request->get('target');
            $deleteSub = $request->request->get('deletesub') ? true : false;
            $model = C4GForumSubscriptionModel::findByForumAndMember($forumId, $userId);
            if ($model->id === null) {
                throw new \Exception("C4gForumSubscriptionModel: Subscription with ID $forumId does not exist.");
            }
            \System::loadLanguageFile('subscriptions');
            if ($deleteSub === true) {
                $model->delete();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_TITLE_DELETE'],
                    'message' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_MESSAGE_DELETE']
                ));
            } else {
                $model->newThread = $request->request->get('newthread') ?: '0';
                $model->movedThread = $request->request->get('movedthread') ?: '0';
                $model->deletedThread = $request->request->get('deletedthread') ?: '0';
                $model->newPost = $request->request->get('newpost') ?: '0';
                $model->editedPost = $request->request->get('editedpost') ?: '0';
                $model->deletedPost = $request->request->get('deletedpost') ?: '0';
                $model->save();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_TITLE'],
                    'message' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_MESSAGE']
                ));
            }
        } catch (\Throwable $e) {
            \System::loadLanguageFile('subscriptions');
            $response = new JsonResponse();
            $response->setData(array(
                'title' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['ERROR_TITLE'],
                'message' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['ERROR_MESSAGE']
            ));
        }
        return $response;
    }

    public function changeThreadSubscriptionAction(Request $request) {
        try {
            if (!C4GUtils::isFrontendUserLoggedIn()) {
                throw new \Exception("Frontenduser is not logged in.");
            }
            $userId = FrontendUser::getInstance()->id;
            $threadId = $request->request->get('target');
            $deleteSub = $request->request->get('deletesub') ? true : false;
            $model = C4GThreadSubscriptionModel::findByThreadAndMember($threadId, $userId);
            if ($model->id === null) {
                throw new \Exception("C4GThreadSubscriptionModel: Subscription with ID $threadId does not exist.");
            }
            \System::loadLanguageFile('subscriptions');
            if ($deleteSub === true) {
                $model->delete();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_TITLE_DELETE'],
                    'message' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_MESSAGE_DELETE']
                ));
            } else {
                $model->newPost = $request->request->get('newpost') ?: '0';
                $model->editedPost = $request->request->get('editedpost') ?: '0';
                $model->deletedPost = $request->request->get('deletedpost') ?: '0';
                $model->save();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_TITLE'],
                    'message' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUCCESS_MESSAGE']
                ));
            }
        } catch (\Throwable $e) {
            \System::loadLanguageFile('subscriptions');
            $response = new JsonResponse();
            $response->setData(array(
                'title' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['ERROR_TITLE'],
                'message' => $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['ERROR_MESSAGE']
            ));
        }
        return $response;
    }
}