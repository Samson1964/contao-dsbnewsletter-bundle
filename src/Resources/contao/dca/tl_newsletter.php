<?php

// Kindtabelle hinzufügen
$GLOBALS['TL_DCA']['tl_newsletter']['config']['ctable'] = array('tl_newsletter_items');

// Bearbeitungslink hinzufügen
$GLOBALS['TL_DCA']['tl_newsletter']['list']['operations']['editContent'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_newsletter']['editContent'],
	'href'                  => 'table=tl_newsletter_items',
	'icon'                  => 'bundles/contaodsbnewsletter/images/editContent.png'
);
