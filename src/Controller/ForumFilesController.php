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

use con4gis\ForumBundle\Classes\C4GForumHelper;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\FrontendUser;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumFilesController
{
    public function __construct(ContaoFramework $framework)
    {
        $framework->initialize();
    }

    /**
     * @Route(
     *     "/c4g_forum/file/{fileId}",
     *     name="c4g_forum_file",
     *     methods={"GET"},
     *     requirements={"fileId"="\d+"}
     * )
     * @param Request $request
     * @param int $fileId
     * @return BinaryFileResponse
     */
    public function serveFile(Request $request, int $fileId): BinaryFileResponse
    {
        $database = Database::getInstance();
        $statement = $database->prepare('SELECT * FROM tl_c4g_forum_upload WHERE id = ?');
        $uploadRow = $statement->execute($fileId)->fetchAssoc();
        if ($uploadRow !== false) {
            $user = FrontendUser::getInstance();
            $statement = $database->prepare(
                'SELECT f.* FROM tl_files f JOIN tl_c4g_forum_upload u ON f.uuid = u.fileUuid WHERE u.id = ?'
            );
            $fileRow = $statement->execute($fileId)->fetchAssoc();
            if ($fileRow !== false) {
                if ($user->id > 0) {
                    return new BinaryFileResponse('../'.$fileRow['path']);
                } else {
                    throw new RedirectResponseException('../../'.$fileRow['path']);
                }
            }
        }

        throw new PageNotFoundException();
    }
}