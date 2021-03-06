<?php

namespace Ethinking\PushConnectorBundle\DependencyInjection\Compiler;

use Ethinking\PushConnector\Connector\Channels\Mapper\Content\Fields\ContentFieldsMapperInterface;
use Ethinking\PushConnector\Connector\Channels\Provider\PushConnectorChannelsInterface;
use Ethinking\PushConnector\Connector\Channels\Registry\ContentFieldsMapperRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContentFieldsMapperPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        //check if ChannelsRegistry exist
        if (!$container->hasDefinition(ContentFieldsMapperRegistry::class)) {
            return;
        }

        $contentFieldsMapperTagged = $container->findTaggedServiceIds(ContentFieldsMapperInterface::class);

        $contentFieldsMapper = [];

        foreach ($contentFieldsMapperTagged as $id => $tags) {
            foreach ($tags as $attributes) {
                $contentFieldsMapper[$attributes] = new Reference($id);
            }
        }

        $contentFieldsMapperRegistryDef = $container->findDefinition(ContentFieldsMapperRegistry::class);
        $contentFieldsMapperRegistryDef->setArguments([$contentFieldsMapper]);
    }
}
