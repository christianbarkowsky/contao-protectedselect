<?php

/*
 * protected select
 * Adds a new formula select widget which hides the internal field values in the frontend
 *
 * @copyright  Christian Barkowsky 2015, Jan Theofel 2011-2014, ETES GmbH 2010
 * @author     Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author     Jan Theofel <jan@theofel.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['protectedselect'] = '{type_legend},type,name,label;{options_legend},protectedOptions;{fconfig_legend},mandatory,multiple;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit';


/**
 * Fields
 */
/**/
$GLOBALS['TL_DCA']['tl_form_field']['fields']['protectedOptions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['protectedOptions'],
	'exclude'                 => true,
	'inputType'               => 'protectedOptionWizard',
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "blob NULL",
);
/**/

/*
$GLOBALS['TL_DCA']['tl_form_field']['fields']['protectedOptions'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_form_field']['protectedOptions'],
	'exclude' 		=> true,
	'inputType' 	=> 'multiColumnWizard',
	'eval' 			=> array
	(
		'columnFields' => array
		(

			'label' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_form_field']['opReference'],
				'exclude'               => true,
				'inputType'             => 'text',
				'eval' 					=> array('style'=>'width:180px')
			),
			'value' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_form_field']['opValueProtected'],
				'exclude'               => true,
				'inputType'             => 'text',
				'eval' 					=> array('style'=>'width:180px')
			),
			'reference' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_form_field']['opLabel'],
				'exclude'               => true,
				'inputType'             => 'text',
				'eval' 					=> array('style'=>'width:180px'),
				'mandatory'				=> true
			),
		),
		'mandatory'=>true
	),
	'sql'                     => "blob NULL",
);
*/
