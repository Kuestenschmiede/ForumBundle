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

/**
 * Usethe "memberLink" key in the eval array to indicate this field as a member link field, e. g. homepage, facebook, twitter.
 * This key is used in the member data generation for the forum to get all member links as output them.
 */

$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace(
    '{groups_legend}',
    '{forum_member_legend},memberImage,memberSignature,memberPosts,memberHomepageLink,memberFacebookLink,memberTwitterLink,memberGooglePlusLink;{groups_legend}',
    $GLOBALS['TL_DCA']['tl_member']['palettes']['default']
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberImage'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberImage'],
    'exclude'                 => true,
    'inputType'               => 'avatar',
    'load_callback'           => array(array('tl_member_dca', 'setUploadFolder')),
    'save_callback'           => array(array('tl_member_dca', 'handleMemberImage')),
    'eval'                    => array('filesOnly'=>true, 'multiple' => false, 'fieldType'=>'radio', 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'forum', 'storeFile' => true, 'uploadFolder' => 'files/userimages/xxx', 'tl_class'=>'clr'),
    'sql'                     => "mediumtext NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberSignature'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberSignature'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'textarea',
    'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr', 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'forum'),
    'sql'                     => "mediumtext NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberHomepageLink'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberHomepageLink'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'fieldType'=>'radio', 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'forum', 'memberLink' => true, 'tl_class'=>'clr w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberFacebookLink'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberFacebookLink'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'fieldType'=>'radio', 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'forum', 'memberLink' => true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberTwitterLink'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberTwitterLink'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'fieldType'=>'radio', 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'forum', 'memberLink' => true, 'tl_class'=>'clr w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberGooglePlusLink'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberGooglePlusLink'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'fieldType'=>'radio', 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'forum', 'memberLink' => true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);


/**
 * Class tl_member_dca
 */
class tl_member_dca extends \Contao\Backend
{

    /**
     * Check if submitted memberImage is empty.
     * If a memberImage is already stored in the database, return this memberImage value.
     * Otherwise store an empty value.
     *
     * This save_callback prevents deleting memberImages when submitting the personal data form without specifying a memberImage when there is already a memberImage stored in the database.
     * It also takes into account, when the admin saves the member profile in the backend and checks again for already present memberImage data in the database.
     *
     * @param $varValue
     * @param $dc
     * @return mixed
     */
    public function handleMemberImage($varValue, $dc)
    {
        // Get the member's ID based upon the usage-location of the Widget: BE -> current viewed member, FE -> current logged in frontenduser.
        if (TL_MODE === 'FE') {
            $this->import('frontenduser');
            $iMemberId = $this->frontenduser->id;
        } else {
            $iMemberId = $dc->id;
        }

        $sImagePathFromDatabase = \con4gis\ForumBundle\Resources\contao\models\C4gForumMember::getAvatarByMemberId($iMemberId);

        $deseralized_value = deserialize($varValue);
        if (empty($deseralized_value) && (!empty($sImagePathFromDatabase))) {
            if ($sImagePathFromDatabase) {
                $varValue = $sImagePathFromDatabase;
            }
        }

        return $varValue;
    }

    /**
     * @param $dc
     * @return string
     */
    public function setUploadFolder($varValue, $dc)
    {
        $uploadFolder = TL_ROOT . "/files/userimages/";
        $iMemberId = $dc->id;

        if ($iMemberId > 0) {
            $uploadFolder = $uploadFolder.'user_'.$iMemberId;
        }

        $GLOBALS['TL_DCA']['tl_member']['fields']['memberImage']['eval']['uploadFolder'] = $uploadFolder;
    }

}