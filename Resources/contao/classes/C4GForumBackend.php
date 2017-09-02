<?php
/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */

namespace con4gis\ForumBundle\Resources\contao\classes;

/**
 * Class C4GForumBackend
 * @package on4gis\ForumBundle\Resources\contao\ForumBundle
 */
class C4GForumBackend extends \Backend
{
	/**
	 * Load the helper class
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->helper = new C4GForumHelper($this->Database);
	}

	/**
	 * build the fulltextindex
	 */
	public function buildIndex()
	{
		$message = '';

		if ($this->Input->post('FORM_SUBMIT') == 'tl_c4g_forum_build_index')
		{
			$this->helper->renewAllTheIndexesFromDB();

			// TODO Errorhandling
			// 			// Check for errors
			// 			if ( ERROR )
			// 			{
			// 				$this->addErrorMessage($GLOBALS['TL_LANG']['tl_c4g_forum']['fail'][0].$GLOBALS['TL_LANG']['tl_c4g_forum']['fail'][1]);
			// 				$message ='
			//				<div class="tl_header" style="color:#900;">
			//				'.$GLOBALS['TL_LANG']['tl_c4g_forum']['fail'][0].$GLOBALS['TL_LANG']['tl_c4g_forum']['fail'][1].'
			//				</div>';
			//				$this->reload();
			// 			}
			$message ='
			<div class="tl_header" style="color:#090;">
			'.$GLOBALS['TL_LANG']['tl_c4g_forum']['success'].'
			</div>';
		}

		//fetch info
		$indexInfo = $this->Database->prepare(
					"SELECT first, last_total_renew, last_index FROM tl_c4g_forum_search_last_index ".
					"WHERE id = 1"
				)->executeUncached()->fetchAllAssoc();

		//check if there was an index before
		if(isset($indexInfo[0])){
			$wordCount = $this->Database->prepare(
						"SELECT COUNT(*) AS count FROM tl_c4g_forum_search_word"
					)->executeUncached()->fetchAssoc();
			$wordCount = $wordCount['count'];
			$noIndex = false;
		}else{
			$wordCount = 0;
			$noIndex = true;
		}

		// create the form
		$form=
			//back-button
			'
			<div id="tl_buttons">
				<a href="'.ampersand(str_replace('&key=build_index', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
			</div>'.
			//headline
			'
			<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_c4g_forum']['headline_index'][0].'</h2>
			'.$this->getMessages().'
			<form action="'.ampersand($this->Environment->request, true).'" id="tl_c4g_forum_build_index" class="tl_form" method="post">
			<div class="tl_formbody_edit">
				<input type="hidden" name="FORM_SUBMIT" value="tl_c4g_forum_build_index">
				<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
			</div>
			<center>
			'.$message.'
			<br/>
			<div align="left" class="tl_tbox" style="padding:1px; box-shadow:0px 1px 6px #666; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; width:600px">
				<div align="center">
					<h1 class="main_headline">'.$GLOBALS['TL_LANG']['tl_c4g_forum']['info_headline'].'</h1>
				</div>
			';
		if($noIndex){
			$form .= '<div align="center" style="color:#900; padding:3px; margin:1px;">'.$GLOBALS['TL_LANG']['tl_c4g_forum']['noIndex'].'</div>';
		}else{
			$form .='
				<div style="width:350px; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.$GLOBALS['TL_LANG']['tl_c4g_forum']['info'][0].'
				</div>
				<div style="width:233px; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.strftime("%d.%m.%Y", $indexInfo[0]['first']).'
				</div>

				<div style="width:350px; background-color:#eee; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.$GLOBALS['TL_LANG']['tl_c4g_forum']['info'][1].'
				</div>
				<div style="width:233px; background-color:#eee; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.strftime("%d.%m.%Y", $indexInfo[0]['last_total_renew']).'
				</div>

				<div style="width:350px; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.$GLOBALS['TL_LANG']['tl_c4g_forum']['info'][2].'
				</div>
				<div style="width:233px; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.strftime("%d.%m.%Y", $indexInfo[0]['last_index']).'
				</div>

				<div style="width:350px; background-color:#eee; float:left; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.$GLOBALS['TL_LANG']['tl_c4g_forum']['info'][3].'
				</div>
				<div style="width:233px; background-color:#eee; float:right; border-bottom:1px solid #ddd; padding:3px; margin:1px;">
					'.$wordCount.'
				</div>
				';
		}
		$form .='
			<div>&nbsp;</div>
			</div>

			<br/>
			'.


			'
			<div class="tl_header">
				'.$GLOBALS['TL_LANG']['tl_c4g_forum']['warning'][0].'
				<br>
				'.$GLOBALS['TL_LANG']['tl_c4g_forum']['warning'][1].'
			</div>

			</center>
			<br/>
			'.

			// TODO index einzelner Foren erneuern

			//submit-buttons
			'
			<div class="tl_formbody_submit">
				<div align="center" class="tl_submit_container">
					<input type="submit" name="index" id="index" class="tl_submit" accesskey="i" value="'.specialchars($GLOBALS['TL_LANG']['tl_c4g_forum']['index'][0]).'">
				</div>
			</div>
			</form>';

		// return the form
		return $form;
	}

}

?>