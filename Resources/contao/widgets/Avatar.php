<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Class Upload
 *
 * Provide methods to use the FileUpload class in a back end widget. The widget
 * will only upload the files to the server. Use a submit_callback to process
 * the files or use the class as base for your own upload widget.
 *
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Avatar extends \Widget implements \uploadable
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Add a for attribute
	 * @var boolean
	 */
	protected $blnForAttribute = false;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * Uploader
	 * @var \FileUpload
	 */
	protected $objUploader;


	/**
	 * Initialize the FileUpload object
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);

		$this->objUploader = new \con4gis\ForumBundle\Resources\contao\classes\C4gForumSingleFileUpload();
		$this->objUploader->setName($this->strName);
	}


	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$strUploadTo = 'system/tmp';

		// No file specified
		if (!isset($_FILES[$this->strName]['name'][0])) {
			return;
		}

		// Specify the target folder in the DCA (eval)
		if (isset($this->arrConfiguration['uploadFolder']))
		{
			$strUploadTo = $this->arrConfiguration['uploadFolder'];

			// Add user-based subfolder to target folder to prevent overwriting files with duplicate names.
			if (TL_MODE === 'FE') {
				$this->import('frontenduser');
				$strUploadTo = 'files/userimages/user_' . $this->frontenduser->id;
			}

			// Create the folder if it does not exist.
			new \Contao\Folder($strUploadTo);
		}

		return $this->objUploader->uploadTo($strUploadTo);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$iMemberId = 0;
		$sReturn = '';

		// Get the member's ID based upon the usage-location of the Widget: BE -> current viewed member, FE -> current logged in frontenduser.
		if (TL_MODE === 'FE') {
			$this->import('frontenduser');
			$iMemberId = $this->frontenduser->id;
		} else if (TL_MODE === 'BE') {
			$iMemberId = $this->currentRecord;
		}

		// Generate an image tag with the member's avatar.
		$sImage = \con4gis\ForumBundle\Resources\contao\classes\C4GForumHelper::getAvatarByMemberId($iMemberId);
		if ($sImage) {
			$sReturn = '<img src="' . $sImage . '">';
		}

		$sReturn .= ltrim($this->objUploader->generateMarkup());

		return $sReturn;
	}

}
