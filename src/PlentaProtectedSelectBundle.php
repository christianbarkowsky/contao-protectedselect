<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2023, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

namespace Plenta\ProtectedSelect;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Configures the bundle.
 */
class PlentaProtectedSelectBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
