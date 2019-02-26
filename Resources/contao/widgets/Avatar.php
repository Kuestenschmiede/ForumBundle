<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
namespace con4gis\ForumBundle\Resources\contao\widgets;

use con4gis\ForumBundle\Resources\contao\classes\C4GForumHelper;
use con4gis\ForumBundle\Resources\contao\classes\C4gForumSingleFileUpload;
use Contao\Folder;
use Contao\System;
use Contao\Widget;

class Avatar extends Widget implements \uploadable
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
    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->objUploader = new C4gForumSingleFileUpload();
        $this->objUploader->setName($this->strName);
    }



    /**
     * Trim values
     * @param mixed
     * @return mixed
     */
    public function validator($varInput)
    {
        $rootDir = System::getContainer()->getParameter('kernel.project_dir');

        $strUploadTo = 'system/tmp';

        // No file specified
        if (!isset($_FILES[$this->strName]['name'][0]))
        {
            return;
        }

        // Specify the target folder in the DCA (eval)
        if (isset($this->arrConfiguration['uploadFolder']))
        {
            $strUploadTo = $this->arrConfiguration['uploadFolder'];

            // Add user-based subfolder to target folder to prevent overwriting files with duplicate names.
            if (TL_MODE === 'FE')
            {
                $this->import('frontenduser');
                $strUploadTo = 'files/userimages/user_' . $this->frontenduser->id;
            }
            else
            {
                if (!$strUploadTo)
                {
                    return;
                }
            }

            // Create the folder if it does not exist.
            if (!is_dir($rootDir . '/' . $strUploadTo))
            {
                new Folder($strUploadTo);
            }
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
        if (TL_MODE === 'FE')
        {
            $this->import('frontenduser');
            $iMemberId = $this->frontenduser->id;
        }
        else
        {
            if (TL_MODE === 'BE')
            {
                $iMemberId = $this->currentRecord;
            }
        }

        // Generate an image tag with the member's avatar.
        $sImage = C4GForumHelper::getAvatarByMemberId($iMemberId);
        if ($sImage)
        {
            $sReturn = '<img src="' . $sImage . '">';
        }

        $sReturn .= ltrim($this->objUploader->generateMarkup());

        return $sReturn;
    }

}