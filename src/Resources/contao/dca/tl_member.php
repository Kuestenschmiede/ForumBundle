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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/**
 * Usethe "memberLink" key in the eval array to indicate this field as a member link field, e. g. homepage, facebook, twitter.
 * This key is used in the member data generation for the forum to get all member links as output them.
 */

PaletteManipulator::create()
    ->addLegend('forum_member_legend', 'groups_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addField(array('memberImage','memberSignature','memberPosts','memberHomepageLink','memberFacebookLink','memberTwitterLink','memberGooglePlusLink'), 'forum_member_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member');

$GLOBALS['TL_DCA']['tl_member']['fields']['memberImage'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['memberImage'],
    'exclude'                 => true,
    'inputType'               => 'avatar',
    'load_callback'           => array(array('con4gis\ForumBundle\Classes\Callbacks\MemberCallback', 'setUploadFolder')),
    'save_callback'           => array(array('con4gis\ForumBundle\Classes\Callbacks\MemberCallback', 'handleMemberImage')),
    'eval'                    => array('filesOnly'=>true, 'multiple' => false, 'fieldType'=>'radio', 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'forum', 'storeFile' => true, 'tl_class'=>'clr'),
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
$GLOBALS['TL_DCA']['tl_member']['fields']['tstampLastAction'] = array
(
    'sql'                     => "int(10) NOT NULL default 0"
);

