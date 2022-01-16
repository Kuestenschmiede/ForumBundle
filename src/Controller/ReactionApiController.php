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

use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\FrontendUser;
use Contao\System;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;

class ReactionApiController extends AbstractController
{
    private $csrfTokenManager;

    /**
     * @Route(
     *     "/c4g_forum/reaction",
     *     name="c4g_forum_reaction",
     *     methods={"POST"},
     *     defaults={"_token_check": true}
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function reaction(Request $request) : JsonResponse {
        $framework = $this->get('contao.framework');
        $framework->initialize();
        $this->csrfTokenManager = $this->get('contao.csrf.token_manager');
        $token = $id = $request->request->get('REQUEST_TOKEN', null);
        $token = new CsrfToken('REQUEST_TOKEN', $token);
        $this->csrfTokenManager->isTokenValid($token);

        $reactionId = $request->request->getInt('reactionId');
        $postId = $request->request->getInt('postId');
        $user = FrontendUser::getInstance();
        if ($user->id < 1 || $postId === 0 || $reactionId !== 0) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
        $database = Database::getInstance();
        $stmt = $database->prepare(
            'SELECT * FROM tl_c4g_forum_post_reaction WHERE postId = ? AND memberId = ? AND reactionId = ?'
        );
        if ($stmt->execute($postId, $user->id, $reactionId)->numRows < 1) {
            $stmt = $database->prepare(
                'INSERT INTO tl_c4g_forum_post_reaction(postId, memberId, reactionId) VALUES (?, ?, ?)'
            );
            $stmt->execute($postId, $user->id, $reactionId);
        } else {
            $stmt = $database->prepare(
                'DELETE FROM tl_c4g_forum_post_reaction WHERE postId = ? AND memberId = ? AND reactionId = ?'
            );
            $stmt->execute($postId, $user->id, $reactionId);
        }

        return new JsonResponse([], Response::HTTP_OK);
    }
}