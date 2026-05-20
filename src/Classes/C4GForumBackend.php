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

namespace con4gis\ForumBundle\Classes;

use Contao\Backend;
use Contao\StringUtil;
use Contao\System;

/**
 * Class C4GForumBackend
 * @package on4gis\ForumBundle\Resources\contao\ForumBundle
 */
class C4GForumBackend extends Backend
{
    /**
     * @var C4GForumHelper
     */
    protected $helper = null;

    /**
     * @var \Contao\Database
     */
    protected $Database = null;

    /**
     * Load the helper class
     */
    public function __construct()
    {
        $this->Database = System::getContainer()->get('database_connection');
        $this->helper = new C4GForumHelper($this->Database);
    }

    /**
     * build the fulltextindex
     */
    public function buildIndex()
    {
        $message = '';

        if (System::getContainer()->get('request_stack')->getCurrentRequest()->request->get('FORM_SUBMIT') == 'tl_c4g_forum_build_index') {
            $this->helper->renewAllTheIndexesFromDB();
        }

        //fetch info
        $indexInfo = $this->Database->prepare(
                    'SELECT first, last_total_renew, last_index FROM tl_c4g_forum_search_last_index ' .
                    'WHERE id = 1'
                )->executeUncached()->fetchAllAssoc();

        //check if there was an index before
        if (isset($indexInfo[0])) {
            $wordCount = $this->Database->prepare(
                        'SELECT COUNT(*) AS count FROM tl_c4g_forum_search_word'
                    )->executeUncached()->fetchAssoc();
            $wordCount = $wordCount['count'];
            $noIndex = false;
        } else {
            $wordCount = 0;
            $noIndex = true;
        }

        $environment = System::getContainer()->get('contao.routing.scope_matcher');
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        // create the form
        $form =
            //back-button
            '
			<div id="tl_buttons">
				<a href="' . StringUtil::ampersand(str_replace('&key=build_index', '', $request->getUri())) . '" class="header_back" title="' . \Contao\StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) . '" accesskey="b">' . $GLOBALS['TL_LANG']['MSC']['backBT'] . '</a>
			</div>' .
            //headline
            '
			<h2 class="sub_headline">' . $GLOBALS['TL_LANG']['tl_c4g_forum']['headline_index'][0] . '</h2>
			' . System::getMessages() . '
			<form action="' . StringUtil::ampersand($request->getUri(), true) . '" id="tl_c4g_forum_build_index" class="tl_form" method="post">
			<div class="tl_formbody_edit">
				<input type="hidden" name="FORM_SUBMIT" value="tl_c4g_forum_build_index">
				<input type="hidden" name="REQUEST_TOKEN" value="' . System::getContainer()->get('contao.csrf.token_manager')->getToken(System::getContainer()->getParameter('contao.csrf_token_name'))->getValue() . '">
			</div>
			<center>
			' . $message . '
			<br/>
			<div align="left" class="tl_tbox" style="padding:1px; box-shadow:0px 1px 6px #666; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; width:600px">
				<div align="center">
					<h1 class="main_headline">' . $GLOBALS['TL_LANG']['tl_c4g_forum']['info_headline'] . '</h1>
				</div>
			';
        if ($noIndex) {
            $form .= '<div align="center" style="color:#900; padding:3px; margin:1px;">' . $GLOBALS['TL_LANG']['tl_c4g_forum']['noIndex'] . '</div>';
        } else {
            $form .= '
				<div style="width:350px; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . $GLOBALS['TL_LANG']['tl_c4g_forum']['info'][0] . '
				</div>
				<div style="width:233px; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . strftime('%d.%m.%Y', $indexInfo[0]['first']) . '
				</div>

				<div style="width:350px; background-color:#eee; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . $GLOBALS['TL_LANG']['tl_c4g_forum']['info'][1] . '
				</div>
				<div style="width:233px; background-color:#eee; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . strftime('%d.%m.%Y', $indexInfo[0]['last_total_renew']) . '
				</div>

				<div style="width:350px; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . $GLOBALS['TL_LANG']['tl_c4g_forum']['info'][2] . '
				</div>
				<div style="width:233px; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . strftime('%d.%m.%Y', $indexInfo[0]['last_index']) . '
				</div>

				<div style="width:350px; background-color:#eee; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . $GLOBALS['TL_LANG']['tl_c4g_forum']['info'][3] . '
				</div>
				<div style="width:233px; background-color:#eee; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					' . $wordCount . '
				</div>
				';
        }
        $form .= '
			<div>&nbsp;</div>
			</div>

			<br/>
			' .

            '
			<div class="tl_header">
				' . $GLOBALS['TL_LANG']['tl_c4g_forum']['warning'][0] . '
				<br>
				' . $GLOBALS['TL_LANG']['tl_c4g_forum']['warning'][1] . '
			</div>

			</center>
			<br/>
			' .

            // TODO index einzelner Foren erneuern

            //submit-buttons
            '
			<div class="tl_formbody_submit">
				<div align="center" class="tl_submit_container">
					<input type="submit" name="index" id="index" class="tl_submit" accesskey="i" value="' . \Contao\StringUtil::specialchars($GLOBALS['TL_LANG']['tl_c4g_forum']['index'][0]) . '">
				</div>
			</div>
			</form>';

        // return the form
        return $form;
    }
}
