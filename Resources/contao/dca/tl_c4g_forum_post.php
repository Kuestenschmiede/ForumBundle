<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * con4gis - the gis-kit
 *
 * @version   php 5
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2017.
 * @link      https://www.kuestenschmiede.de
 */

/**
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum_post'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_c4g_forum_thread',
        'sql'                         => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        ),
        'onsubmit_callback' =>array(array('tl_c4g_forum_post','saveDefault'))

    ),
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('creation'),
            'panelLayout'             => 'sort,filter;search,limit',
            'flag'                    => 1
        ),
        'label' => array
        (
            'fields'                  => array('subject','text'),
            'format'                  => '%s, %s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array()
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array(''),
        'default'                     => '{title_legend},title, price;{description_legend},description,subject,text;'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        ''                            => ''
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'text' => array
        (
            'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['text'],
            'search'				=> true,
            'inputType'				=> 'textarea',
            'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
        ),
        'subject' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['name'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255 ),
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),

        'tags' => array
        (
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'author' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'creation' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'forum_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'post_number' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'edit_count' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'edit_last_author' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'edit_last_time' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'linkname' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'linkurl' => array
        (
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'loc_osm_id' => array
        (
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'loc_geox' => array
        (
            'sql'                     => "varchar(20) NOT NULL default ''"
        ),
        'loc_geoy' => array
        (
            'sql'                     => "varchar(20) NOT NULL default ''"
        ),
        'loc_data_type' => array
        (
            'sql'                     => "char(10) NOT NULL default ''"
        ),
        'loc_data_content' => array
        (
            'sql'                     => "text NULL"
        ),
        'locstyle' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'loc_label' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'loc_tooltip' => array
        (
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'rating' => array
        (
            'sql'                     => "double NOT NULL default '0'"
        )
    ),
);
class tl_c4g_forum_post extends \Backend{

    public function saveDefault(DataContainer $dc)
    {
        if (!$dc->activeRecord)
        {
            return;
        }

        $arrSet['creation'] = time();
        //@ToDo ForumId,author hinzufügen
        $arrSetParent['last_post_id'] = $dc->id;

        $this->Database->prepare("UPDATE tl_c4g_forum_post %s WHERE id=?")->set($arrSet)->execute($dc->id);
        $this->Database->prepare("UPDATE tl_c4g_forum_thread %s WHERE id=?")->set($arrSetParent)->execute($dc->activeRecord->pid);
    }

}