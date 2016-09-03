<?php

namespace EdgarEz\TFABundle;

use EdgarEz\TFABundle\DependencyInjection\Compiler\ProviderPass;
use EdgarEz\TFABundle\DependencyInjection\Security\PolicyProvider\TFAPolicyProvider;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdgarEzTFABundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var EzPublishCoreExtension $eZExtension */
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new TFAPolicyProvider($this->getPath()));

        $container->addCompilerPass(new ProviderPass());
    }
}
