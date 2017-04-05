<?php

namespace EdgarEz\TFABundle\Provider\SMS\Data;

use EdgarEz\TFABundle\Provider\SMS\Values\API\Auth;

trait AuthDataTrait
{
    /**
     * @var Auth $auth
     */
    protected $auth;

    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function getId()
    {
        return $this->auth ? $this->auth->id : null;
    }
}
