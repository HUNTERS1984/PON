<?php

namespace CoreBundle\Manager;

use Abraham\TwitterOAuth\TwitterOAuth;
use CoreBundle\Entity\AppUser;
use CoreBundle\Paginator\Pagination;
use Facebook\Facebook;

class AppUserManager extends AbstractManager
{
    /**
     * @var Facebook
    */
    protected $facebookManager;

    /**
     * @var TwitterOAuth
    */
    protected $twitterManager;

    public function dummy(AppUser $user)
    {
        $this->save($user);
    }

    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param AppUser $appUser
     *
     * @return AppUser
     */
    public function createAppUser(AppUser $appUser)
    {
        $appUser
            ->setEnabled(true)
            ->setCreatedAt(new \DateTime());
        return $this->saveAppUser($appUser);
    }

    /**
     * @param AppUser $appUser
     *
     * @return AppUser
     */
    public function saveAppUser(AppUser $appUser)
    {
        $appUser->setUpdatedAt(new \DateTime());
        return $this->save($appUser);
    }

    /**
     * @param AppUser $appUser
     *
     * @return boolean
     */
    public function deleteAppUser(AppUser $appUser)
    {
        $appUser
            ->setDeletedAt(new \DateTime())
            ->setEnabled(false);

        return $this->saveAppUser($appUser);
    }

    /**
     * @param string $accessToken
     *
     * @return array
     */
    public function facebookLogin($accessToken)
    {
        /** @var Facebook $manager */
        $manager = $this->facebookManager;
        $manager->setDefaultAccessToken($accessToken);
        try {
            $response = $manager->get('/me');
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
           return ['status' => false, 'message' => $e->getMessage()];
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
        $facebookUser = $response->getGraphUser();
        $appUser = $this->findOneBy(['facebookId'=> $facebookUser->getId()]);
        $isNull = false;
        if(!$appUser) {
            $isNull = true;
            $appUser = new AppUser();
            $appUser->setUsername($facebookUser->getId());
            $appUser->setFacebookId($facebookUser->getId());
            $appUser->setEmail($facebookUser->getId().'@facebook.com');
        }
        $appUser->setName($facebookUser->getName());
        $password = md5($accessToken);
        $appUser->setPlainPassword($password);

        if($isNull) {
            $this->createAppUser($appUser);
        }else{
            $this->saveAppUser($appUser);
        }

        return ['status' => true, 'password' => $password, 'username' => $appUser->getUsername()];
    }

    /**
     * @param string $accessToken
     * @param string $accessTokenSecret
     *
     * @return array
     */
    public function twitterLogin($accessToken, $accessTokenSecret)
    {
        /** @var TwitterOAuth $manager */
        $manager = $this->twitterManager;
        $manager->setOauthToken($accessToken, $accessTokenSecret);
        $response = $manager->get("account/verify_credentials");
        if(isset($response->errors) && count($response->errors) > 0) {
            return ['status' => false, 'message' => $response->errors[0]->message];
        }
        $twitter = $response;
        $appUser = $this->findOneBy(['twitterId'=> $twitter->id]);
        $isNull = false;
        if(!$appUser) {
            $isNull = true;
            $appUser = new AppUser();
            $appUser->setTwitterId($twitter->id);
            $appUser->setUsername($twitter->id);
            $appUser->setEmail($twitter->id.'@facebook.com');
        }
        $appUser->setName($twitter->name);
        $password = md5(sprintf("%s_%s",$accessToken,$accessTokenSecret));
        $appUser->setPlainPassword($password);

        if($isNull) {
            $this->createAppUser($appUser);
        }else{
            $this->saveAppUser($appUser);
        }

        return ['status' => true, 'password' => $password, 'username' => $appUser->getUsername()];
    }

    /**
     * List App User
     * @param array $params
     *
     * @return array
     */
    public function listAppUser($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
        if(isset($params['userName'])) {
            $conditions = [
                'username' => [
                    'type' => 'like',
                    'value' => "%".$params['userName']."%"
                ]
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        return $this->pagination->render($query, $limit, $offset);
    }

    /**
     * @param Facebook $facebookManager
     * @return AppUserManager
     */
    public function setFacebookManager($facebookManager)
    {
        $this->facebookManager = $facebookManager;
        return $this;
    }

    /**
     * @return Facebook
     */
    public function getFacebookManager()
    {
        return $this->facebookManager;
    }

    /**
     * @param TwitterOAuth $twitterManager
     * @return AppUserManager
     */
    public function setTwitterManager($twitterManager)
    {
        $this->twitterManager = $twitterManager;
        return $this;
    }

    /**
     * @return TwitterOAuth
     */
    public function getTwitterManager()
    {
        return $this->twitterManager;
    }
}