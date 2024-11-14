<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2024 Leo Feyer
 *
 * @package   DSB-Newsletter
 * @author    Frank Hoppe
 * @license   GNU/LPGL
 * @copyright Frank Hoppe 2024
 */


/**
 * Table tl_dsbnewsletter
 */
$GLOBALS['TL_DCA']['tl_dsbnewsletter'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'                 => 'Table',
		'ctable'                        => array('tl_dsbnewsletter_items'),
		'enableVersioning'              => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'                    => 'primary',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                      => 1,
			'fields'                    => array('datum'),
			'panelLayout'               => 'filter,sort;search,limit',
			'flag'                      => 12,
			'disableGrouping'           => true,
		),
		'label' => array
		(
			'fields'                    => array('datum', 'title'),
			'showColumns'               => true,
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                  => 'act=select',
				'class'                 => 'header_edit_all',
				'attributes'            => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['edit'],
				'href'                  => 'table=tl_dsbnewsletter_items',
				'icon'                  => 'edit.gif',
			),
			'editheader' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['editheader'],
				'href'                  => 'act=edit',
				'icon'                  => 'header.gif',
			),
			'copy' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['copy'],
				'href'                  => 'act=copy',
				'icon'                  => 'copy.gif'
			),
			'delete' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['delete'],
				'href'                  => 'act=delete',
				'icon'                  => 'delete.gif',
				'attributes'            => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'                => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['toggle'],
				'attributes'           => 'onclick="Backend.getScrollOffset()"',
				'haste_ajax_operation' => array
				(
					'field'            => 'published',
					'options'          => array
					(
						array('value' => '', 'icon' => 'invisible.svg'),
						array('value' => '1', 'icon' => 'visible.svg'),
					),
				),
			),
			'show' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['show'],
				'href'                  => 'act=show',
				'icon'                  => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                  => array(''),
		'default'                       => '{title_legend},title,datum;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		''                              => ''
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['id'],
			'sql'                       => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                       => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['title'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'search'                    => true,
			'eval'                      => array
			(
				'mandatory'             => true,
				'maxlength'             => 64,
				'tl_class'              => 'w50 clr'
			),
			'sql'                       => "varchar(64) NOT NULL default ''"
		),
		'datum' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['datum'],
			'exclude'                   => true,
			'default'                   => time(),
			'filter'                    => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'flag'                      => 8,
			'eval'                      => array
			(
				'rgxp'                  => 'date',
				'datepicker'            => true,
				'mandatory'             => true,
				'maxlength'             => 10,
				'tl_class'              => 'w50 widget'
			),
			'sql'                       => "int(10) unsigned NOT NULL default '0'"
		),
		'published' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_dsbnewsletter']['published'],
			'exclude'                   => true,
			'search'                    => false,
			'sorting'                   => false,
			'filter'                    => true,
			'inputType'                 => 'checkbox',
			'eval'                      => array('tl_class' => 'w50','isBoolean' => true),
			'sql'                       => "char(1) NOT NULL default ''"
		),
	)
);


/**
 * Class tl_dsbnewsletter
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_dsbnewsletter extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

}
