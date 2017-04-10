<?php

namespace EdgarEz\TFABundle\Provider;

use EdgarEz\TFABundle\Repository\TFARepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProviderAbstract
 *
 * @package EdgarEz\TFABundle\Provider
 */
class ProviderAbstract
{
    /** @var Session $session */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isAuthenticated()
    {
        return $this->session->get('tfa_authenticated', false);
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

    public function register(
        TFARepository $tfaRepository,
        $userId, $provider
    )
    {
        $tfaRepository->setProvider($userId, $provider);

        return null;
    }
}
