<?php

namespace EdgarEz\TFABundle\EventListener;

use EdgarEz\TFABundle\Security\AuthHandler;
use eZ\Publish\Core\MVC\Symfony\Security\Authorization\Attribute;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * Class TFAListener
 * @package EdgarEz\TFABundle\EventListener
 */
class TFAListener implements EventSubscriberInterface
{
    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /** @var AccessDecisionManagerInterface $accessDecisionManager */
    protected $accessDecisionManager;

    /** @var AuthHandler $authHandler */
    protected $authHandler;

    /**
     * TFAListener constructor.
     *
     * @param TokenStorage $tokenStorage
     * @param AccessDecisionManagerInterface $accessDecisionManager
     * @param AuthHandler $authHandler
     */
    public function __construct(
        TokenStorage $tokenStorage,
        AccessDecisionManagerInterface $accessDecisionManager,
        AuthHandler $authHandler
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->accessDecisionManager = $accessDecisionManager;
        $this->authHandler = $authHandler;
    }

    /**
     * Subscribe to Kernel content event
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onRequest', 100)
        );
    }

    /**
     * Handle event
     *
     * @param FilterControllerEvent $event
     */
    public function onRequest(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if (strpos($request->getUri(), '/_tfa/') !== false)
            return;

        $token = $this->tokenStorage->getToken();
        if (!$token)
            return;

        if (!$this->authHandler->isAuthenticated($request)) {
            $redirectUrl = $this->authHandler->requestAuthCode($request);

            if ($redirectUrl) {
                $event->setController(
                    function () use ($redirectUrl) {
                        return new RedirectResponse($redirectUrl);
                    }
                );
            }
        }
    }
}
