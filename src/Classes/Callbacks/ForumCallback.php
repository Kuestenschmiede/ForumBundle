<?php

namespace con4gis\ForumBundle\Classes\Callbacks;

use con4gis\ForumBundle\Classes\C4GForumTicketStatus;
use con4gis\CoreBundle\Classes\C4GVersionProvider;
use Contao\Backend;
use Contao\BackendUser;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\System;

class ForumCallback
{
    public function loadMapProfiles()
    {
        if (C4GVersionProvider::isInstalled('con4gis/maps')) {
            $arrProfiles = \con4gis\MapsBundle\Resources\contao\models\C4gMapProfilesModel::findAll();
            $arrResult = [];
            if ($arrProfiles) {
                foreach ($arrProfiles as $profile) {
                    $arrResult[$profile->id] = $profile->name;
                }
            }
            return $arrResult;
        } else {
            return [];
        }
    }

    public function setMailTextDefault($varValue, DataContainer $dc)
    {
        if (empty($varValue)) {
            return $GLOBALS['TL_LANG']['tl_c4g_forum']['default_subscription_text'];
        } else {
            return $varValue;
        }
    }

    public function copyPageWithSubpages($row, $href, $label, $title, $icon, $attributes, $table)
    {
        $db = System::getContainer()->get('database_connection');
        $id = $db->fetchOne("SELECT id FROM tl_c4g_forum WHERE pid=?", [$row['id']]);

        if ($id) {
            return '<a href="' . Backend::addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
        } else {
            return Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)) . ' ';
        }
    }

    public function updateDCA(DataContainer $dc)
    {
        $db = System::getContainer()->get('database_connection');
        $helper = new \con4gis\ForumBundle\Classes\C4GForumHelper($db);
        $GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['guest_rights']['default'] =
            $helper->getGuestDefaultRights();
        $GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['member_rights']['default'] =
            $helper->getMemberDefaultRights();
        $GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['admin_rights']['default'] =
            $helper->getAdminDefaultRights();

        if (!$dc->id) {
            return;
        }

        $row = $db->fetchAssociative("SELECT use_intropage, map_override_locationstyle FROM tl_c4g_forum WHERE id=?", [$dc->id]);
        if ($row) {
            if ($row['use_intropage']) {
                $GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['default'] =
                    $GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['with_intropage'];
            }
        }

        if (C4GVersionProvider::isInstalled('con4gis/maps')) {
            $c4gMapsFields = '{maps_legend:hide},enable_maps;';
            $GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['default'] =
                str_replace('{expert_legend', $c4gMapsFields . '{expert_legend',
                    $GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['default']);

            if ($row) {
                if ($row['map_override_locationstyle']) {
                    $GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps'] =
                        str_replace('map_override_locationstyle,',
                            'map_override_locationstyle,map_override_locstyles,', $GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps']);
                }
            }
        }
    }

    public function getAllLocStyles(DataContainer $dc)
    {
        $db = System::getContainer()->get('database_connection');
        $locStyles = $db->fetchAllAssociative("SELECT id,name FROM tl_c4g_map_locstyles ORDER BY name");
        $return = [];
        foreach ($locStyles as $style) {
            $return[$style['id']] = $style['name'];
        }
        return $return;
    }

    public function get_label($row, $label, DataContainer $dc, $args)
    {
        return $args[0];
    }

    public function remove_bb($row, $href, $label, $title, $icon, $attributes)
    {
        return '';
    }

    public function decodeTags($varValue, DataContainer $dc)
    {
        return $varValue;
    }

    public function forumThread($row, $href, $label, $title, $icon)
    {
        $href .= "&amp;id=" . $row['id'];
        return '<a href="' . Backend::addToUrl($href) . '" title="' . specialchars($title) . '">' . Image::getHtml($icon, $label) . '</a> ';
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $tid = Input::get('tid');
        if (strlen($tid)) {
            $this->toggleVisibility($tid, (Input::get('state') == 1));
            Backend::redirect(Backend::getReferer());
        }

        $user = BackendUser::getInstance();
        if (!$user->isAdmin && !$user->hasAccess('tl_c4g_forum::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . Backend::addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
    }

    public function toggleVisibility($intId, $blnVisible)
    {
        $user = BackendUser::getInstance();
        if (!$user->isAdmin && !$user->hasAccess('tl_c4g_forum::published', 'alexf')) {
            System::getContainer()->get('monolog.logger.contao')->error('Not enough permissions to publish/unpublish C4GMaps ID "' . $intId . '"', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, 'tl_c4g_forum toggleVisibility')]);
            Backend::redirect('contao/main.php?act=error');
        }

        // Versions are tricky in Contao 5 without Backend class, but we can try using the Model or the legacy way if Backend is available.
        // For now, let's stick to the database update.
        $db = System::getContainer()->get('database_connection');
        $db->executeStatement("UPDATE tl_c4g_forum SET tstamp=?, published=? WHERE id=?", [time(), ($blnVisible ? 1 : ''), $intId]);
    }

    public function getGuestRightList(DataContainer $dc)
    {
        $db = System::getContainer()->get('database_connection');
        $helper = new \con4gis\ForumBundle\Classes\C4GForumHelper($db);
        $rights = $helper->getGuestRightList();
        $return = [];
        foreach ($rights as $right) {
            $return[$right] = $GLOBALS['TL_LANG']['tl_c4g_forum']['right_' . $right];
        }
        return $return;
    }

    public function getRightList(DataContainer $dc)
    {
        $db = System::getContainer()->get('database_connection');
        $helper = new \con4gis\ForumBundle\Classes\C4GForumHelper($db);
        $rights = $helper->getRightList();
        $return = [];
        foreach ($rights as $right) {
            $return[$right] = $GLOBALS['TL_LANG']['tl_c4g_forum']['right_' . $right];
        }
        return $return;
    }

    public function onSubmit(DataContainer $dc)
    {
        if ($dc->activeRecord) {
            $db = System::getContainer()->get('database_connection');
            $helper = new \con4gis\ForumBundle\Classes\C4GForumHelper($db);
            $helper->updateForumRightsAndGroupInheritance($dc->activeRecord->id, $dc->activeRecord->pid);
            $helper->updateMapEnabledInheritance($dc->activeRecord->id, $dc->activeRecord->pid);
        }
    }

    public function pickLinkUrl(DataContainer $dc)
    {
        return ' <a href="contao/page.php?do=' . Input::get('do') . '&amp;table=' . $dc->table . '&amp;field=' . $dc->field . '&amp;value=' . str_replace(array('{{link_url::', '}}'), '', $dc->value) . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']) . '" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])) . '\',\'url\':this.href,\'id\':\'' . $dc->field . '\',\'tag\':\'ctrl_' . $dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '') . '\',\'self\':this});return false">' . Image::getHtml('pickpage.svg', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
    }

    public function get_maps(DataContainer $dc)
    {
        $db = System::getContainer()->get('database_connection');
        $maps = $db->fetchAllAssociative("SELECT * FROM tl_c4g_maps WHERE location_type='map' AND published=1");
        $return = [];
        foreach ($maps as $map) {
            $return[$map['id']] = $map['name'];
        }
        return $return;
    }

    public static function onDeleteForum(DataContainer $dc)
    {
        if ($dc->activeRecord) {
            if ($dc->activeRecord->id > 0) {
                // $db = System::getContainer()->get('database_connection');
                // $helper = new \con4gis\ForumBundle\Classes\C4GForumHelper($db);
                // TODO move old threads and posts to a paper bin
            }
        }
    }
    public function saveDefaultPost(DataContainer $dc)
    {
        if (!$dc->activeRecord) {
            return;
        }
        $db = System::getContainer()->get('database_connection');
        $thread = $db->fetchAssociative("SELECT pid,last_post_id FROM tl_c4g_forum_thread WHERE id=?", [$dc->activeRecord->pid]);
        $numPosts = $db->fetchOne("SELECT COUNT(id) FROM tl_c4g_forum_post WHERE pid = ?", [$dc->activeRecord->pid]);

        $arrSet = [];
        $arrSet['forum_id'] = $thread['pid'];
        $arrSet['author'] = $db->fetchOne("SELECT default_author FROM tl_c4g_forum WHERE id=?", [$arrSet['forum_id']]);
        $arrSet['post_number'] = $numPosts !== null ? $numPosts : 1;

        $arrSetParent = [];
        $arrSetParent['last_post_id'] = $dc->activeRecord->id;
        $arrSetParent['state'] = $dc->activeRecord->state;
        $arrSetParent['edit_last_time'] = $dc->activeRecord->creation;
        $arrSetParent['posts'] = $arrSet['post_number'];

        if ($dc->activeRecord->subject == '') {
            $arrSet['subject'] = $GLOBALS['TL_LANG']['tl_c4g_forum_post']['state_change'] . $dc->activeRecord->state;
        }

        if ($arrSet['author']) {
            $db->update('tl_c4g_forum_post', $arrSet, ['id' => $dc->activeRecord->id]);
            $db->update('tl_c4g_forum_thread', $arrSetParent, ['id' => $dc->activeRecord->pid]);
        }
    }

    public function loadPostLabel($arrRow)
    {
        $db = System::getContainer()->get('database_connection');
        $thread = $db->fetchAssociative('SELECT * FROM tl_c4g_forum_thread WHERE id=?', [$arrRow['pid']]);
        if ($thread && $thread['state'] == 1) {
            $db->update('tl_c4g_forum_thread', ['state' => 2], ['id' => $arrRow['pid']]);
        }
        return $arrRow['text'];
    }

    public function forumPost($row, $href, $label, $title, $icon)
    {
        $href .= "&amp;id=" . $row['id'];
        return '<a href="' . Backend::addToUrl($href) . '" title="' . specialchars($title) . '">' . Image::getHtml($icon, $label) . '</a> ';
    }

    public function saveDefaultThread(DataContainer $dc)
    {
        if (!$dc->activeRecord) {
            return;
        }

        $db = System::getContainer()->get('database_connection');
        $author = $db->fetchOne("SELECT default_author FROM tl_c4g_forum WHERE id=?", [$dc->activeRecord->pid]);

        if ($author) {
            $db->update('tl_c4g_forum_thread', ['author' => $author, 'creation' => time()], ['id' => $dc->id]);
        }
    }

    public function getThreadLabel($arrRow)
    {
        $result = "";
        $db = System::getContainer()->get('database_connection');
        $settings = $db->fetchAssociative("SELECT * FROM tl_c4g_settings LIMIT 1");

        if ($settings && $settings['c4g_forum_type']) {
            if ($settings['c4g_forum_type'] == "DISCUSSIONS") {
                $result .= $GLOBALS['TL_LANG']['tl_c4g_forum_thread']['counter_caption_thread'];
            } else if ($settings['c4g_forum_type'] == "TICKET") {
                $result .= $GLOBALS['TL_LANG']['tl_c4g_forum_thread']['counter_caption_ticket'];
            }
        }

        $result .= sprintf('%04d', $arrRow['id']) . '] ';
        $author = $db->fetchAssociative('SELECT * FROM tl_member WHERE id=?', [$arrRow['author']]);
        $result .= $arrRow['name'] . ': ';
        $result .= date($GLOBALS['TL_CONFIG']['dateFormat'], intval($arrRow['tstamp'])) . ' ';
        $result .= date($GLOBALS['TL_CONFIG']['timeFormat'], intval($arrRow['tstamp'])) . ' ';
        $result .= $author ? $author['username'] : '';
        $state = C4GForumTicketStatus::getState($arrRow['state']);
        if ($state) {
            $result .= ' : (<b>' . $state . '</b>)';
        }

        return $result;
    }

    public function getThreadOptions(DataContainer $dc)
    {
        return array(
            1 => C4GForumTicketStatus::getState(1),
            2 => C4GForumTicketStatus::getState(2),
            3 => C4GForumTicketStatus::getState(3),
            4 => C4GForumTicketStatus::getState(4)
        );
    }

    public function getPostOptions(DataContainer $dc)
    {
        return $this->getThreadOptions($dc);
    }

    public function getThreadDatasets(DataContainer $dc)
    {
        $pid = Input::get('id');
        $db = System::getContainer()->get('database_connection');
        if ($pid) {
            $childs = $this->getForumChilds($pid);
            $root = $db->fetchFirstColumn("SELECT id FROM tl_c4g_forum_thread WHERE pid=?", [$pid]);
            $root = array_merge($root, $childs);
            if (empty($root)) {
                $root = array('0');
            }
        } else {
            $GLOBALS['TL_CSS'][] = "bundles/con4gisforum/dist/css/c4gForumBackendButton.min.css";
            $root = $db->fetchFirstColumn("SELECT id FROM tl_c4g_forum_thread");
        }
        $GLOBALS['TL_DCA']['tl_c4g_forum_thread']['list']['sorting']['root'] = $root;
    }

    public function getForumChilds($pid)
    {
        $db = System::getContainer()->get('database_connection');
        $childs = $db->fetchAllAssociative("SELECT id FROM tl_c4g_forum WHERE pid=?", [$pid]);

        $return = array();

        foreach ($childs as $child) {
            $return = array_merge($return, $db->fetchFirstColumn("SELECT id FROM tl_c4g_forum_thread WHERE pid=?", [$child['id']]));
            $return = array_merge($return, $this->getForumChilds($child['id']));
        }
        return $return;
    }
}
