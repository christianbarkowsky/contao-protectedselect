<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

/*
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['protectedselect'] = '{type_legend},type,name,label;{options_legend},protectedOptions;{fconfig_legend},mandatory,multiple;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl;{submit_legend},addSubmit';

/*
 * Fields
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['protectedOptions'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['protectedOptions'],
    'exclude' => true,
    'inputType' => 'protectedOptionWizard',
    'eval' => ['mandatory' => true],
    'sql' => 'blob NULL',
];
