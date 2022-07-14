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

use con4gis\CoreBundle\Controller\UploadController;
use Contao\Database;
use Contao\Dbafs;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumUploadController
{
    private UploadController $uploadController;

    public function __construct(UploadController $uploadController)
    {
        $this->uploadController = $uploadController;
    }

    /**
     * @Route(
     *     "/c4g_forum/upload/image/{threadId}",
     *     name="c4g_forum_upload_image",
     *     methods={"POST"},
     *     requirements={"threadId"="\d+"}
     * )
     * @param Request $request
     * @param int $threadId
     * @return JsonResponse
     */
    public function imageUploadAction(Request $request, int $threadId): JsonResponse
    {
        $response =  $this->uploadController->imageUploadAction($request);
        $data = json_decode($response->getContent(), true);
        if ($data['url']) {
            $fileId = $this->insertFileReferenceByThreadIdAndUrl($threadId, $data['url']);
            if ($fileId !== 0) {
                $data['url'] = explode('files', $data['url'])[0] . 'c4g_forum/file/'.$fileId;
                $response->setData($data);
            }
        }
        return $response;
    }

    /**
     * @Route(
     *     "/c4g_forum/upload/file/{threadId}",
     *     name="c4g_forum_upload_file",
     *     methods={"POST"},
     *     requirements={"threadId"="\d+"}
     * )
     * @param Request $request
     * @param int $threadId
     * @return JsonResponse
     */
    public function fileUploadAction(Request $request, int $threadId): JsonResponse
    {
        $response =  $this->uploadController->fileUploadAction($request);
        $data = json_decode($response->getContent(), true);
        if ($data['url']) {
            $this->insertFileReferenceByThreadIdAndUrl($threadId, $data['url']);
        }
        return $response;
    }

    private function insertFileReferenceByThreadIdAndUrl(int $threadId, string $url): int
    {
        Dbafs::syncFiles();
        $relativeUrl = 'files'.explode('files', $url)[1];
        $database = Database::getInstance();
        $statement = $database->prepare('SELECT uuid FROM tl_files WHERE path = ?');
        $result = $statement->execute($relativeUrl)->fetchAssoc();
        if ($result !== false) {
            $database = Database::getInstance();
            $statement = $database->prepare(
                'INSERT INTO tl_c4g_forum_upload (threadId, fileUUid) VALUES (?, ?)'
            );
            $statement->execute($threadId, $result['uuid']);
            $statement = $database->prepare('SELECT LAST_INSERT_ID() as id');
            $result = $statement->execute()->fetchAssoc();
            return (int) $result['id'];
        }
        return 0;
    }
}