<?php

namespace EdgarEz\TFABundle\DependencyInjection\Security\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

/**
 * Class TFAPolicyProvider
 * @package EdgarEz\TFABundle\DependencyInjection\Security\PolicyProvider
 */
class TFAPolicyProvider extends YamlPolicyProvider
{
    /** @var string $path bundle path */
    protected $path;

    /**
     * TFAPolicyProvider constructor.
     *
     * @param string $path bundle path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * prepend policies to eZ Platform policy configuration
     *
     * @return array list of policies.yml
     */
    public function getFiles()
    {
        return [$this->path . '/Resources/config/policies.yml'];
    }
}
