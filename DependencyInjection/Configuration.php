<?php

namespace Hnk\HnkFrameworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("hnk_framework");

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode("seo")
                    ->children()
                        ->scalarNode("title")->defaultValue("HnkFramework")->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
