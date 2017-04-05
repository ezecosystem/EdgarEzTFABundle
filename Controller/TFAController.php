<?php

namespace EdgarEz\TFABundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use EdgarEz\TFABundle\Entity\TFA;
use EdgarEz\TFABundle\Provider\ProviderInterface;
use EdgarEz\TFABundle\Repository\TFARepository;
use EdgarEz\TFABundle\Repository\TFATrustedRepository;
use EdgarEz\TFABundle\Security\AuthHandler;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TFAController extends Controller
{
    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /** @var ConfigResolverInterface $configResolver */
    protected $configResolver;

    /** @var ProviderInterface[] $providers */
    protected $providers;

    /** @var AuthHandler $authHandler */
    protected $authHandler;

    /** @var \Doctrine\Common\Persistence\ObjectManager|object $entityManager */
    protected $entityManager;

    /** @var TFARepository $tfaRepository */
    protected $tfaRepository;

    /** @var TFATrustedRepository $tfaTrustedRepository */
    protected $tfaTrustedRepository;

    public function __construct(
        TokenStorage $tokenStorage,
        ConfigResolverInterface $configResolver,
        AuthHandler $authHandler,
        Registry $doctrineRegistry
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->configResolver = $configResolver;

        $this->authHandler = $authHandler;
        $this->providers = $this->authHandler->getProviders();

        $this->entityManager = $doctrineRegistry->getManager();
        $this->tfaRepository = $this->entityManager->getRepository('EdgarEzTFABundle:TFA');
        $this->tfaTrustedRepository = $this->entityManager->getRepository('EdgarEzTFABundle:TFA');
    }

    public function listAction()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $apiUser = $user->getAPIUser();

        /** @var TFA $userProvider */
        $userProvider = $this->tfaRepository->findOneByUserId($apiUser->id);
        $providersList = array();

        foreach ($this->providers as $provider) {
            $providersList[$provider->getIdentifier()] = array(
                'selected' => ($userProvider && $userProvider->getProvider() == $provider->getIdentifier()) ? true : false,
                'title' => $provider->getName(),
                'description' => $provider->getDescription()
            );
        }

        return $this->render('EdgarEzTFABundle:tfa:list.html.twig', [
            'layout' => $this->configResolver->getParameter('pagelayout'),
            'providersList' => $providersList
        ]);
    }

    public function clickAction($provider)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $apiUser = $user->getAPIUser();

        /** @var TFA $userProvider */
        $userProvider = $this->tfaRepository->findOneByUserId($apiUser->id);

        if ($userProvider) {
            $this->entityManager->remove($userProvider);
            $this->entityManager->flush();
        }

        $tfaProviders = $this->authHandler->getProviders();
        if (!isset($tfaProviders[$provider])) {
        }

        $tfaProvider = $tfaProviders[$provider];
        if ($redirect = $tfaProvider->register(
            $this->tfaRepository,
            $apiUser->id,
            $provider
        )) {
            return $this->redirect($redirect);
        }

        return $this->render('EdgarEzTFABundle:tfa:click.html.twig', [
            'layout' => $this->configResolver->getParameter('pagelayout'),
            'provider' => $provider
        ]);
    }

    public function registeredAction($provider)
    {
        return $this->render('EdgarEzTFABundle:tfa:click.html.twig', [
            'layout' => $this->configResolver->getParameter('pagelayout'),
            'provider' => $provider
        ]);
    }
}
