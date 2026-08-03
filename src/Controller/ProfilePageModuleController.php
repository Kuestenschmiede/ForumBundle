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

use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\CoreBundle\Resources\contao\models\C4gSettingsModel;
use con4gis\ForumBundle\Classes\C4GForumHelper;
use con4gis\ForumBundle\Classes\PageUrlService;
use con4gis\ForumBundle\Models\C4gForumMember;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Module;
use Contao\System;

class ProfilePageModuleController extends Module
{
    protected $strTemplate = 'mod_c4g_forum_profile_page';

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

    public function generate()
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest())) {
            $objTemplate = new \Contao\BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['profile_page_module'][0] . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        $pageUrlService = new PageUrlService();
        $alias = $pageUrlService->getAlias();

        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
        if ($request) {
            $request->attributes->set('parameters', '');
            $routeParams = $request->attributes->get('_route_params');
            if (is_array($routeParams) && isset($routeParams['parameters'])) {
                $routeParams['parameters'] = '';
                $request->attributes->set('_route_params', $routeParams);
            }
        }

        if ($alias === '') {
            $this->Template->member = null;
            return;
        }

        $database = \Contao\Database::getInstance();
        $statement = $database->prepare(
            'SELECT * FROM tl_member WHERE login = 1 AND LOWER(username) = ? LIMIT 1'
        );
        $member = $statement->execute(...[$alias])->fetchAssoc();
        if ($member === false || count($member) === 0) {
            throw new PageNotFoundException();
        }
        \Contao\System::loadLanguageFile('frontendModules');

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
        $member['postCount'] = $statement->execute(...[$member['id']])->fetchAssoc()['posts'];
        $statement = $database->prepare(
            'SELECT COUNT(0) as threads FROM tl_c4g_forum_thread WHERE author = ?'
        );
        $member['threadCount'] = $statement->execute(...[$member['id']])->fetchAssoc()['threads'];
        
        $member['avatarUrl'] = '';
        if ($this->c4g_forum_show_avatars) {
            $size = [100, 100];
            if ($this->c4g_forum_avatar_size) {
                $size = \Contao\StringUtil::deserialize($this->c4g_forum_avatar_size, true);
            }
            $member['avatarUrl'] = C4GForumHelper::getAvatarByMemberId($member['id'], $size);
        }

        switch ($this->c4g_forum_show_realname) {
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

        $dateAdded = (int)($member['dateAdded'] ?: $member['tstamp']);
        $member['dateAdded'] = date($GLOBALS['TL_CONFIG']['dateFormat'], $dateAdded);
        $lastOnline = max((int)($member['lastLogin'] ?? 0), (int)($member['currentLogin'] ?? 0), (int)($member['tstampLastAction'] ?? 0));
        $member['lastOnline'] = $this->calculateLastOnline($lastOnline);

        if ($this->c4g_forum_show_ranks) {
            $ranks = \Contao\StringUtil::deserialize($this->c4g_forum_member_ranks, true);
            foreach ($ranks as $rank) {
                if ($member['postCount'] >= $rank['rank_min']) {
                    $member['rank'] = $rank['rank_name'];
                }
            }
        }

        $stats = \Contao\StringUtil::deserialize($this->c4g_forum_user_statistics, true);
        if ($stats !== []) {
            $userStatistics = [];
            \Contao\System::loadLanguageFile('tl_member');
            (new \Contao\DcaLoader('tl_member'))->load();
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
        $posts = $statement->execute(...[$member['id']])->fetchAllAssoc();
        if ((int) $this->c4g_forum_module_page > 0) {
            $pageModel = \Contao\PageModel::findByPk((int) $this->c4g_forum_module_page);
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
        $this->Template->language = $GLOBALS['TL_LANG']['c4g_forum']['profile'];
        $this->Template->member = $member;
        $this->Template->posts = $posts;
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
        if ($lastOnline <= 0) {
            return '-';
        }
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
