<?php

namespace CoreBundle\Entity;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;

/**
 * RefreshToken
 */
class RefreshToken extends BaseRefreshToken
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

