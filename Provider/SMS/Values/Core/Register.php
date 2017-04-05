<?php

namespace EdgarEz\TFABundle\Provider\SMS\Values\Core;

class Register extends \EdgarEz\TFABundle\Provider\SMS\Values\API\Register
{
    public function getId()
    {
        return $this->id;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}
