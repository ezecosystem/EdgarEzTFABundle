<?php

namespace EdgarEz\TFABundle\Provider\SMS;

use EdgarEz\TFABundle\Provider\ProviderAbstract;
use EdgarEz\TFABundle\Provider\ProviderInterface;
use EdgarEz\TFABundle\Repository\TFARepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SMSProvider extends ProviderAbstract implements ProviderInterface
{
    /** @var Router $router */
    protected $router;

    public function __construct(Router $router, Session $session)
    {
        parent::__construct($session);
        $this->router = $router;
    }

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

        $redirectUrl =  $this->router->generate('tfa_sms_auth_form');

        return $redirectUrl;
    }

    public function register(
        TFARepository $tfaRepository,
        $userId, $provider
    )
    {
        return $this->router->generate('tfa_sms_register_form');
    }

    public function getIdentifier()
    {
        return 'sms';
    }

    public function getName()
    {
        return 'SMS Provider';
    }

    public function getDescription()
    {
        return 'description provider sms';
    }
}
