<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 15.09.17
 * Time: 15:46
 */

namespace con4gis\ForumBundle\Controller;

use con4gis\CoreBundle\Resources\contao\classes\C4GUtils;
use con4gis\ForumBundle\Resources\contao\models\C4GForumSubscriptionModel;
use con4gis\ForumBundle\Resources\contao\models\C4GThreadSubscriptionModel;
use Contao\Database;
use Contao\Frontend;
use Contao\FrontendUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionController extends Controller
{
    public function changeForumSubscriptionAction(Request $request) {
        try {
            if (!C4GUtils::isFrontendUserLoggedIn()) {
                throw new \Exception("Frontenduser is not logged in.");
            }
            $userId = FrontendUser::getInstance()->id;
            $forumId = $request->request->get('target');
            $deleteSub = $request->request->get('deletesub') ? true : false;
            $model = new C4GForumSubscriptionModel(
                Database::getInstance()->prepare(
                    "SELECT * FROM ".
                    C4GForumSubScriptionModel::getTable().
                    " WHERE pid = ? AND member = ?")
                ->execute($forumId, $userId)
            );
            if ($model->id === null) {
                throw new \Exception("C4gForumSubscriptionModel: Subscription with ID $forumId does not exist.");
            }
            if ($deleteSub === true) {
                $model->delete();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => 'Erfolg',
                    'message' => 'Abonnement wurde erfolgreich gelöscht.'
                ));
            } else {
                $model->newThread = $request->request->get('newthreads');
                $model->movedThread = $request->request->get('movedthreads');
                $model->deletedThread = $request->request->get('deletedthreads');
                $model->newPost = $request->request->get('newposts');
                $model->editedPost = $request->request->get('editedposts');
                $model->deletedPost = $request->request->get('deletedposts');
                $model->save();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => 'Erfolg',
                    'message' => 'Abonnement wurde erfolgreich angepasst.'
                ));
            }
        } catch (\Exception $e) {
            $response = new JsonResponse();
            $response->setData(array(
                'title' => 'Fehler',
                'message' => 'Ein Fehler ist aufgetreten.'
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
            $model = new C4GThreadSubscriptionModel(
                Database::getInstance()->prepare(
                    "SELECT * FROM ".
                    C4GThreadSubscriptionModel::getTable().
                    " WHERE pid = ? AND member = ?")
                    ->execute($threadId, $userId)
            );
            if ($model->id === null) {
                throw new \Exception("C4GThreadSubscriptionModel: Subscription with ID $threadId does not exist.");
            }
            if ($deleteSub === true) {
                $model->delete();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => 'Erfolg',
                    'message' => 'Abonnement wurde erfolgreich gelöscht.'
                ));
            } else {
                $model->newPost = $request->request->get('newposts');
                $model->editedPost = $request->request->get('editedposts');
                $model->deletedPost = $request->request->get('deletedposts');
                $model->save();
                $response = new JsonResponse();
                $response->setData(array(
                    'title' => 'Erfolg',
                    'message' => 'Abonnement wurde erfolgreich angepasst.'
                ));
            }
        } catch (\Exception $e) {
            $response = new JsonResponse();
            $response->setData(array(
                'title' => 'Fehler',
                'message' => 'Ein Fehler ist aufgetreten. '.$e->getMessage()
            ));
        }
        return $response;
    }
}