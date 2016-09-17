<?php

namespace CoreBundle\Entity;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * Client
 */
class Client extends BaseClient
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accessTokens;


    /**
     * Add accessToken
     *
     * @param \CoreBundle\Entity\AccessToken $accessToken
     *
     * @return Client
     */
    public function addAccessToken(\CoreBundle\Entity\AccessToken $accessToken)
    {
        $this->accessTokens[] = $accessToken;

        return $this;
    }

    /**
     * Remove accessToken
     *
     * @param \CoreBundle\Entity\AccessToken $accessToken
     */
    public function removeAccessToken(\CoreBundle\Entity\AccessToken $accessToken)
    {
        $this->accessTokens->removeElement($accessToken);
    }

    /**
     * Get accessTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $refreshTokens;


    /**
     * Add refreshToken
     *
     * @param \CoreBundle\Entity\RefreshToken $refreshToken
     *
     * @return Client
     */
    public function addRefreshToken(\CoreBundle\Entity\RefreshToken $refreshToken)
    {
        $this->refreshTokens[] = $refreshToken;

        return $this;
    }

    /**
     * Remove refreshToken
     *
     * @param \CoreBundle\Entity\RefreshToken $refreshToken
     */
    public function removeRefreshToken(\CoreBundle\Entity\RefreshToken $refreshToken)
    {
        $this->refreshTokens->removeElement($refreshToken);
    }

    /**
     * Get refreshTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRefreshTokens()
    {
        return $this->refreshTokens;
    }
}
