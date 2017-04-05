<?php

namespace EdgarEz\TFABundle\Provider\SMS\Data;

use EdgarEz\TFABundle\Provider\SMS\Values\RegisterCreateStruct;
use EzSystems\RepositoryForms\Data\NewnessCheckable;

class RegisterCreateData extends RegisterCreateStruct implements NewnessCheckable
{
    use RegisterDataTrait;

    public function isNew()
    {
        return true;
    }
}
