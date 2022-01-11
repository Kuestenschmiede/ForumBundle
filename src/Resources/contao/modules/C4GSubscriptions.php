<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\ForumBundle\Resources\contao\modules;

use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\CoreBundle\Resources\contao\models\C4gSettingsModel;
use con4gis\ForumBundle\Resources\contao\models\C4GForumSubscriptionModel;
use con4gis\ForumBundle\Resources\contao\models\C4GThreadSubscriptionModel;
use Patchwork\Utf8;

/**
 * Class C4GSubscriptionOverview
 * @package con4gis\ForumBundle\Resources\contao\modules
 */
class C4GSubscriptions extends \Module
{
    protected $strTemplate = 'mod_c4g_forum_subscriptions';

    /**
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['c4g_forum_subscription'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (!$_POST && $this->redirectBack && ($strReferer = $this->getReferer()) != \Environment::get('request'))
        {
            $_SESSION['LAST_PAGE_VISITED'] = $strReferer;
        }

        return parent::generate();
    }

    protected function compile()
    {
        ResourceLoader::loadJavaScriptResource("/bundles/con4gisforum/dist/js/subscriptions.js", ResourceLoader::HEAD);
        ResourceLoader::loadCssResource("/bundles/con4gisforum/dist/css/subscriptions.min.css");
        ResourceLoader::loadJavaScriptResource('assets/jquery/js/jquery.min.js|async|static', $location = ResourceLoader::JAVASCRIPT, $key = 'c4g_jquery');
        ResourceLoader::loadJavaScriptResource('bundles/con4giscore/vendor/jQuery/jquery-ui-1.12.1.custom/jquery-ui.min.js|async|static',
            ResourceLoader::JAVASCRIPT,
            'c4g_jquery_ui'
        );
        ResourceLoader::loadCssResource('bundles/con4giscore/vendor/jQuery/jquery-ui-1.12.1.custom/jquery-ui.min.css', 'c4g_jquery_ui_core');
        // Set the JQuery UI theme to be used
        if (!ResourceLoader::isJqueryUiThemeLoaded()) {
            ResourceLoader::loadJqueryUiTheme(C4gSettingsModel::findSettings()->c4g_uitheme_css_select);
        }

        \System::loadLanguageFile('subscriptions');
        $template = $this->Template;
        $template->sub_forum_headline = $this->sub_forum_headline !== '' ? $this->sub_forum_headline : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUBFORUM_SUBS_HEAD'];
        $template->sub_forum_change_sub_caption = $this->sub_forum_change_sub_caption !== '' ? $this->sub_forum_change_sub_caption : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUBFORUM_SUBS_CHANGE'];
        $template->sub_forum_delete_sub_caption = $this->sub_forum_delete_sub_caption !== '' ? $this->sub_forum_delete_sub_caption : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['SUBFORUM_SUBS_DELETE'];
        $template->thread_headline = $this->thread_headline !== '' ? $this->thread_headline : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['THREAD_SUBS_HEAD'];
        $template->thread_change_sub_caption = $this->thread_change_sub_caption !== '' ? $this->thread_change_sub_caption : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['THREAD_SUBS_CHANGE'];
        $template->thread_delete_sub_caption = $this->thread_delete_sub_caption !== '' ? $this->thread_delete_sub_caption : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['THREAD_SUBS_DELETE'];
        $template->no_subs_text = $this->no_subs_text !== '' ? $this->no_subs_text : $GLOBALS['TL_LANG']['C4G_FORUM_SUBS']['NO_SUBS_TEXT'];

        $user = \Contao\FrontendUser::getInstance();

        $template->forumSubs = C4GForumSubscriptionModel::findBy('member', $user->id);
        $template->threadSubs = C4GThreadSubscriptionModel::findBy('member', $user->id);
    }
}