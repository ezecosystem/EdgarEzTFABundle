<?php

namespace EdgarEz\TFABundle\Security;

use EdgarEz\TFABundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthHandler
 * @package EdgarEz\TFABundle\Security
 */
class AuthHandler implements ProviderInterface
{
    /** @var ProviderInterface[] $providers */
    private $providers = array();

    /** @var string $providerAlias */
    protected $providerAlias;

    /**
     * AuthHandler constructor.
     * @param string $providerAlias
     */
    public function __construct($providerAlias = '')
    {
        $this->providerAlias = $providerAlias;
    }

    /**
     * @param ProviderInterface $provider
     * @param $alias
     */
    public function addProvider(ProviderInterface $provider, $alias)
    {
        $this->providers[$alias] = $provider;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isAuthenticated(Request $request)
    {
        if (!isset($this->providers[$this->providerAlias]))
            return true;

        return $this->providers[$this->providerAlias]->isAuthenticated($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function requestAuthCode(Request $request)
    {
        if (!isset($this->providers[$this->providerAlias]))
            return false;

        return $this->providers[$this->providerAlias]->requestAuthCode($request);
    }
}
