<?php

namespace EdgarEz\TFABundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ProviderPass
 * @package EdgarEz\TFABundle\DependencyInjection\Compiler
 */
class ProviderPass implements CompilerPassInterface
{
    /**
     * Process TFA providers
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('edgareztfa.security.auth_handler')) {
            return;
        }

        $definition = $container->findDefinition('edgareztfa.security.auth_handler');
        $taggedServices = $container->findTaggedServiceIds('edgareztfa.provider');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addProvider', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}
