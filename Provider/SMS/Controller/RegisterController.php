<?php

namespace EdgarEz\TFABundle\Provider\SMS\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use EdgarEz\TFABundle\Entity\TFASMSPhone;
use EdgarEz\TFABundle\Provider\ProviderInterface;
use EdgarEz\TFABundle\Provider\SMS\Data\Mapper\RegisterMapper;
use EdgarEz\TFABundle\Provider\SMS\Form\Type\RegisterType;
use EdgarEz\TFABundle\Provider\SMS\Values\Core\Register;
use EdgarEz\TFABundle\Repository\TFARepository;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\Security\User;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\PlatformUIBundle\Http\FormProcessingDoneResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RegisterController extends Controller
{
    /** @var ConfigResolverInterface $configResolver */
    protected $configResolver;

    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /** @var TFARepository $tfaRepository */
    protected $tfaRepository;

    /** @var  TFASMSPhone $tfaSMSPhoneRepository  */
    protected $tfaSMSPhoneRepository;

    /** @var ProviderInterface $provider */
    protected $provider;

    public function __construct(
        ConfigResolverInterface $configResolver,
        TokenStorage $tokenStorage,
        Registry $doctrineRegistry,
        ProviderInterface $provider
    )
    {
        $this->configResolver = $configResolver;
        $this->tokenStorage = $tokenStorage;

        $entityManager = $doctrineRegistry->getManager();
        $this->tfaRepository = $entityManager->getRepository('EdgarEzTFABundle:TFA');
        $this->tfaSMSPhoneRepository = $entityManager->getRepository('EdgarEzTFABundle:TFASMSPhone');

        $this->provider = $provider;
    }

    public function registerAction(Request $request)
    {
        $actionUrl = $this->generateUrl('tfa_sms_register_form');

        $register = new Register();
        $data = (new RegisterMapper())->mapToFormData($register);
        $form = $this->createForm(RegisterType::class, $data);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();
            $apiUser = $user->getAPIUser();

            $this->tfaSMSPhoneRepository->setPhone($apiUser->id, $data->phone);
            $this->tfaRepository->setProvider($apiUser->id, $this->provider->getIdentifier());

            $redirectUrl = $this->generateUrl('tfa_registered', ['provider' => $this->provider->getIdentifier()]);
            $response = new RedirectResponse($redirectUrl);
            return $response;
        }

        return $this->render('EdgarEzTFABundle:tfa:sms/register.html.twig', [
            'layout' => $this->configResolver->getParameter('pagelayout'),
            'form' => $form->createView(),
            'register' => $data,
            'actionUrl' => $actionUrl
        ]);
    }
}
