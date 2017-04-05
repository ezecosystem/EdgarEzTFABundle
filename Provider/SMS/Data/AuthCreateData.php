<?php

namespace EdgarEz\TFABundle\Provider\SMS\Data;

use EdgarEz\TFABundle\Provider\SMS\Values\AuthCreateStruct;
use EzSystems\RepositoryForms\Data\NewnessCheckable;

class AuthCreateData extends AuthCreateStruct implements NewnessCheckable
{
    use AuthDataTrait;

    public function isNew()
    {
        return true;
    }
}
