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

    /**
     * Return siteaccess host
     *
     * @param Request $request
     * @return string
     */
    protected function getSiteaccessUrl(Request $request)
    {
        $semanticPathinfo = $request->attributes->get('semanticPathinfo') ?: '/';
        $semanticPathinfo = rtrim($semanticPathinfo, '/');
        $uri = $request->getUri();
        if (!$semanticPathinfo)
            return $uri;

        return substr($uri, 0, -strlen($semanticPathinfo));
    }
}
