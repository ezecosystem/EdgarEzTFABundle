<?php

namespace EdgarEz\TFABundle\Provider;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface ProviderInterface
 *
 * @package EdgarEz\TFABundle\Provider
 */
interface ProviderInterface
{
    public function isAuthenticated(Request $request);

    public function requestAuthCode(Request $request);
}
