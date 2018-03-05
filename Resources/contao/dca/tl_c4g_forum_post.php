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
        'onsubmit_callback' =>array(array('tl_c4g_forum_post','saveDefault'))/*,
        'onload_callback'   =>array(
                                    array('tl_c4g_forum_post', 'loadPost')

        ),*/

    ),
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('creation'),
            'panelLayout'             => 'sort,filter;search,limit',
            'flag'                    => 6
        ),
        'label' => array
        (
            'fields'                  => array('subject','text'),
            'format'                  => '%s, %s',
            'label_callback'          => array('tl_c4g_forum_post','loadLabel')
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
        'default'                     => '{title_legend},title, price;{description_legend},description,subject,text,state;'
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
            'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['text'],
            'search'				  => true,
            'inputType'				  => 'textarea',
            'eval'					  => array('rte'=>'tinyMCE'),
            'sql'                     => "mediumtext NULL"
        ),
        'subject' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_post']['subject'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255 ),
            'sql'                     => "varchar(100) NOT NULL default ''"
        ),
        'state' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['state'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_c4g_forum_post','get_options'),
            'sql'                     => "int(10)"
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
        $thread = $this->Database->prepare("SELECT pid,last_post_id FROM tl_c4g_forum_thread WHERE id=?")->execute($dc->activeRecord->pid)->fetchAssoc();
        $lastPost = $this->Database->prepare('SELECT post_number FROM tl_c4g_forum_post WHERE id=?')->execute($thread['last_post_id'])->fetchAssoc();
        $arrSet['forum_id'] = $thread['pid'];
        $arrSet['author'] = $this->Database->prepare("SELECT default_author FROM tl_c4g_forum WHERE id=?")->execute($arrSet['forum_id'])->fetchAssoc()['default_author'];
        $arrSet['creation'] = time();
        $arrSet['post_number'] = $lastPost['post_number'];

        //@ToDo ForumId,author hinzufügen
        $arrSetParent['last_post_id'] = $dc->id;
        $arrSetParent['creation'] = time();
        $arrSetParent['state'] = $dc->activeRecord->state;

        if($dc->activeRecord->subject == ''){
            $arrSet['subject'] = $GLOBALS['TL_LANG']['tl_c4g_forum_post']['state_change'] . $dc->activeRecord->state;
        }

        if ($arrSet['author']) {
            $this->Database->prepare("UPDATE tl_c4g_forum_post %s WHERE id=?")->set($arrSet)->execute($dc->id);
            $this->Database->prepare("UPDATE tl_c4g_forum_thread %s WHERE id=?")->set($arrSetParent)->execute($dc->activeRecord->pid);
        }
    }

    public function loadLabel ($arrRow)
    {
        //Status des Tickets auf gelesen ändern
        $thread = $this->Database->prepare('SELECT * FROM tl_c4g_forum_thread WHERE id=?')->execute($arrRow['pid'])->fetchAssoc();
        if($thread['state'] == 1){
            $set['state'] = 2;
            $this->Database->prepare("UPDATE tl_c4g_forum_thread %s WHERE id=?")->set($set)->execute($arrRow['pid']);
        }
        return $arrRow['text'];
    }
    public function get_options(DataContainer $dc)
    {
        return array(
            1 => \con4gis\ForumBundle\Resources\contao\classes\C4GForumTicketStatus::getState(1),
            2 => \con4gis\ForumBundle\Resources\contao\classes\C4GForumTicketStatus::getState(2),
            3 => \con4gis\ForumBundle\Resources\contao\classes\C4GForumTicketStatus::getState(3),
            4 => \con4gis\ForumBundle\Resources\contao\classes\C4GForumTicketStatus::getState(4)
        );
    }
}