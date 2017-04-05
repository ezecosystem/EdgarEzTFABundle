<?php

namespace EdgarEz\TFABundle\Provider\SMS\Values\API;

use eZ\Publish\API\Repository\Values\ValueObject;

class Auth extends ValueObject
{
    protected $id;

    protected $code;
}
