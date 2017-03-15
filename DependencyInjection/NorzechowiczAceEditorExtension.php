<?php

/**
 * This file is part of the AceEditorBundle.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Norzechowicz\AceEditorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class NorzechowiczAceEditorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerAceEditorParameters($config, $container);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.xml');
        $loader->load('twig.xml');
    }

    /**
     * Register parameters for the DI.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function registerAceEditorParameters(array $config, ContainerBuilder $container)
    {
        // use debug from the kernel.debug, but we can force it via "debug"
        $debug = $container->getParameter('kernel.debug');
        if (!$debug && $config['debug']) {
            $debug = true;
        }

        $mode = 'src'.($debug ? '' : '-min').($config['noconflict'] ? '-noconflict' : '');

        $container->setParameter('norzechowicz_ace_editor.options.autoinclude', $config['autoinclude']);
        $container->setParameter('norzechowicz_ace_editor.options.base_path', $config['base_path']);
        $container->setParameter('norzechowicz_ace_editor.options.mode', $mode);
    }
}
