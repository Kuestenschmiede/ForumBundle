<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
$GLOBALS['TL_DCA']['tl_c4g_forum_thread'] = array
(

    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
        'ptable' => 'tl_c4g_forum',
        'ctable' => 'tl_c4g_forum_post',
        //'notCreatable'  => true,
        'sql'           => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        ),
        'onsubmit_callback' =>array(array('tl_c4g_forum_thread','saveDefault')),
        'onload_callback'   =>array(array('tl_c4g_forum_thread', 'getDatasets'))
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
            'fields'                  => array('name'),
            'label_callback'          => array('tl_c4g_forum_thread','get_label'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'post' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['post'],
                'href'                => 'do=c4g_forum_post&amp;table=tl_c4g_forum_post',
                'icon'	 		      => 'bundles/con4gisforum/icons/table.png',
                'button_callback'     => array('tl_c4g_forum_thread','forumPost')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['show'],
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
        'default'                     => '{title_legend},title, price;{description_legend},description,name,recipient,owner,state,creation;'
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
            'filter'                  => true,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['name'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'state' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['state'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_c4g_forum_thread','get_options'),
            'filter'                  => true,
            'search'                  => true,
            'eval'                    => array('includeBlankOption' => true, 'blankOptionLabel' => '-'),
            'sql'                     => "int(10)"
        ),
        'sort' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '999'"
        ),
        'threaddesc' => array
        (
            'sql'                     => "text NULL"
        ),
        'author' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'owner' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['owner'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'filter'                  => true,
            'search'                  => true,
            'foreignKey'              => 'tl_member.username',
            //'options_callback'      => array('CLASS', 'METHOD'),
            'eval'                    => array('maxlength'=>255, 'includeBlankOption'=>true, 'multiple'=>true, 'chosen'=>true),
            'sql'                     => "blob"
        ),
        'recipient' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum_thread']['recipient'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'filter'                  => true,
            'search'                  => true,
            'foreignKey'              => 'tl_member.username',
            //'options_callback'      => array('CLASS', 'METHOD'),
            'eval'                    => array('maxlength'=>255, 'includeBlankOption'=>true, 'multiple'=>true, 'chosen'=>true),
            'sql'                     => "blob",
        ),
        'concerning' => array(
            'sql'                     => 'int(10)'
        ),
        'creation' => array
        (
            'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'posts' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'last_post_id' => array
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
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tags' => array
        (
            'sql'                     => "blob NULL"
        )
    ),
);
class tl_c4g_forum_thread extends \Backend{
    public function forumPost($row, $href, $label, $title, $icon)
    {
        $href .= "&amp;id=".$row['id'];
        return '<a href="' . $this->addToUrl($href) . '" title="'.specialchars($title).'">'.Image::getHtml($icon, $label).'</a> ';
    }
    public function saveDefault(DataContainer $dc)
    {
        if (!$dc->activeRecord)
        {
            return;
        }

        $author = $this->Database->prepare("SELECT default_author FROM tl_c4g_forum WHERE id=?")->execute($dc->activeRecord->pid)->fetchAssoc();

        $arrSet['author'] = $author['default_author'];

        if ($arrSet['author']) {
            $arrSet['creation'] = time();
            $this->Database->prepare("UPDATE tl_c4g_forum_thread %s WHERE id=?")->set($arrSet)->execute($dc->id);
        }
    }
    public function get_label($arrRow)
    {
        $result ="";
        $settings = Database::getInstance()->execute("SELECT * FROM tl_c4g_settings LIMIT 1")->fetchAllAssoc();

        if ($settings) {
            $settings = $settings[0];
        }
        if ($settings && $settings['c4g_forum_type']) {

            if ($settings['c4g_forum_type'] == "DISCUSSIONS") {
                $result .= $GLOBALS['TL_LANG']['tl_c4g_forum_thread']['counter_caption_thread'];
            } else if ($settings['c4g_forum_type'] == "TICKET") {
                $result .= $GLOBALS['TL_LANG']['tl_c4g_forum_thread']['counter_caption_ticket'];
            }
        }

        $result .=sprintf('%04d', $arrRow['id']).'] ';
        $author = $this->Database->prepare('SELECT * FROM tl_member WHERE id=?')->execute($arrRow['author'])->fetchAssoc();
        $result .= $arrRow['name'].': ';
        $result .= date($GLOBALS['TL_CONFIG']['dateFormat'], intval($arrRow['tstamp'])).' ';
        $result .= date($GLOBALS['TL_CONFIG']['timeFormat'], intval($arrRow['tstamp'])).' ';
        $result .= $author['username'];
        $state = \con4gis\ForumBundle\Resources\contao\classes\C4GForumTicketStatus::getState($arrRow['state']);
        if($state)
        {
            $result .=' : (<b>'.$state.'</b>)';
        }

        return $result;
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
    public function getDatasets(DataContainer $dc)
    {
        $pid = input::get('id');
        if($pid){
            $childs = $this->getChilds($pid,$dc);
            $root = $dc->Database->prepare("SELECT id FROM tl_c4g_forum_thread WHERE pid=?")
                ->execute($pid)
                ->fetchEach('id');
            $root = array_merge($root,$childs);
            if (empty($root)) {
                $root = array('0');
            }
        }
        else{
            $GLOBALS['TL_CSS'][] = "bundles/con4gisforum/css/c4gForumBackendButton.css";
            $root = $dc->Database->prepare("SELECT id FROM tl_c4g_forum_thread")
                ->execute()
                ->fetchEach('id');

        }
        $GLOBALS['TL_DCA']['tl_c4g_forum_thread']['list']['sorting']['root'] = $root;

    }
    public function getChilds($pid,$dc)
    {
        $childs = $dc->Database->prepare("SELECT id FROM tl_c4g_forum WHERE pid=?")
            ->execute($pid)->fetchAllAssoc();

        $return = array();

        foreach($childs as $child)
        {
            $return = array_merge($return,$dc->Database->prepare("SELECT id FROM tl_c4g_forum_thread WHERE pid=?")
                ->execute($child['id'])
                ->fetchEach('id'));
            $return = array_merge($return, self::getChilds($child['id'],$dc));
        }
        return $return;
    }


}