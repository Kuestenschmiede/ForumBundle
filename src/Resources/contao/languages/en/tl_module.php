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

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_type']              = array(
    'Forum type',
    'Change type to change the wording. On informations threads are questions and posts are comments. Ticketsystem changes the forum to a closed ticketsystem.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_size']              = array(
    'Size (width, height)',
    'Size of division (DIV) wherein the forum is displayed. The size is calculated automatically when you don\'t enter values here.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_scroll']            = array(
    'Size of the scrollable area of the threadlist (width, height)',
    'Leave empty if you don\'t want scrollbars.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_startforum']        = array(
    'Origin',
    'Choose the parent forum to start from. Leave empty to see all defined forums.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_navigation']   = array(
    'Navigation',
    'Choose the navigation for the forum.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_threadclick']       = array(
    'Thread click action',
    'Choose the action to be performed when a thread is clicked.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_realname']     = array(
    'Use real-names instead of usernames',
    'Choose if and how you want to display the real-name of the users, instead of their usernames'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_postsort']          = array(
    'Post order',
    'Choose the order of the posts in the post list of a thread.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxlength'] = array(
    'Length of titles',
    'Choose the count of characters in a title till a wordwrap.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_collapsible_posts'] = array(
    'Make posts collapsible',
    'Choose if and how you want the posts to be collapsible.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb']        = array(
    'Show breadcrumb',
    'Check this if you want the breadcrumb to be displayed.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_hide_intropages']   = array(
    'Hide intropages',
    'Check this to hide intropages despite they have been defined. This can make sense if you want to realise different views on your forum with several frontend modules.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jumpTo']            = array(
    'Redirect page on denied permission',
    'Please choose the page to which visitors will be redirected when the permission for a requested action is not granted.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_language']          = array(
    'Frontend-Language',
    'Empty=determine automatically, de=German, en=English.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual']      = array(
    'multilingual entries',
    'you can fill out some fields in user language. With right language switch, you can change the language.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_languages'] = array(
    'Frontend languages for all multilanguages fields',
    'Select your languages.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_multilingual_language'] = array(
    'Frontend languages',
    ''
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes']                       = array(
    'Use Editor',
    'Deactivate this checkbox, if you do not want to use Editor in your forum. Please take note, that deactivating Editor after they have already been used, may cause ugly formating-errors.'
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_last_post_on_new'] = array("Show last post on create new one", "");

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_enabled'] = array("Enable rating", "Enables a five star rating system, when writing posts.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rating_color'] = array("Ratingstar color", "Changes the color of ratingstars. Default: global textcolor");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_reaction_enabled'] = ["Enable reactions", "Allows \"Like\" information on contributions of other members."];
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_module_page'] = ["Forum module page", "The page where the forum module is embedded. Is needed for linking."];
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_user_profile_page'] = ["Profile module page", "The page where the profile module is embedded. Is needed for linking."];

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_pn_button']                = array('Private messages','Show button for private messages');
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sub_title']                     = array('Name for subscription','Name shown in the subject of a subscription-mail');
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_user_statistics'] = ['Other member data to be displayed','Displays additional member data in the post.'];
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_toolbaritems']   = array('WYSIWYG-Editor Toolbar Buttons', '');
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_imguploadpath']  = array('Image Upload-Folder', 'Decide where uploaded images should be stored. An additional folder named by date is created in here');
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_editor_fileuploadpath'] = array('File Upload-Folder', 'Decide where uploaded files should be stored. An additional folder named by date is created in here');
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_autourl'] = array(
    'Automaticaly recognize URLs',
    'This Feature automatically recognites typed URLs and converts them into functional links.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor_option']['no']           = "Do not use editor";

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_text']     = array(
    'Box navigation: display forum name',
    'Check this to show the forum name in the box navigation.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_subtext']  = array(
    'Box navigation: display details',
    'Check this to show the number of child forums, number of threads and number of posts in the box navigation.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_lastpost'] = array(
    'Box navigation: display last post information',
    'Check this to display information regarding the last post in the forum.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_lastthread'] = array(
    'Box navigation: display last thread information',
    'Check this to display information regarding the last thread created in the forum.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_center']   = array(
    'Center box navigation',
    'Check this to center the block containing the boxes.'
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui']                   = array(
    'Use jQuery UI',
    'Uncheck this to deactivate jQuery UI completely. The library is not loaded and all jQuery UI dependent functionality is deactivated!'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_lib']               = array(
    'Load jQuery UI library',
    'Uncheck this if you are already loading the jQuery UI library by yourself: please check that you use a compatible version of the library!'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_select']     = array(
    'jQuery UI ThemeRoller CSS theme',
    'Select a standart UI-Theme.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_uitheme_css_src']        = array(
    'jQuery UI ThemeRoller CSS file',
    'Optionally: select the CSS file you created with the jQuery UI ThemeRoller.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogsize']             = array(
    'Size of dialogs (width, height)',
    'Leave empty to use default values. Has no meaning if you use embedded dialogs.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_buttons_jqui_layout']    = array(
    'Use jQuery UI Layout for the toolbar buttons',
    'Check this to use jQuery-UI Buttons, otherwise links are created.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jqui_layout'] = array(
    'Use jQuery UI Layout for the breadcrumb buttons',
    'Check this to use jQuery-UI Buttons, otherwise links are created.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_table_jqui_layout']      = array(
    'Use jQuery UI Layout for threadlist',
    'Check this to use jQuery-UI layout for the threadlist.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_dialogs_embedded']       = array(
    'Embedded dialogs',
    'Check this if you want dialogs to be embedded into the page rather than flowing around.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_embdialogs_jqui']        = array(
    'Use jQuery UI Layout for embedded dialogs',
    'Check this to use jQuery-UI layout for the embedded dialogs. '
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_posts_jqui']             = array(
    'Use jQuery UI Layout for posts',
    'Check this to use jQuery-UI layout for displaying the posts.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_jqui_layout']      = array(
    'Use jQuery UI Layout for box navigation',
    'Check this to use jQuery-UI CSS-classes to style the navigation boxes.'
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jquery_lib']       = array(
    'Load jQuery library',
    'Check this if you are already loading jQuery by yourself. Attention: Make sure a compatible version is loaded!'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtable_lib']      = array(
    'Load jQuery DataTables library',
    'Uncheck this if you don\'t want jQuery DataTables to be loaded! Attention: you can\'t use the threadlist if jQuery DataTables is not available!'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqhistory_lib']    = array(
    'Load jQuery History library',
    'Uncheck this, if you don\'t want to use jQuery History.js functionality. Attention: unchecking this means that the backbutton doesn\'t work inside the forum. Also the browser URL field is not updated while using forum functionality, so there is no easy link functionality to forums, threads and posts.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqtooltip_lib']    = array(
    'Load jQuery Tooltip library',
    'Uncheck this to deactivate jQuery Tooltip functionality.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqscrollpane_lib'] = array(
    'Load jScrollPane library',
    'Check this if you want to use styleable scrollbars in jQuery UI dialogs.'
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_enable_maps'] = array(
    'Enable maps (requires con4gis-Maps)',
    'Check this to activate map functionality in general. Note that you also have to configure map functionality in the forum maintenance. Requires the Contao extension \'con4gis-Maps\' to be installed! '
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_root'] = array("Targetpage for sitemaplinks","");


if (version_compare(VERSION, '3', '<')) {
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
        'Create an XML sitemap',
        'Create a Google XML sitemap in the root directory.'
    );
} else {
    $GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap'] = array(
        'Create an XML sitemap',
        'Create a Google XML sitemap in the directory "share/".'
    );
}
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_filename'] = array(
    'Sitemap file name',
    'Enter the name of the sitemap file without extension .xml.'
);
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_contents'] = array(
    'Sitemap content',
    'Check the contents you want to have written to the sitemap file.'
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_jumpTo'] = array(
    'Redirect to',
    'Please select the page which contains the frontend module of the forum.'
);

$GLOBALS['TL_LANG']['tl_module']['ticketredirectsite'] = array(
    'Redirect page ticket',
    'Pick the page to which is redirected to display the ticket.'
);

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_post_count'] = array("Show post count", "Zeige die Anzahl der Beiträge unter dem Autorennamen an.");

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_online_status'] = array("Show online status", "Show a member's online status next to their name.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_member_online_time'] = array("Online time (in seconds)", "The amount of time a member remains logged in without performing an action.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_ranks'] = array("Show member ranks", "Show the ranks of members.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_member_ranks']  = array("Member ranks", "The member ranks based on number of posts.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_min']      = array("Minimum posts", "Minimum number of posts for this rank.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_name']     = array("Rank name", "The name of the rank.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_rank_language'] = array("Language", "The language of the rank.");

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_onlythreads'] = array("Checkbox for search threads only (no posts)", "");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_wholewords']  = array("Checkbox for whole words search", "");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_use_tags_in_search'] = array("Select for tags defined in your forum", "");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_forums']      = array("Select special forum", "");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_displayonly'] = array("Entry for searching usernames and periods", "");

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_show_avatars'] = array("Show avatars", "Activate the member avatars");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_avatar_size'] = array("Avatar size (width, height)", "The width and height of user avatars");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_move_all'] = array("Move threads in other forum modules too (not recommend!)", "Just move threads between forum modules with same or similar settings!");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forumbox'] = array("rename forumbox param", "rename the browser param forumbox (not recommend!)");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_param_forum'] = array("rename forum param", "rename the browser param forum (not recommend!)");

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastperson'] = array("remove field -Last-", "Remove the -Last- field from table.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_lastdate'] = array("remove field -Last on-", "Remove the -Last on- field from table.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createperson'] = array("remove field -AUTHOR-", "Remove the -Author- field from table.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_createdate'] = array("remove field -Created on-", "Remove the -Created on- field from table.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_remove_count'] = array("remove field -#-", "Remove the -#- field from table.");

$GLOBALS['TL_LANG']['tl_module']['sub_forum_headline'] = array('Headline', 'Headline used for forum subscriptions. Leave empty to use the default headline.');
$GLOBALS['TL_LANG']['tl_module']['sub_forum_change_sub_caption'] = array('Change subscription button text', 'The text of the change subscription button. Leave empty to use the default text.');
$GLOBALS['TL_LANG']['tl_module']['sub_forum_delete_sub_caption'] = array('Unsubscribe button text', 'The text of the unsubscribe button. Leave empty to use the default text.');
$GLOBALS['TL_LANG']['tl_module']['thread_headline'] = array('Headline', 'The headline used for topic subscriptions. Leave empty to use the default text.');
$GLOBALS['TL_LANG']['tl_module']['thread_change_sub_caption'] = array('Change subscription button text', 'The text of the change subscription button. Leave empty to use the default text.');
$GLOBALS['TL_LANG']['tl_module']['thread_delete_sub_caption'] = array('Unsubscribe button text', 'The text of the unsubscribe button. Leave empty to use the default text.');
$GLOBALS['TL_LANG']['tl_module']['no_subs_text'] = array('Text for nonexistent subscriptions', 'This text is displayed to the user when they have no subscriptions.');
$GLOBALS['TL_LANG']['tl_module']['c4g_editor_options'] = [
'Available optional buttons',
'Defines which optional buttons are available in the editor.'
];

$GLOBALS['TL_LANG']['tl_module']['pm_center_forum_module_legend'] = 'Forum module';
$GLOBALS['TL_LANG']['tl_module']['subforum_sub_legend'] = 'Forum settings';
$GLOBALS['TL_LANG']['tl_module']['thread_sub_legend'] = 'Topic settings';
$GLOBALS['TL_LANG']['tl_module']['misc_legend'] = 'Miscellaneous settings';

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_general_legend'] = 'Forum - General';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_user_legend']         = 'Forum - User settings';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sizes_legend']        = 'Forum - Sizes';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_bbcodes_legend'] = 'Forum - Editor';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_boxes_legend']   = 'Forum - Box navigation settings';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_jqui_legend']    = 'Forum - Styling (jQuery UI)';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_maps_legend']    = 'Forum - Maps (con4gis-Maps)';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_lib_legend']     = 'Forum - jQuery libraries';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_sitemap_legend'] = 'Forum - XML sitemap';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_search_legend']       = 'Forum - Search settings';

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_breadcrumb_legend'] = 'Breadcrumb';

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_threads_perpage_selection'] = array("Initial number of topics per page", "Initially displays this number of topics in the forum.");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_pagination_legend'] = 'Forum - Pagination';
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_page_legend'] = 'Page options';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['DISCUSSIONS'] = 'Discussions (threads & posts)';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['QUESTIONS']   = 'Informations (questions & comments)';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['TICKET']   = 'Ticketsystem';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['TREE']  = 'Tree';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['BOXES'] = 'Boxes';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREAD'] = 'Display all posts of thread';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['FPOST']  = 'Display first post';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['LPOST']  = 'Display last post';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['UU'] = 'Do not use real-names (use usernames instead)';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['FF'] = 'Use only the first-name';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['LL'] = 'Use only the last-name';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['FL'] = 'Use first- and last-name';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['LF'] = 'Use last- and first-name';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['UP'] = 'Oldest post first';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['DN'] = 'Latest post first';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['NC'] = 'Do not use collapsible posts';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['CO'] = 'All posts uncollapsed';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['CC'] = 'All posts collapsed';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['CF'] = 'First post uncollapsed';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['CL'] = 'Last post uncollapsed';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['THREADS'] = 'Public threads';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['FORUMS']  = 'Public forums';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['INTROS']  = 'Public forums - Intropages';

$GLOBALS['TL_LANG']['tl_module']['c4g_references']['settings']  = 'con4gis settings';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['base']      = 'base';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['black-tie'] = 'black-tie';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['blitzer']   = 'blitzer';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['cupertino'] = 'cupertino';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['dark-hive'] = 'dark-hive';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['dot-luv']   = 'dot-luv';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['eggplant']  = 'eggplant';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['excite-bike']   = 'excite-bike';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['flick']         = 'flick';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['hot-sneaks']    = 'hot-sneaks';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['humanity']      = 'humanity';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['le-frog']       = 'le-frog';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['mint-choc']     = 'mint-choc';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['overcast']      = 'overcast';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['pepper-grinder'] = 'pepper-grinder';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['redmond']       = 'redmond';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['smoothness']    = 'smoothness';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['south-street']  = 'south-street';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['start']         = 'start';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['sunny']         = 'sunny';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['swanky-purse']  = 'swanky-purse';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['trontastic']    = 'trontastic';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['ui-darkness']   = 'ui-darkness';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['ui-lightness']  = 'ui-lightness';
$GLOBALS['TL_LANG']['tl_module']['c4g_references']['vader']         = 'vader';

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip']                           = array("Tooltip for threadlist", "");
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_first_post'] = "Title of first post";
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['title_last_post']  = "Title of last post";
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_first_post']  = "Content of first post";
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['body_last_post']   = "Content of last post";
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadtitle']      = "Threadtitle";
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['threadbody']       = "Threaddescription";
$GLOBALS['TL_LANG']['tl_module']['c4g_forum_tooltip_value']['disabled']         = "disabled";

$GLOBALS['TL_LANG']['tl_module']['c4g_editor_options']['h2'] = "Headline H2";
$GLOBALS['TL_LANG']['tl_module']['c4g_editor_options']['h3'] = "Headline H3";
$GLOBALS['TL_LANG']['tl_module']['c4g_editor_options']['href'] = "Hyperlinks";
$GLOBALS['TL_LANG']['tl_module']['c4g_editor_options']['attach'] = "File attachments";

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_editor']                        = array('WYSIWYG-Editor', '');

$GLOBALS['TL_LANG']['tl_module']['c4g_appearance_themeroller_css'] = array('jQuery UI ThemeRoller CSS fille', 'select a jQuery UI CSS file.');
$GLOBALS['TL_LANG']['tl_module']['fields']['pm_center_forum_module'] = array('Forum module', 'Pick the forum module associated with this module.');

$GLOBALS['TL_LANG']['tl_module']['c4g_forum_notifications'] = 'Forum - Notifications';
$GLOBALS['TL_LANG']['tl_module']['sub_new_thread'] = array('New Topic', 'This email is sent when a new discussion is posted in a forum the user is subscribed to.');
$GLOBALS['TL_LANG']['tl_module']['sub_deleted_thread'] = array('Deleted Topic', 'This email is sent when a topic is deleted in a forum the user is subscribed to.');
$GLOBALS['TL_LANG']['tl_module']['sub_moved_thread'] = array('Moved Topic', 'This email is sent when a topic is moved in a forum the user is subscribed to.');
$GLOBALS['TL_LANG']['tl_module']['sub_new_post'] = array('New Post', 'This email is sent when a new post is created in a topic the user is subscribed to.');
$GLOBALS['TL_LANG']['tl_module']['sub_deleted_post'] = array('Deleted Post', 'This email is sent when a post is deleted in a topic the user is subscribed to.');
$GLOBALS['TL_LANG']['tl_module']['sub_edited_post'] = array('Edited Post', 'This email is sent when a post is edited in a topic the user is subscribed to.');
$GLOBALS['TL_LANG']['tl_module']['mail_new_pm'] = array('New Personal Message', 'This email is sent when the user receives a new PM.');
$GLOBALS['TL_LANG']['tl_module']['new_pm_redirect'] = array('Redirect page for personal messages', 'This is the page the notification for new personal messages will link to.');
