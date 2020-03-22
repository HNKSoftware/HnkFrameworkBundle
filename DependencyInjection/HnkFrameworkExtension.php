<?php

namespace Hnk\HnkFrameworkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class HnkFrameworkExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.yml');

        $this->updateSeoManagerConfig($container, $config);
    }

    private function updateSeoManagerConfig(ContainerBuilder $container, array $config)
    {
        if (!isset($config["seo"], $config["seo"]["title"])) {
            return;
        }

        $definition = $container->getDefinition("hnk_framework.seo_manager");
        $definition->replaceArgument(0, $config["seo"]["title"]);
    }

}
