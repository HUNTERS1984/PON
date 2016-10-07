<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Paginator\Pagination;
use Facebook\Facebook;

class AppUserManager extends AbstractManager
{
    /**
     * @var Facebook
    */
    protected $facebookManager;

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
        if(!$appUser) {
            $appUser = new AppUser();
        }
        $appUser->setName($facebookUser->getName());
        $password = md5($accessToken);
        $appUser->setPlainPassword($password);
        $appUser->setFacebookId($facebookUser->getId());
        $appUser->setUsername($facebookUser->getId());
        if(!$appUser) {
            $this->createAppUser($appUser);
        }else{
            $this->saveAppUser($appUser);
        }

        return ['status' => true, 'password' => $password, 'user' => $facebookUser];
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
}