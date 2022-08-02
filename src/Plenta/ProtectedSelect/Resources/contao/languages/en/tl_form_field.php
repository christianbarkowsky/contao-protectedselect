<?php

/**
 * protected select
 * Adds a new formula select widget which hides the internal field values in the frontend
 *
 * @copyright  Christian Barkowsky 2015-2019
 * @copyright  Jan Theofel 2011-2014, ETES GmbH 2010
 * @copyright  ETES GmbH 2010
 * @author     Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author     Jan Theofel <jan@theofel.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_form_field']['protectedOptions'] = &$GLOBALS['TL_LANG']['tl_form_field']['options'];

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_form_field']['opReference'] = 'Reference';
$GLOBALS['TL_LANG']['tl_form_field']['opValueProtected'] = 'Value (protected)';
$GLOBALS['TL_LANG']['tl_form_field']['opLabel'] = 'Label';
$GLOBALS['TL_LANG']['tl_form_field']['opDefault'] = 'Default';
$GLOBALS['TL_LANG']['tl_form_field']['opGroup'] = 'Group';
