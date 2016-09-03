<?php

namespace EdgarEz\TFABundle\Provider\Email;

use EdgarEz\TFABundle\Provider\ProviderAbstract;
use EdgarEz\TFABundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EmailProvider
 * @package EdgarEz\TFABundle\Provider\Email
 */
class EmailProvider extends ProviderAbstract implements ProviderInterface
{
    /**
     * Return url to request auth code
     *
     * @param Request $request
     * @return string
     */
    public function requestAuthCode(Request $request)
    {
        $session = $request->getSession();
        $authCode = random_int(10000, 99999);
        $session->set('tfa_authcode', $authCode);
        $session->set('tfa_redirecturi', $request->getUri());

        $siteaccessUrl = $this->getSiteaccessUrl($request);
        $redirectUrl = $siteaccessUrl . '/_tfa/email/auth';

        return $redirectUrl;
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
