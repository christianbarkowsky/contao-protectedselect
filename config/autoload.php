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
 * Register the classes
 */
\Contao\ClassLoader::addClasses([
    'Contao\FormProtectedSelectMenu' => 'system/modules/protectedselect/forms/FormProtectedSelectMenu.php',
    'Contao\ProtectedOptionWizard'   => 'system/modules/protectedselect/forms/ProtectedOptionWizard.php',
]);

/**
 * Register the templates
 */
\Contao\TemplateLoader::addFiles([
    'form_protected_select'          => 'system/modules/protectedselect/templates'
]);
