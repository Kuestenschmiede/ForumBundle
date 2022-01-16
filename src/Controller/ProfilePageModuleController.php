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
use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\CoreBundle\Resources\contao\models\C4gSettingsModel;
use con4gis\ForumBundle\Classes\PageUrlService;
use con4gis\GroupsBundle\Resources\contao\models\MemberModel;
use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Database;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePageModuleController extends AbstractFrontendModuleController
{
    private const DAYS_730 = 63072000;
    private const DAYS_60 = 5184000;
    private const DAYS_14 = 1209600;
    private const HOURS_48 = 172800;
    private const MINUTES_120 = 7200;

    private const YEAR = 31536000;
    private const MONTH = 2592000;
    private const WEEK = 604800;
    private const DAY = 86400;
    private const HOUR = 3600;
    private const MINUTE = 60;

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $pageUrlService = new PageUrlService();
        $alias = $pageUrlService->getAlias();
        if ($alias === '') {
            throw new PageNotFoundException();
        }
        $database = Database::getInstance();
        $statement = $database->prepare(
            'SELECT * FROM tl_member WHERE login = 1 AND LOWER(username) = ? LIMIT 1'
        );
        $member = $statement->execute($alias)->fetchAssoc();
        if ($member === false || count($member) === 0) {
            throw new PageNotFoundException();
        }
        System::loadLanguageFile('frontendModules');

        ResourceLoader::loadCssResource('bundles/con4gisforum/dist/css/c4gForum.min.css');
        ResourceLoader::loadCssResource('bundles/con4giscore/vendor/jQuery/jquery-ui-1.12.1.custom/jquery-ui.min.css');
        $settings = C4gSettingsModel::findSettings();
        if ($settings && $settings->c4g_appearance_themeroller_css) {
            $objFile = \FilesModel::findByUuid($settings->c4g_appearance_themeroller_css);
            ResourceLoader::loadCssResource($objFile->path);
        } else if ($settings && $settings->c4g_uitheme_css_select) {
            $theme = $settings->c4g_uitheme_css_select;
            ResourceLoader::loadCssResource('bundles/con4giscore/vendor/jQuery/ui-themes/themes/' . $theme . '/jquery-ui.css');
        } else {
            ResourceLoader::loadCssResource('bundles/con4giscore/vendor/jQuery/ui-themes/themes/base/jquery-ui.css');
        }

        $statement = $database->prepare(
            'SELECT COUNT(0) as posts FROM tl_c4g_forum_post WHERE author = ?'
        );
        $member['postCount'] = $statement->execute($member['id'])->fetchAssoc()['posts'];
        $statement = $database->prepare(
            'SELECT COUNT(0) as threads FROM tl_c4g_forum_thread WHERE author = ?'
        );
        $member['threadCount'] = $statement->execute($member['id'])->fetchAssoc()['threads'];
        $member['avatarUrl'] = StringUtil::deserialize($member['memberImage'])[0];

        switch ($model->c4g_forum_show_realname) {
            case 'UU';
                $member['name'] = $member['username'];
                break;
            case 'FF';
                $member['name'] = $member['firstname'];
                break;
            case 'LL';
                $member['name'] = $member['lastname'];
                break;
            case 'FL';
                $member['name'] = $member['firstname'] . ' ' . $member['lastname'];
                break;
            case 'LF';
                $member['name'] = $member['lastname'] . ', ' . $member['firstname'];
                break;
            default;
                break;
        }

        $member['dateAdded'] = date($GLOBALS['TL_CONFIG']['dateFormat'], $member['dateAdded']);
        $member['lastOnline'] = $this->calculateLastOnline($member['lastLogin']);

        if ($model->c4g_forum_show_ranks) {
            $ranks = StringUtil::deserialize($model->c4g_forum_member_ranks, true);
            foreach ($ranks as $rank) {
                if ($member['postCount'] >= $rank['rank_min']) {
                    $member['rank'] = $rank['rank_name'];
                }
            }
        }

        $stats = StringUtil::deserialize($model->c4g_forum_user_statistics, true);
        if ($stats !== []) {
            $userStatistics = [];
            System::loadLanguageFile('tl_member');
            Controller::loadDataContainer('tl_member');
            foreach ($stats as $stat) {
                $translation = $GLOBALS['TL_DCA']['tl_member']['fields'][$stat]['label'][0] ?:
                    $GLOBALS['TL_LANG']['tl_member'][$stat][0] ?: '';
                if ($translation !== '' && (string) $member[$stat] !== '') {
                    $userStatistics[$translation] = (string) $member[$stat];
                }
            }
            $member['user_statistics'] = $userStatistics;
        }

        $statement = $database->prepare(
            'SELECT p.text, p.creation, t.name as threadName, t.id as tid, f.name as forumName, f.id as fid FROM tl_c4g_forum_post p '.
            'JOIN tl_c4g_forum_thread t ON p.pid = t.id JOIN tl_c4g_forum f ON t.pid = f.id '.
            'WHERE p.author = ? ORDER BY p.tstamp DESC LIMIT 10'
        );
        $posts = $statement->execute($member['id'])->fetchAllAssoc();
        if ((int) $model->c4g_forum_module_page > 0) {
            $pageModel = PageModel::findByPk((int) $model->c4g_forum_module_page);
        } else {
            $pageModel = null;
        }
        foreach ($posts as $key => $post) {
            $post['text'] = strip_tags(htmlspecialchars_decode($post['text']), '<p>');
            if ($pageModel !== null) {
                $post['threadUrl'] = $pageModel->getAbsoluteUrl().'?state=forum:'.$post['fid'].';readthread:'.$post['tid'];
            } else {
                $post['threadUrl'] = '';
            }
            $post['creation'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $post['creation']);
            $posts[$key] = $post;
        }

        $member = $this->filterUndesirableColumns($member);
        $template->language = $GLOBALS['TL_LANG']['c4g_forum']['profile'];
        $template->member = $member;
        $template->posts = $posts;
        return $template->getResponse();
    }

    private function filterUndesirableColumns(array $member): array
    {
        unset($member['id']);
        unset($member['tstamp']);
        unset($member['password']);
        unset($member['secret']);
        unset($member['memberImage']);
        unset($member['session']);
        unset($member['locked']);
        unset($member['firstname']);
        unset($member['lastname']);
        unset($member['username']);
        unset($member['useTwoFactor']);
        unset($member['lastLogin']);
        return $member;
    }

    private function calculateLastOnline(int $lastOnline): string
    {
        $difference = time() - $lastOnline;
        if ($difference >= static::DAYS_730) {
            return sprintf($GLOBALS['TL_LANG']['c4g_forum']['profile']['years_ago'], floor($difference / static::YEAR));
        } elseif ($difference >= static::DAYS_60) {
            return sprintf($GLOBALS['TL_LANG']['c4g_forum']['profile']['months_ago'], floor($difference / static::MONTH));
        } elseif ($difference >= static::DAYS_14) {
            return sprintf($GLOBALS['TL_LANG']['c4g_forum']['profile']['weeks_ago'], floor($difference / static::WEEK));
        } elseif ($difference >= static::HOURS_48) {
            return sprintf($GLOBALS['TL_LANG']['c4g_forum']['profile']['days_ago'], floor($difference / static::DAY));
        } elseif ($difference >= static::MINUTES_120) {
            return sprintf($GLOBALS['TL_LANG']['c4g_forum']['profile']['hours_ago'], floor($difference / static::HOUR));
        } elseif ($difference >= static::HOUR) {
            return $GLOBALS['TL_LANG']['c4g_forum']['profile']['one_hour_ago'];
        } else {
            return sprintf($GLOBALS['TL_LANG']['c4g_forum']['profile']['minutes_ago'], floor($difference / static::MINUTE));
        }
    }
}