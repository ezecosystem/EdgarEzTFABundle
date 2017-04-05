<?php

namespace EdgarEz\TFABundle\Provider\SMS\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

abstract class AuthStruct extends ValueObject
{
    public $id;
}
