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
namespace con4gis\ForumBundle\Widgets;

use con4gis\ForumBundle\Classes\C4GForumHelper;
use con4gis\ForumBundle\Classes\C4gForumSingleFileUpload;
use Contao\Folder;
use Contao\StringUtil;
use Contao\System;
use Contao\UploadableWidgetInterface;
use Contao\Widget;
use Symfony\Component\HttpFoundation\Request;

class Avatar extends Widget implements UploadableWidgetInterface
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
        $container = System::getContainer();
        $requestStack = $container->get('request_stack');
        $request = $requestStack->getCurrentRequest() ?? Request::createFromGlobals();
        $rootDir = $container->getParameter('kernel.project_dir');

        // No file specified
        if (!isset($_FILES[$this->strName]['name'][0]) || $_FILES[$this->strName]['name'][0] === '')
        {
            $varExisting = $request->request->get($this->strName . '_existing');
            if (\Contao\Validator::isUuid($varExisting)) {
                return \Contao\StringUtil::uuidToBin($varExisting);
            }
            return $varExisting;
        }

        $strUploadTo = null;

        // Specify the target folder in the DCA (eval)
        if (isset($this->arrConfiguration['uploadFolder'])) {
            $strUploadTo = $this->arrConfiguration['uploadFolder'];
        } elseif ($container->get('contao.routing.scope_matcher')->isFrontendRequest($request)) {
            $user = $container->get('security.helper')->getUser();
            if ($user instanceof \Contao\FrontendUser) {
                $strUploadTo = 'files/userimages/user_' . $user->id;
            } else {
                // When registering, the member does not exist in the database yet.
                // The upload will be handled in the createNewUser hook once the member ID is known.
                return null;
            }
        } elseif ($container->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $iMemberId = (int)$this->currentRecord;
            if (!$iMemberId) {
                $iMemberId = (int)$request->query->get('id');
            }
            if ($iMemberId > 0) {
                $strUploadTo = 'files/userimages/user_' . $iMemberId;
            }
        }

        if (!$strUploadTo)
        {
           return null;
        }

        // Create the folder if it does not exist.
        if (!is_dir($rootDir . '/' . $strUploadTo))
        {
            new Folder($strUploadTo);
        }

        $files = $this->objUploader->uploadTo($strUploadTo);
        if (is_array($files) && !empty($files)) {
            $objFile = \Contao\Dbafs::addResource($files[0]);
            return $objFile->uuid;
        }

        return null;
    }


    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {
        $iMemberId = 0;
        $sReturn = '';
        $container = System::getContainer();
        $requestStack = $container->get('request_stack');
        $request = $requestStack->getCurrentRequest() ?? Request::createFromGlobals();

        // Get the member's ID based upon the usage-location of the Widget: BE -> current viewed member, FE -> current logged in frontenduser.
        if ($container->get('contao.routing.scope_matcher')->isFrontendRequest($request))
        {
            $user = $container->get('security.helper')->getUser();
            if ($user instanceof \Contao\FrontendUser) {
                $iMemberId = $user->id;
            }
        }
        else
        {
            if ($container->get('contao.routing.scope_matcher')->isBackendRequest($request))
            {
                $iMemberId = (int)$this->currentRecord;
                if (!$iMemberId) {
                    $iMemberId = (int)$request->query->get('id');
                }
            }
        }

        // Generate an image tag with the member's avatar.
        $sImage = C4GForumHelper::getAvatarByMemberId($iMemberId);
        if ($sImage)
        {
            $sReturn = '<img src="' . $sImage . '" style="max-width: 200px; display: block; margin-bottom: 10px;">';
        }

        $val = $this->varValue;
        if (\Contao\Validator::isUuid($val)) {
            $val = \Contao\StringUtil::binToUuid($val);
        }
        $sReturn .= '<input type="hidden" name="'.$this->strName.'_existing" value="'.StringUtil::specialchars($val).'">';

        $sReturn .= ltrim($this->objUploader->generateMarkup());

        return $sReturn;
    }

}