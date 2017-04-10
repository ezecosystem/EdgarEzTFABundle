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
        $authCode = random_int(10000, 99999);
        $this->session->set('tfa_authcode', $authCode);
        $this->session->set('tfa_redirecturi', $request->getUri());

        $siteaccessUrl = $this->getSiteaccessUrl($request);
        $redirectUrl = $siteaccessUrl . '/_tfa/email/auth';

        return $redirectUrl;
    }

    public function getIdentifier()
    {
        return 'email';
    }

    public function getName()
    {
        return 'Email Provider';
    }

    public function getDescription()
    {
        return 'description provider email';
    }
}
