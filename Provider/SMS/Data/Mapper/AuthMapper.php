<?php

namespace EdgarEz\TFABundle\Provider\SMS\Data\Mapper;

use EdgarEz\TFABundle\Provider\SMS\Data\AuthCreateData;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;

class AuthMapper implements FormDataMapperInterface
{
    public function mapToFormData(ValueObject $auth, array $params = [])
    {
        $data = new AuthCreateData(['auth' => $auth]);

        return $data;
    }
}
