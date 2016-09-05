<?php

namespace EdgarEz\TFABundle\Provider\Email\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\Security\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AuthController
 * @package EdgarEz\TFABundle\Provider\Email\Controller
 */
class AuthController extends Controller
{
    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /** @var ConfigResolverInterface $configResolver */
    protected $configResolver;

    /** @var \Swift_Mailer $mailer */
    protected $mailer;

    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var array $providers */
    public $providers;

    /**
     * AuthController constructor.
     *
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        TokenStorage $tokenStorage,
        ConfigResolverInterface $configResolver,
        \Swift_Mailer $mailer,
        TranslatorInterface $translator
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->configResolver = $configResolver;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function setProviders(array $providers)
    {
        $this->providers = $providers;
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

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $emailTo = $user->getAPIUser()->email;
        $emailFrom = $this->providers['email']['from'];

        $this->sendCode($code, $emailFrom, $emailTo);

        return $this->render('EdgarEzTFABundle:tfa:email/auth.html.twig', [
            'layout' => $this->configResolver->getParameter('pagelayout')
        ]);
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

    protected function sendCode($code, $emailFrom, $emailTo)
    {
        $message = \Swift_Message::newInstance();

        $message->setSubject($this->translator->trans('Two Factor Authentication code'))
            ->setFrom($emailFrom)
            ->setTo($emailTo)
            ->setBody(
                $this->renderView(
                    'EdgarEzTFABundle:tfa:email/mail.txt.twig',
                    array(
                        'code' => $code
                    )
                ), 'text/html'
            );

        $this->mailer->send($message);
    }
}
