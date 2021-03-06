<?php

namespace EdgarEz\TFABundle\Repository;

use EdgarEz\TFABundle\Entity\TFA;

/**
 * TFARepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TFARepository extends \Doctrine\ORM\EntityRepository
{
    public function setProvider($userId, $provider)
    {
        $tfa = new TFA();
        $tfa->setUserId($userId);
        $tfa->setProvider($provider);

        $this->getEntityManager()->persist($tfa);
        $this->getEntityManager()->flush();
    }
}
