<?php

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter_items']['title_legend'] = 'Headline';
$GLOBALS['TL_LANG']['tl_newsletter_items']['headline'] = array('Headline', 'Headline of the section. Leave the field empty to output no headline at all.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['text_legend'] = 'Text';
$GLOBALS['TL_LANG']['tl_newsletter_items']['text'] = array('Text', 'You can use HTML tags to format the text. The field may stay empty if the section is meant to consist of an image only.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['image_legend'] = 'Image settings';
$GLOBALS['TL_LANG']['tl_newsletter_items']['useImage'] = array('Use an image', 'Please only use images in their original resolution that have been prepared for the newsletter. They are taken over unchanged.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['singleSRC'] = array('Image', 'Please choose an image from the file manager.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['floating'] = array('Alignment', 'Determines whether the image is placed above, below, left or right of the text. With "left" and "right" the text wraps around the image.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['overwriteMeta'] = array('Override metadata', 'Override the metadata from the file manager.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['alt'] = array('Alternate text', 'Here you can enter an alternate text for the image (&lt;em&gt;alt&lt;/em&gt; attribute).');
$GLOBALS['TL_LANG']['tl_newsletter_items']['imageTitle'] = array('Image title', 'Here you can enter the title of the image (&lt;em&gt;title&lt;/em&gt; attribute).');
$GLOBALS['TL_LANG']['tl_newsletter_items']['imageUrl'] = array('Image link target', 'If an address is given, clicking the image leads there. Without it the image stays unlinked.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['caption'] = array('Image caption', 'Here you can enter a short text which will be shown underneath the image.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['expert_legend'] = 'Expert settings';
$GLOBALS['TL_LANG']['tl_newsletter_items']['cssID'] = array('CSS ID/class', 'Here you can set an ID and one or more classes.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['invisible_legend'] = 'Visibility';
$GLOBALS['TL_LANG']['tl_newsletter_items']['invisible'] = array('Invisible', 'Do not include the section in the newsletter.');

/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['tl_newsletter_items']['noHeadline'] = '- without headline -';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_items']['new'] = array('New section', 'Create a new section');
$GLOBALS['TL_LANG']['tl_newsletter_items']['edit'] = array('Edit section', 'Edit section ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_items']['copy'] = array('Duplicate section', 'Duplicate section ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_items']['cut'] = array('Move section', 'Move section ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_items']['delete'] = array('Delete section', 'Delete section ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_items']['toggle'] = array('Show/hide section', 'Show/hide section ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_items']['show'] = array('Section details', 'Show the details of section ID %s');
