<?php

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

/**
 * Set Tablename: tl_settings
 */
$strName = 'tl_settings';


/** Palettes */
$GLOBALS['TL_DCA'][$strName]['palettes']['default'] .=
    ';{forum_legend},sub_new_thread,sub_deleted_thread,sub_moved_thread,sub_new_post,sub_deleted_post,sub_edited_post;';



/** Fields */
/*$GLOBALS['TL_DCA'][$strName]['fields']['sub_new_thread'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['fields']['sub_new_thread'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'foreignKey'              => 'tl_nc_notification.title',
    'eval'                    => array('multiple' => true,),
    'sql'                     => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA'][$strName]['fields']['sub_deleted_thread'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['fields']['sub_deleted_thread'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'foreignKey'              => 'tl_nc_notification.title',
    'eval'                    => array('multiple' => true,),
    'sql'                     => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA'][$strName]['fields']['sub_moved_thread'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['fields']['sub_moved_thread'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'foreignKey'              => 'tl_nc_notification.title',
    'eval'                    => array('multiple' => true,),
    'sql'                     => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA'][$strName]['fields']['sub_new_post'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['fields']['sub_new_post'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'foreignKey'              => 'tl_nc_notification.title',
    'eval'                    => array('multiple' => true,),
    'sql'                     => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA'][$strName]['fields']['sub_deleted_post'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['fields']['sub_deleted_post'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'foreignKey'              => 'tl_nc_notification.title',
    'eval'                    => array('multiple' => true,),
    'sql'                     => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA'][$strName]['fields']['sub_edited_post'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['fields']['sub_edited_post'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'foreignKey'              => 'tl_nc_notification.title',
    'eval'                    => array('multiple' => true,),
    'sql'                     => "varchar(255) NOT NULL default ''",
);*/