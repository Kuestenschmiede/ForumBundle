<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2026, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Controller;

use con4gis\CoreBundle\Controller\UploadController;
use Contao\Database;
use Contao\Dbafs;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ForumUploadController
{
    private UploadController $uploadController;

    public function __construct(UploadController $uploadController)
    {
        $this->uploadController = $uploadController;
    }

    #[Route(
        path: '/c4g_forum/upload/image',
        name: 'c4g_forum_upload_image',
        methods: ['POST'],
        requirements: ['threadId' => '\d+']
    )]
    public function imageUploadAction(Request $request): JsonResponse
    {
        $response =  $this->uploadController->imageUploadAction($request);
        return $this->insertFileReferenceAndUpdateResponseUrl($response);
    }

    #[Route(
        path: '/c4g_forum/upload/file',
        name: 'c4g_forum_upload_file',
        methods: ['POST'],
        requirements: ['threadId' => '\d+']
    )]
    public function fileUploadAction(Request $request): JsonResponse
    {
        $response =  $this->uploadController->fileUploadAction($request);
        return $this->insertFileReferenceAndUpdateResponseUrl($response);
    }

    private function insertFileReferenceAndUpdateResponseUrl(JsonResponse $response): JsonResponse
    {
        $data = json_decode($response->getContent(), true);
        if (isset($data['url']) && $data['url']) {
            $fileId = $this->insertFileReferenceByUrl($data['url']);
            if ($fileId !== 0) {
                $pos = strpos($data['url'], 'files/');
                $baseUrl = '/';
                if ($pos !== false) {
                    $potentialBase = substr($data['url'], 0, $pos);
                    if ($potentialBase !== '') {
                        $baseUrl = $potentialBase;
                    }
                }
                $filename = basename($data['url']);
                $data['url'] = $baseUrl . 'c4g_forum/file/' . $fileId . '/' . $filename;
                $response->setData($data);
            }
        }
        return $response;
    }

    private function insertFileReferenceByUrl(string $url): int
    {
        $pos = strpos($url, 'files/');
        if ($pos !== false) {
            $relativeUrl = substr($url, $pos);
        } else {
            $relativeUrl = $url;
        }

        \Contao\System::getContainer()->get('monolog.logger.contao')->info("ForumUploadController: Attempting to add resource $relativeUrl to DBAFS.");
        $result = Dbafs::addResource($relativeUrl);
        if ($result !== false) {
            \Contao\System::getContainer()->get('monolog.logger.contao')->info("ForumUploadController: Added resource $relativeUrl. UUID: " . bin2hex($result->uuid));
            $database = \Contao\Database::getInstance();
            $statement = $database->prepare(
                'INSERT INTO tl_c4g_forum_upload (tstamp, fileUuid) VALUES (?, ?)'
            );
            $statement->execute(...[time(), $result->uuid]);
            $statement = $database->prepare('SELECT LAST_INSERT_ID() as id');
            $result = $statement->execute()->fetchAssoc();
            return (int) $result['id'];
        }
        \Contao\System::getContainer()->get('monolog.logger.contao')->error("ForumUploadController: Failed to add resource $relativeUrl to DBAFS.");
        return 0;
    }
}