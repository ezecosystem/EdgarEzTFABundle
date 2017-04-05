<?php

namespace EdgarEz\TFABundle\Provider;

use EdgarEz\TFABundle\Repository\TFARepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface ProviderInterface
 *
 * @package EdgarEz\TFABundle\Provider
 */
interface ProviderInterface
{
    public function getIdentifier();

    public function getName();

    public function getDescription();

    public function isAuthenticated(Request $request);

    public function requestAuthCode(Request $request);

    public function register(TFARepository $tfaRepository, $userId, $provider);
}
