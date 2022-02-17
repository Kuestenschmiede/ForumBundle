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
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
    public function __construct(ContaoFramework $contaoFramework)
    {
        $contaoFramework->initialize();
    }

    public function returnSitemap(Request $request, string $filename) : Response {
        $template = new FrontendTemplate('forum_sitemap', 'text/xml');
        $database = Database::getInstance();
        $statement = $database->prepare(
            'SELECT * FROM tl_module WHERE type = ? AND c4g_forum_sitemap = 1 AND c4g_forum_sitemap_filename = ?'
        );
        $module = $statement->execute('c4g_forum', str_replace('.xml', '', $filename))->fetchAssoc();
        if (!empty($module)) {
            $idfield = 'pid';

            $url = parse_url($_SERVER['HTTP_REFERER']);
            $scheme = '';
            if ($url['scheme']) {
                $scheme = $url['scheme'] . '://';
            }
            $path = '';
            if ($url['path']) {
                $path = $url['path'];
            }
            $this->frontendUrl = $scheme . $url['host'] . $path;

            $helper = new C4GForumHelper($database);
            $forums = $helper->getForumsFromDB($module['c4g_forum_startforum'], true, true, $idfield, false);
            $pages = [];

            $contents = unserialize($module['c4g_forum_sitemap_contents']);
            if (in_array('INTROS', $contents)) {
                foreach ($forums as $forum) {
                    if (!$forum['sitemap_exclude'] && $forum['use_intropage']) {
                        $pages[] = $helper->getUrlForForum(
                            $forum['id'],
                            2,
                            false,
                            'forumbox',
                            'forum'
                        );
                    }
                }
            }

            if (in_array('FORUMS', $contents)) {
                foreach ($forums as $forum) {
                    if (!$forum['sitemap_exclude']) {
                        if ($forum['subforums'] > 0) {
                            $urltype = 1;
                        } else {
                            $urltype = 0;
                        }
                        $pages[] = $helper->getUrlForForum(
                            $forum['id'],
                            $urltype,
                            false,
                            'forumbox',
                            'forum'
                        );
                    }
                }
            }

            if (in_array('THREADS', $contents)) {
                foreach ($forums as $forum) {
                    if (!$forum['sitemap_exclude'] &&
                        $helper->checkPermissionWithData('readpost', $forum['member_groups'], $forum['admin_groups'],
                            $forum['guest_rights'], $forum['member_rights'], $forum['admin_rights'])) {
                        $threads = $helper->getThreadsFromDB($forum['id']);
                        foreach ($threads as $thread) {
                            $pages[] = $helper->getUrlForThread(
                                $thread['id'],
                                0,
                                false,
                                'forumbox'
                            );
                        }
                    }
                }
            }
            $template->pages = $pages;
        }

        return $template->getResponse();
    }
}