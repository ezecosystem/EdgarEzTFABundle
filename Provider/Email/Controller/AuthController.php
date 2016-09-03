<?php

namespace EdgarEz\TFABundle\Provider\Email\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class AuthController
 * @package EdgarEz\TFABundle\Provider\Email\Controller
 */
class AuthController extends Controller
{
    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /**
     * AuthController constructor.
     *
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Ask for TFA code authentication
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authAction(Request $request)
    {
        $session = $request->getSession();
        $code = $session->get('tfa_authcode', false);

        return $this->render('EdgarEzTFABundle:tfa:email/auth.html.twig', ['code' => $code]);
    }

    /**
     * Check for TFA code authentication
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function checkAction(Request $request)
    {
        $session = $request->getSession();
        $code = null;

        $TFACode = $session->get('tfa_authcode', false);
        $code = $request->get('code');

        if ($code && $code == $TFACode) {
            $session->set('tfa_authenticated', true);
            return new RedirectResponse($session->get('tfa_redirecturi'));
        } else {
            return $this->redirectToRoute('tfa_auth_form');
        }
    }
}
