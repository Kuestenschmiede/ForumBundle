<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 5
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

namespace con4gis\ForumBundle\Resources\contao\modules;

use con4gis\ForumBundle\Resources\contao\models\C4GForumSubscriptionModel;
use con4gis\ForumBundle\Resources\contao\models\C4GThreadSubscriptionModel;

/**
 * Class C4GSubscriptionOverview
 * @package con4gis\ForumBundle\Resources\contao\modules
 */
class C4GSubscriptionOverview extends \Module
{
    protected $strTemplate = 'mod_c4g_forum_subscription_overview';

    protected function compile()
    {
        $template = $this->Template;
        $template->sub_forum_headline = $his->sub_forum_headline !== '' ? $this->sub_thread_headline : 'Bereich-Abonnements';
        $template->sub_forum_change_sub_caption = $his->sub_forum_change_sub_caption !== '' ? $his->sub_forum_change_sub_caption : 'Abonnement ändern';
        $template->sub_forum_delete_sub_caption = $his->sub_forum_delete_sub_caption !== '' ? $his->sub_forum_delete_sub_caption : 'Deabonnieren';
        $template->sub_thread_headline = $his->sub_thread_headline !== '' ? $this->sub_thread_headline : 'Themen-Abonnements';
        $template->sub_thread_change_sub_caption = $his->sub_thread_change_sub_caption !== '' ? $his->sub_thread_change_sub_caption : 'Abonnement ändern';
        $template->sub_thread_delete_sub_caption = $his->sub_thread_delete_sub_caption !== '' ? $his->sub_thread_delete_sub_caption : 'Deabonnieren';

        $user = $this->User;

        $template->forumSubs = C4GForumSubscriptionModel::findBy('member', $user->id);
        $template->threadSubs = C4GThreadSubscriptionModel::findBy('member', $user->id);
    }
}