<?php use con4gis\CoreBundle\Classes\C4GVersionProvider;
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2026, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use Contao\Image;

/**
 * Table tl_c4g_forum
 */
$GLOBALS['TL_DCA']['tl_c4g_forum'] = array
(
	'config' => array
	(
	    'label'                       => &$GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => \Contao\DC_Table::class,
		'ctable'                      => array('tl_c4g_forum_thread'),
		'enableVersioning'            => true,
	    'onload_callback'			  => array(
											array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'updateDCA')
										 ),
	    'onsubmit_callback'           => array(
            array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'onSubmit')
        ),
		'ondelete_callback'			  => array(
											array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'onDeleteForum')
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

	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'fields'                  => array('name'),
			'flag'                    => 1,
            'icon'                    => 'bundles/con4giscore/images/be-icons/con4gis_blue.svg',
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
            'label_callback'          => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','get_label')
		),
		'global_operations' => array
		(
			'index' => array
			(
				'label'				  => &$GLOBALS['TL_LANG']['tl_c4g_forum']['build_index'],
				'href'				  => 'key=build_index',
				'class'				  => 'navigation',
                'icon'                => 'sync.svg',
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
                'button_callback'     => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','remove_bb')
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
				'button_callback'     => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'copyPageWithSubpages')
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
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\')) return false;Backend.getScrollOffset()"',
			),
			'thread' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['thread'],
                'href'                => 'do=c4g_forum_thread',
                'icon'	 		      => 'tablewizard.svg',
                'button_callback'     => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','forumThread')
            ),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_forum']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

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
										 '{expert_legend:hide},linkurl,link_newwindow,sitemap_exclude,auto_subscribe,maxPostsPerThread,charLimitPerPost;',

	    // used in updateDCA(), because subpalettes don't work well with TinyMCE fields!!
		'with_intropage'              => '{general_legend},name,optional_names,headline,optional_headlines,description,optional_descriptions,published;'.
                                         '{language_legend:hide},optional_names,optional_headlines,optional_descriptions;'.
										 '{comfort_legend},box_imagesrc;'.
										 '{intropage_legend},use_intropage,intropage,intropage_forumbtn,intropage_forumbtn_jqui;'.
										 '{infotext_legend:hide},pretext,posttext;'.
										 '{groups_legend:hide},define_groups;'.
										 '{rights_legend:hide},define_rights;'.
										 '{expert_legend:hide},linkurl,link_newwindow,sitemap_exclude,auto_subscribe,maxPostsPerThread,charLimitPerPost;',

	),

	'subpalettes' => array(
		'define_groups'				  => 'member_groups,admin_groups,default_author',
		'define_rights'				  => 'guest_rights,member_rights,admin_rights',
		'enable_maps'			  	  => 'map_profile,map_location_label,map_override_locstyles,map_label,map_tooltip,map_popup,map_link',
	),

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
        'importId' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'eval'                    => array('doNotCopy' => true)
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
                        'options_callback'      => [\con4gis\ForumBundle\Classes\Callbacks\ModuleCallback::class, 'getLanguages'],
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
                        'options_callback'      => [\con4gis\ForumBundle\Classes\Callbacks\ModuleCallback::class, 'getLanguages'],
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
                        'options_callback'      => [\con4gis\ForumBundle\Classes\Callbacks\ModuleCallback::class, 'getLanguages'],
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
		    'options_callback'        => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','getGuestRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'member_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['member_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','getRightList'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
		),

		'admin_rights' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['admin_rights'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','getRightList'),
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
        
        'map_profile' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['map_profile'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => ['con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'loadMapProfiles'],
            'eval'                    => ['tl_class'=>'long',
                                               'submitOnChange' => true, 'chosen' => true, 'alwaysSave' => true],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
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
            'options_callback'        => array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback','getAllLocStyles'),
            'eval'                    => array('mandatory'=>false, 'multiple'=>true),
            'sql'                     => "blob NULL"
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
			'wizard' 				  => array(array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'pickLinkUrl')),
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
        'auto_subscribe' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['auto_subscribe'],
            'exclude'                 => true,
            'default'                 => '',
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
		),
        'maxPostsPerThread' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['maxPostsPerThread'],
            'exclude'                 => true,
            'default'                 => 0,
            'inputType'               => 'text',
            'eval'                    => [
                'rgxp' => 'natural'
            ],
            'sql'                     => "int NOT NULL default 0"
		),
        'charLimitPerPost' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['charLimitPerPost'],
            'exclude'                 => true,
            'default'                 => 0,
            'inputType'               => 'text',
            'eval'                    => [
                'rgxp' => 'natural'
            ],
            'sql'                     => "int NOT NULL default 0"
		),
		'tags' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_forum']['tags'],
            'search'				  => true,
            'inputType'               => 'text',
            'load_callback'           => array(array('con4gis\ForumBundle\Classes\Callbacks\ForumCallback', 'decodeTags')),
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
