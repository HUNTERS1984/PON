<?php

namespace CoreBundle\Entity;
use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;

/**
 * AccessToken
 */
class AccessToken extends BaseAccessToken
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
