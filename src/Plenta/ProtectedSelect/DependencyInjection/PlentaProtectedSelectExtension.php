<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2022, Plenta.io
 * @copyright     Christian Barkowsky 2015-2019
 * @copyright     Jan Theofel 2011-2014, ETES GmbH 2010
 * @copyright     ETES GmbH 2010
 * @author        Plenta.io <https://plenta.io>
 * @author        Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author        Jan Theofel <jan@theofel.de>
 * @author        Andreas Schempp <andreas@schempp.ch>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

namespace Plenta\ProtectedSelect\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PlentaProtectedSelectExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
