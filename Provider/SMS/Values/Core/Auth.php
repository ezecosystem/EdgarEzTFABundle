<?php

namespace EdgarEz\TFABundle\Provider\SMS\Values\Core;

class Auth extends \EdgarEz\TFABundle\Provider\SMS\Values\API\Auth
{
    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }
}
