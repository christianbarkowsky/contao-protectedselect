<?php

/*
 * Contact maintainer Jan Theofel: hallo@christianbarkowsky.de
 *
 * PHP version 5
 * @copyright  Christian Barkowsky 2015 <hallo@christianbarkowsky.de>
 * @copyright  Jan Theofel 2011-2013, ETES GmbH 2010
 * @author     Jan Theofel <jan@theofel.de>, Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: $
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['protectedselect'] = '{type_legend},type,name,label;{options_legend},protectedOptions;{fconfig_legend},mandatory,multiple;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['protectedOptions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['protectedOptions'],
	'exclude'                 => true,
	'inputType'               => 'protectedOptionWizard',
	'eval'                    => array('mandatory'=>true),
	'sql' 					  => 'blob NULL'
);
