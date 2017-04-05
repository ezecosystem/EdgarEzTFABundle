<?php

namespace EdgarEz\TFABundle\Provider\SMS\Data;

use EdgarEz\TFABundle\Provider\SMS\Values\API\Register;

trait RegisterDataTrait
{
    /**
     * @var Register $register
     */
    protected $register;

    public function setRegister(Register $register)
    {
        $this->register = $register;
    }

    public function getId()
    {
        return $this->register ? $this->register->id : null;
    }
}
