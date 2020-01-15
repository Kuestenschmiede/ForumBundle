<?php use con4gis\CoreBundle\Classes\C4GVersionProvider;

if (!defined('TL_ROOT')) die('You cannot access this file directly!');
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */

/**
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum'] = array
(

	// Config
	'config' => array
	(
	    'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => 'Table',
		'ctable'                      => array('tl_c4g_forum_thread'),
		'enableVersioning'            => true,
	    'onload_callback'			  => array(
											array('tl_c4g_forum', 'updateDCA')
										 ),
	    'onsubmit_callback'           => array(
	        //array('\c4g\Core\C4GAutomator', 'purgeApiCache'),
            array('tl_c4g_forum', 'onSubmit')
        ),
		'ondelete_callback'			  => array(
											array('tl_c4g_forum', 'onDeleteForum')
										 ),
        'sql'                         => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        )

	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'fields'                  => array('name'),
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
            'label_callback'          => array('tl_c4g_forum','get_label')
		),
		'global_operations' => array
		(
			'index' => array
			(
				'label'				  => &$GLOBALS['TL_LANG']['tl_c4g_forum']['build_index'],
				'href'				  => 'key=build_index',
				'class'				  => 'navigation',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="i"'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			),
            'remove_bb' => array
            (
                'button_callback'     => array('tl_c4g_forum','remove_bb')
            ),
            'back' => [
                'href'                => 'key=back',
                'class'               => 'header_back',
                'button_callback'     => ['\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'back'],
                'icon'                => 'back.svg',
                'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
            ],
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'copyChilds' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['copyChilds'],
				'href'                => 'act=paste&amp;mode=copy&amp;childs=1',
				'icon'                => 'copychilds.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_c4g_forum', 'copyPageWithSubpages')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'thread' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['thread'],
                'href'                => 'do=c4g_forum_thread',
                'icon'	 		      => 'bundles/con4gisforum/icons/table.png',
                'button_callback'     => array('tl_c4g_forum','forumThread')
            ),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_c4g_forum', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('define_groups','define_rights','enable_maps','map_type'),
		'default'                     => '{general_legend},name,headline,description,published;'.
                                         '{language_legend:hide},optional_names,optional_headlines,optional_descriptions;'.
										 '{comfort_legend},box_imagesrc;'.
										 '{intropage_legend:hide},use_intropage;'.
										 '{infotext_legend:hide},pretext,posttext;'.
										 '{additional_legend:hide},tags;'.
										 '{groups_legend:hide},define_groups;'.
										 '{rights_legend:hide},define_rights;'.
										 '{expert_legend:hide},linkurl,link_newwindow,sitemap_exclude;mail_subscription_text',

	    // used in updateDCA(), because subpalettes don't work well with TinyMCE fields!!
		'with_intropage'              => '{general_legend},name,optional_names,headline,optional_headlines,description,optional_descriptions,published;'.
                                         '{language_legend:hide},optional_names,optional_headlines,optional_descriptions;'.
										 '{comfort_legend},box_imagesrc;'.
										 '{intropage_legend},use_intropage,intropage,intropage_forumbtn,intropage_forumbtn_jqui;'.
										 '{infotext_legend:hide},pretext,posttext;'.
										 '{groups_legend:hide},define_groups;'.
										 '{rights_legend:hide},define_rights;'.
										 '{expert_legend:hide},linkurl,link_newwindow,sitemap_exclude;',

	),

	'subpalettes' => array(
		'define_groups'				  => 'member_groups,admin_groups,default_author',
		'define_rights'				  => 'guest_rights,member_rights,admin_rights',
		'enable_maps'			  	  => 'map_type,map_id,map_location_label,map_override_locstyles,map_label,map_tooltip,map_popup,map_link',
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
        'sorting' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'threads' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'posts' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'last_thread_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'last_post_id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'enable_maps_inherited' => array
        (
            'sql'                     => "char(1) NOT NULL default ''"
        ),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
            'search'                  => 'true',
			'sorting'                 => 'true',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255 ),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'optional_names' => array
        (
            'label'			=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_names'],
            'exclude' 		=> true,
            'inputType'     => 'multiColumnWizard',
            'eval' 			=> array
            (
                'columnFields' => array
                (
                    'optional_name' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_name'],
                        'exclude'               => true,
                        'inputType'             => 'text',
                        'eval' 			        => array('tl_class'=>'w50')
                    ),
                    'optional_language' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language'],
                        'exclude'               => true,
                        'inputType'             => 'select',
                        'options'               => \System::getLanguages(),
                        'eval'                  => array('chosen' => true, 'style'=>'width: 200px')
                    )
                )
            ),

            'sql' => "blob NULL"
        ),
        'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['headline'],
			'exclude'                 => true,
			'search'                  => true,
            'default'                 => array('value'=>'', 'unit'=>'h1'),
			'inputType'               => 'inputUnit',
			'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'                    => array('maxlength'=>200, 'tl_class'=>'long clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'optional_headlines' => array
        (
            'label'			=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headlines'],
            'exclude' 		=> true,
            'inputType'     => 'multiColumnWizard',
            'eval' 			=> array
            (
                'columnFields' => array
                (
                    'optional_headline' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_headline'],
                        'exclude'               => true,
                        'default'               => array('value'=>'', 'unit'=>'h1'),
                        'inputType'             => 'inputUnit',
                        'options'               => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
                        'eval'                  => array('maxlength'=>200, 'style'=>'width: 250px'),
                    ),
                    'optional_headline_language' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language'],
                        'exclude'               => true,
                        'inputType'             => 'select',
                        'options'               => \System::getLanguages(),
                        'eval'                  => array('chosen' => true, 'style'=>'width: 200px')
                    )
                )
            ),

            'sql' => "blob NULL"
        ),
		'description' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['description'],
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'                  => array('style' => 'height:60px', 'tl_class'=>'long clr'),
            'sql'                   => "blob NULL"
		),
        'optional_descriptions' => array
        (
            'label'			=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_descriptions'],
            'exclude' 		=> true,
            'inputType'     => 'multiColumnWizard',
            'eval' 			=> array
            (
                'columnFields' => array
                (
                    'optional_description' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_description'],
                        'exclude'               => true,
                        'inputType'             => 'textarea',
                        'eval' 			        => array('tl_class'=>'w50')
                    ),
                    'optional_description_language' => array
                    (
                        'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['optional_language'],
                        'exclude'               => true,
                        'inputType'             => 'select',
                        'options'               => \System::getLanguages(),
                        'eval'                  => array('chosen' => true, 'tl_class'=>'w50', 'style'=>'width: 200px')
                    )
                )
            ),

            'sql' => "blob NULL"
        ),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['published'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'box_imagesrc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['box_imagesrc'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'extensions'=>'gif,jpg,jpeg,png,svg', 'tl_class'=>'clr', 'mandatory'=>false),
            'sql'                     => "binary(16) NULL"
		),


		'use_intropage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['use_intropage'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'intropage' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage'],
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
		),

		'intropage_forumbtn' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn'],
			'exclude'               => true,
			'default'               => '',
			'inputType'             => 'text',
			'eval'                  => array('maxlength'=>100 ),
            'sql'                   => "varchar(100) NOT NULL default ''"
		),

		'intropage_forumbtn_jqui' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_c4g_forum']['intropage_forumbtn_jqui'],
			'exclude'               => true,
			'default'               => true,
			'inputType'             => 'checkbox',
            'sql'                   => "char(1) NOT NULL default '1'"
		),

		'pretext' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['pretext'],
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
		),

		'posttext' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_forum']['posttext'],
            'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
		),

		'define_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['define_groups'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'member_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['member_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'admin_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'define_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['define_rights'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),


		'guest_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['guest_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_forum','getGuestRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'member_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['member_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_forum','getRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'admin_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_forum','getRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'enable_maps' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['enable_maps'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'eval'					  => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'map_type' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_type'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('EDIT','PICK','OSMID'),
            'default'                 => 'EDIT',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['references'],
            'eval'					  => array('submitOnChange'=>true),
            'sql'                     => "char(5) NOT NULL default 'PICK'"
		),

		'map_override_locationstyle' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locationstyle'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'eval'					  => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'map_override_locstyles' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_override_locstyles'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'options_callback'        => array('tl_c4g_forum','getAllLocStyles'),
            'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'map_id' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_id'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_c4g_forum', 'get_maps'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		'map_location_label' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_location_label'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>20 ),
            'sql'                     => "char(20) NOT NULL default ''"
		),

		'map_label' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_label'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','SUBJ','LINK','CUST'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
        ),

		'map_tooltip' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_tooltip'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','SUBJ','LINK','CUST'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
		),

		'map_popup' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_popup'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','SUBJ','POST','SUPO'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
		),

		'map_link' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_link'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('OFF','POST','THREA','PLINK'),
            'default'                 => 'OFF',
            'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['references'],
            'sql'                     => "char(5) NOT NULL default 'NONE'"
		),

		'linkurl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['linkurl'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'wizard'),
			'wizard' 				  => array(array('tl_c4g_forum', 'pickLinkUrl')),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),

		'link_newwindow' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['link_newwindow'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
		),

		'sitemap_exclude' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['sitemap_exclude'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
		),
		'tags' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['tags'],
            'search'				  => true,
            'inputType'               => 'text',
            'load_callback'           => array("tl_c4g_forum" => "decodeTags"),
            'eval'                    => array(),
            'sql'                     => "blob NULL"
		),
        'default_author' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['default_author'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('includeBlankOption' => 'true'),
            'sql'                     => "int(10) default '0'"
		),
        'member_id' => array
        (
            'sql'                     =>'int(10) default "0"'
        ),
        'concerning' => array
        (
            'sql'                     =>'int(10) default "0"'
        )
	)
);

/**
 * Class tl_c4g_forum
 */
class tl_c4g_forum extends \Backend
{

	/**
	 * Import BackendUser object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->import('BackendUser', 'User');
		$this->loadLanguageFile('stopwords');

	}


    public function setMailTextDefault($varValue, DataContainer $dc){
        if(empty($varValue)){
            return $GLOBALS['TL_LANG']['tl_c4g_forum']['default_subscription_text'];
        }else{
            return $varValue;
        }
    }

	/**
	 * Return the copy page with subpages button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPageWithSubpages($row, $href, $label, $title, $icon, $attributes, $table)
	{

		$objSubpages = $this->Database->prepare("SELECT id FROM tl_c4g_forum WHERE pid=?")
									  ->limit(1)
									  ->execute($row['id']);

		if ($objSubpages->numRows > 0) {
		  return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		} else {
		  return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
	}


    public function decodeTags($sValue, $oDca){
        echo "<pre>";
        var_dump($sValue);
        die();
    }

	/**
	 * Update the palette information that depend on other values
	 */
	public function updateDCA(DataContainer $dc)
	{
		if ($dc->Database != null) {
			$helper = new con4gis\ForumBundle\Classes\C4GForumHelper($dc->Database);
			$GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['guest_rights']['default'] =
				$helper->getGuestDefaultRights();
	    	$GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['member_rights']['default'] =
				$helper->getMemberDefaultRights();
	    	$GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['admin_rights']['default'] =
				$helper->getAdminDefaultRights();
		}


	    if (!$dc->id) {
	    	return;
	    }
		$objForum = $this->Database->prepare("SELECT use_intropage, map_type, map_override_locationstyle FROM tl_c4g_forum WHERE id=?")
			->limit(1)
			->execute($dc->id);
	    if ($objForum->numRows > 0) {
	    	if ($objForum->use_intropage) {
	    		// used this way because subpalettes don't work well with TinyMCE fields!!
	    		$GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['default'] =
		  			$GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['with_intropage'];
	    	}
	    }

	    // add Maps section if c4gMaps is installed
	    if (C4GVersionProvider::isInstalled('con4gis/maps')) {
	    	$c4gMapsFields = '{maps_legend:hide},enable_maps;';
	    	$GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['default'] =
	    		str_replace('{expert_legend',$c4gMapsFields.'{expert_legend',
	    				$GLOBALS['TL_DCA']['tl_c4g_forum']['palettes']['default']);

		    if ($objForum->numRows > 0) {
			    if ($objForum->map_type == 'EDIT') {
			    	$GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps'] =
			    		str_replace('map_override_locstyles,',
			    			'', $GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps']);
			    }

			    if ($objForum->map_type == 'OSMID') {
			    	$GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps'] =
			    		str_replace('map_id,map_location_label,map_override_locstyles,map_label,map_tooltip,map_popup,map_link',
			    			'map_override_locationstyle,map_id', $GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps']);
			    }

			    if ($objForum->map_override_locationstyle) {
			    	$GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps'] =
			    		str_replace('map_override_locationstyle,',
			    			'map_override_locationstyle,map_override_locstyles,', $GLOBALS['TL_DCA']['tl_c4g_forum']['subpalettes']['enable_maps']);
			    }
			}

	    }

	}

	/**
	 * Return all Location Styles as array
	 * @param object
	 * @return array
	 */
	public function getAllLocStyles(DataContainer $dc)
	{
		$locStyles = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_locstyles ORDER BY name")
			->execute();
		while ($locStyles->next())
		{
			$return[$locStyles->id] = $locStyles->name;
		}
		return $return;
	}
    public function forumThread($row, $href, $label, $title, $icon)
    {

        $href .= "&amp;id=".$row['id'];
        return '<a href="' . $this->addToUrl($href) . '" title="'.specialchars($title).'">'.Image::getHtml($icon, $label).'</a> ';
    }

	/**
	 * Return the "toggle visibility" button
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_c4g_forum::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_c4g_forum::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish C4GMaps ID "'.$intId.'"', 'tl_c4g_forum toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_c4g_forum', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_c4g_forum']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_c4g_forum SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_c4g_forum', $intId);
	}

	/**
	 *
	 * Get List of available rights
	 * @param DataContainer $dc
	 */
	public function getGuestRightList(DataContainer $dc)
	{
		if ($dc->Database != null) {
			$helper = new con4gis\ForumBundle\Classes\C4GForumHelper($dc->Database);
			$rights = $helper->getGuestRightList();
			foreach ($rights as $right) {
				$return[$right] = $GLOBALS['TL_LANG']['tl_c4g_forum']['right_'.$right];
			}
		}
		return $return;
	}

	/**
	 *
	 * Get List of available rights
	 * @param DataContainer $dc
	 */
	public function getRightList(DataContainer $dc)
	{
		if ($dc->Database != null) {
			$helper = new con4gis\ForumBundle\Classes\C4GForumHelper($dc->Database);
			$rights = $helper->getRightList();
			foreach ($rights as $right) {
				$return[$right] = $GLOBALS['TL_LANG']['tl_c4g_forum']['right_'.$right];
			}
		}
		return $return;
	}


	/**
	 * @param DataContainer $dc
	 */
	public function onSubmit(DataContainer $dc)
	{
		if (($dc->activeRecord != null) && ($dc->Database != null)) {
			$helper = new con4gis\ForumBundle\Classes\C4GForumHelper($dc->Database);
			$helper->updateForumRightsAndGroupInheritance($dc->activeRecord->id,$dc->activeRecord->pid);
			$helper->updateMapEnabledInheritance($dc->activeRecord->id,$dc->activeRecord->pid);
		}

	}

	public static function onDeleteForum(DataContainer $dc)
	{
		if (($dc->activeRecord != null) && ($dc->Database != null))
		{
			if ($dc->activeRecord->id > 0)
			{
				$helper = new con4gis\ForumBundle\Classes\C4GForumHelper($dc->Database);
				// TODO move old threads and posts to a paper bin
			}
		}
	}

	/**
	 * Return the page pick wizard for the linkUrl
	 * @param DataContainer $dc
	 */
	public function pickLinkUrl(DataContainer $dc)
	{
		if (version_compare(VERSION,'3','<')) {
			$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
			return ' ' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickPage(\'' . $strField . '\')"');
		}
		else {
			return ' <a href="contao/page.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
		}
	}

	/**
	 * Return all defined maps
	 * @param object
	 * @return array
	 */
	public function get_maps(DataContainer $dc)
	{
		$maps = $this->Database->prepare ( "SELECT * FROM tl_c4g_maps WHERE is_map=1 AND published=1" )->execute ();
		if ($maps->numRows > 0) {
			while ( $maps->next () ) {
				$return [$maps->id] = $maps->name;
			}
		}
		return $return;
	}
	public function get_label($arrRow)
    {
        $threads = $this->Database->prepare('SELECT * FROM tl_c4g_forum_thread WHERE pid=?')->execute($arrRow['id'])->fetchAllAssoc();
        $unreadTickets = false;
        foreach($threads as $thread){
            if($thread['state'] == 1){
                $unreadTickets = true;
            }
        }

        $return = $arrRow['name'];

        $settings = Database::getInstance()->execute("SELECT * FROM tl_c4g_settings LIMIT 1")->fetchAllAssoc();

        if ($settings) {
            $settings = $settings[0];
        }
        if ($settings && $settings['c4g_forum_type']) {
            if($unreadTickets && $settings['c4g_forum_type'] == "TICKET") {
                $return = $arrRow['name'] . $GLOBALS['TL_LANG']['tl_c4g_forum']['unreaded_tickets'];
            }
        }

        return $return;
    }
    public function remove_bb()
    {
        $posts = $this->Database->prepare('SELECT id,text FROM tl_c4g_forum_post')->execute()->fetchAllAssoc();
        // BBCode tags to replace
        $find = array(
            '~\[b\](.*?)\[/b\]~s',
            '~\[i\](.*?)\[/i\]~s',
            '~\[u\](.*?)\[/u\]~s',
            '~\[quote\](.*?)\[/quote\]~s',
            '~\[url=(.*?)\](.*?)\[/url\]~s',
            '~\[url](.*?)\[/url\]~s',
            '~\[size=(.*?)\](.*?)\[/size\]~s',
            '~\[color=(.*?)\](.*?)\[/color\]~s',
            '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
        );
        // HTML tags to replace BBCode
        $replace = array(
            '<b>$1</b>',
            '<i>$1</i>',
            '<span style="text-decoration:underline;">$1</span>',
            '<pre>$1</'.'pre>',
            '<a href="$1">$2</a>',
            '<a href="$1">$1</a>',
            '<span style="font-size:$1px;">$2</span>',
            '<span style="color:$1;">$2</span>',
            '<img src="$1" alt="" />'
        );

        foreach($posts as $post)
        {

            //Status des Tickets auf gelesen ändern

            $set['text'] = preg_replace($find,$replace,$post['text']);
            $this->Database->prepare('UPDATE tl_c4g_forum_post %s WHERE id=?')->set($set)->execute($post['id']);
        }
    }
}
?>