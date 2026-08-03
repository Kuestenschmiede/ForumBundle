<?php

namespace con4gis\ForumBundle\Classes\Callbacks;

use con4gis\ForumBundle\Models\C4gForumMember;
use Contao\Backend;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

class MemberCallback extends Backend
{
    public function handleMemberImage($varValue, $dc)
    {
        $iMemberId = 0;
        $container = System::getContainer();
        $requestStack = $container->get('request_stack');
        $request = $requestStack->getCurrentRequest() ?? Request::createFromGlobals();

        if ($container->get('contao.routing.scope_matcher')->isFrontendRequest($request))
        {
            $user = $container->get('security.helper')->getUser();
            if ($user instanceof \Contao\FrontendUser) {
                $iMemberId = $user->id;
            }
        }
        else
        {
            $iMemberId = (int)$dc->id;
            if (!$iMemberId && $dc->activeRecord) {
                $iMemberId = (int)$dc->activeRecord->id;
            }
            if (!$iMemberId) {
                $iMemberId = (int)$request->query->get('id');
            }
        }

        if (is_string($varValue) && StringUtil::isSerialized($varValue)) {
            return $varValue;
        }

        if (is_array($varValue) && !empty($varValue)) {
            return serialize(array_values($varValue));
        }

        if ($iMemberId > 0 && ($varValue === null || $varValue === '' || $varValue === [])) {
            $db = \Contao\Database::getInstance();
            $res = $db->prepare("SELECT memberImage FROM tl_member WHERE id=?")->execute($iMemberId);
            if ($res->numRows > 0 && ($res->memberImage ?? '') !== '') {
                return $res->memberImage;
            }
        }

        return $varValue;
    }

    public function setUploadFolder($varValue, $dc)
    {
        $uploadFolder = "files/userimages";
        $iMemberId = $dc->id ?? ($dc->activeRecord->id ?? 0);

        if ($iMemberId <= 0) {
            $container = System::getContainer();
            $requestStack = $container->get('request_stack');
            $request = $requestStack->getCurrentRequest() ?? Request::createFromGlobals();

            if ($container->get('contao.routing.scope_matcher')->isFrontendRequest($request)) {
                $user = $container->get('security.helper')->getUser();
                if ($user instanceof \Contao\FrontendUser) {
                    $iMemberId = $user->id;
                }
            }
        }

        if ($iMemberId > 0)
        {
            $uploadFolder = $uploadFolder . '/user_' . $iMemberId;
        }

        $GLOBALS['TL_DCA']['tl_member']['fields']['memberImage']['eval']['uploadFolder'] = $uploadFolder;
        
        return $varValue;
    }
}
