<?php

namespace con4gis\ForumBundle\Classes\Callbacks;

use con4gis\ForumBundle\Models\C4gForumMember;
use Contao\Backend;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

class MemberCallback extends Backend
{
    public function handleMemberImage($varValue, $dc)
    {
        // Get the member's ID based upon the usage-location of the Widget: BE -> current viewed member, FE -> current logged in frontenduser.
        if (\Contao\System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(Request::createFromGlobals()))
        {
            $this->import('Contao\FrontendUser', 'User');
            $iMemberId = $this->User->id;
        }
        else
        {
            $iMemberId = $dc->id;
        }

        $sImagePathFromDatabase = C4gForumMember::getAvatarByMemberId($iMemberId);

        $deseralized_value = unserialize($varValue);
        if (empty($deseralized_value) && (!empty($sImagePathFromDatabase)))
        {
            if ($sImagePathFromDatabase)
            {
                $varValue = $sImagePathFromDatabase;
            }
        }

        return $varValue;
    }

    public function setUploadFolder($varValue, $dc)
    {
        $uploadFolder = "files/userimages";
        $iMemberId = $dc->id;

        if ($iMemberId > 0)
        {
            $uploadFolder = $uploadFolder . '/user_' . $iMemberId;
        }

        $GLOBALS['TL_DCA']['tl_member']['fields']['memberImage']['eval']['uploadFolder'] = $uploadFolder;
        
        return $varValue;
    }
}
