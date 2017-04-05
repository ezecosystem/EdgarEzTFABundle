<?php

namespace EdgarEz\TFABundle\Provider\SMS\Values\API;

use eZ\Publish\API\Repository\Values\ValueObject;

class Register extends ValueObject
{
    protected $id;

    protected $phone;
}
