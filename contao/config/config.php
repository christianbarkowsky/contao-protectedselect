<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

use Plenta\ProtectedSelect\Classes\FormProtectedSelectMenu;
use Plenta\ProtectedSelect\Classes\ProtectedOptionWizard;

$GLOBALS['TL_FFL']['protectedselect'] = FormProtectedSelectMenu::class;

$GLOBALS['BE_FFL']['protectedOptionWizard'] = ProtectedOptionWizard::class;
