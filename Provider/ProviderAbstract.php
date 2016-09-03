<?php

namespace EdgarEz\TFABundle\Provider;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProviderAbstract
 *
 * @package EdgarEz\TFABundle\Provider
 */
class ProviderAbstract
{
    /**
     * Test if user is TFA authenticated
     *
     * @param Request $request
     * @return boolean true|false if authenticated
     */
    public function isAuthenticated(Request $request)
    {
        $session = $request->getSession();
        return $session->get('tfa_authenticated', false);
    }
}
