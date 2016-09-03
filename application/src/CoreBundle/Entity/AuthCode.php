<?php

namespace CoreBundle\Entity;
use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;

/**
 * AccessToken
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

