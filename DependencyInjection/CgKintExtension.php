<?php

namespace Cg\KintBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CgKintExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        \Kint::$enabled = $config['enabled'];
        \Kint::$maxLevels = $config['nesting_depth'];
        \Kint::$maxStrLength = $config['string_length'];

        $container->setParameter('cg_kint.enabled', $config['enabled']);
        $container->setParameter('cg_kint.nesting_depth', $config['nesting_depth']);
        $container->setParameter('cg_kint.string_length', $config['string_length']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
