<?php

namespace EdgarEz\TFABundle\Provider\SMS;

use EdgarEz\TFABundle\Provider\ProviderAbstract;
use EdgarEz\TFABundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class SMSProvider extends ProviderAbstract implements ProviderInterface
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
        $redirectUrl = $siteaccessUrl . '/_tfa/sms/auth';

        return $redirectUrl;
    }
}
