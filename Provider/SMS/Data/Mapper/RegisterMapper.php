<?php

namespace EdgarEz\TFABundle\Provider\SMS\Data\Mapper;

use EdgarEz\TFABundle\Provider\SMS\Data\RegisterCreateData;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;

class RegisterMapper implements FormDataMapperInterface
{
    public function mapToFormData(ValueObject $register, array $params = [])
    {
        $data = new RegisterCreateData(['register' => $register]);

        return $data;
    }
}
