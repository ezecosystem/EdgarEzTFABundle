<?php

namespace EdgarEz\TFABundle\Provider\SMS\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\FieldType\TextLine\Value;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\Security\User;
use Ovh\Api;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;

class AuthController extends Controller
{
    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /** @var ConfigResolverInterface $configResolver */
    protected $configResolver;

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
        TranslatorInterface $translator,
        array $providers
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->configResolver = $configResolver;
        $this->translator = $translator;
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
        $apiUser = $user->getAPIUser();
        /** @var Value $phoneNumberValue */
        $phoneNumberValue = $apiUser->getField('phone_number')->value;
        $phoneNumber = $phoneNumberValue->text;

        $codeSended = $session->get('tfa_codesended', false);
        if (!$codeSended) {
            $this->sendCode($code, $phoneNumber);
            $session->set('tfa_codesended', true);
        }

        return $this->render('EdgarEzTFABundle:tfa:sms/auth.html.twig', [
            'layout' => $this->configResolver->getParameter('pagelayout')
        ]);
    }

    /**
     * Send SMS Code
     *
     * @param string $code SMS code
     */
    protected function sendCode($code, $phoneNumber)
    {
        $endpoint = 'ovh-eu';

        $smsConn = new Api(
            $this->providers['sms']['application_key'],
            $this->providers['sms']['application_secret'],
            $endpoint,
            $this->providers['sms']['consumer_key']
        );

        $message = $this->renderView(
            'EdgarEzTFABundle:tfa:sms/sms.txt.twig',
            array(
                'code' => $code
            )
        );

        $smsServices = $smsConn->get('/sms/');

        $content = (object) array(
            "charset" => "UTF-8",
            "class" => "phoneDisplay",
            "coding" => "7bit",
            "message" => $message,
            "noStopClause" => false,
            "priority" => "high",
            "receivers" => [ $phoneNumber ],
            "senderForResponse" => true,
            "validityPeriod" => 2880
        );
        $smsConn->post('/sms/'. $smsServices[0] . '/jobs/', $content);
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
            return $this->redirectToRoute('tfa_sms_auth_form');
        }
    }
}
