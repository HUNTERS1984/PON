<?php

namespace CoreBundle\Manager;

use Abraham\TwitterOAuth\TwitterOAuth;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\SocialProfile;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Search;
use Facebook\Facebook;
use Facebook\FacebookResponse;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use MetzWeb\Instagram\Instagram;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class AppUserManager extends AbstractManager
{
    /**
     * @var Facebook
    */
    protected $facebookManager;

    /**
     * @var Producer
    */
    protected $emailProducer;

    /**
     * @var Instagram
     */
    protected $instagramManager;

    /**
     * @var TwitterOAuth
    */
    protected $twitterManager;

    /**
     * @var TransformedFinder $appUserFinder
     */
    protected $appUserFinder;

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
        if(!$appUser->getAppUserId()) {
            $appUser->setAppUserId($this->createID('US'));
        }

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
     * get app user
     *
     * @param $id
     * @return null | AppUser
     */
    public function getAppUser($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->appUserFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * get app user
     *
     * @param $email
     * @return null | AppUser
     */
    public function getAppUserByEmail($email)
    {
        $query = new Query();
        $matchQuery = new Query\Match('email',$email);
        $matchQuery->setFieldAnalyzer('email', 'keyword_analyzer');
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery($matchQuery);
        $result = $this->appUserFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    public function sendForGotPasswordEmail(AppUser $user)
    {
        $data = [
            'subject' => 'Test email',
            'sender' => 'vodanhdanh2016@gmail.com',
            'sender_name' => 'Test',
            'recipient' => $user->getEmail(),
            'body' => ' Test Email',
        ];
        $this->emailProducer->publish(serialize($data));
    }

    /**
     * get app user
     *
     * @param $username
     * @return null | AppUser
     */
    public function getAppUserByUsername($username)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['username' => ['value' => $username]]));
        $result = $this->appUserFinder->find($query);
        return !empty($result) ? $result[0] : null;
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

        /** @var SocialProfile $socialProfile */
        foreach($appUser->getSocialProfiles() as $socialProfile) {
            $socialProfile->setDeletedAt(new \DateTime());
        }

        return $this->saveAppUser($appUser);
    }

    /**
     * @param string $accessToken
     *
     * @return array
     */
    public function facebookLogin($accessToken)
    {
        $result = $this->getFacebookAccess($accessToken);
        if(!$result['status']) {
            return $result;
        }

        /** @var FacebookResponse $response*/
        $response = $result['response'];
        $facebookUser = $response->getGraphUser();
        $appUser = $this->findOneBy(['facebookId'=> $facebookUser->getId()]);
        $isNull = false;
        if(!$appUser) {
            $isNull = true;
            $appUser = new AppUser();
            $appUser->setUsername($facebookUser->getId());
            $appUser->setFacebookId($facebookUser->getId());
            $appUser->setRoles(['ROLE_APP']);
            $appUser->setEmail($facebookUser->getId().'@facebook.com');
        }
        $appUser->setName($facebookUser->getName());
        $password = md5($accessToken);
        $appUser->setPlainPassword($password);
        $date = new \DateTime();
        $date->modify("-5 days");
        if($isNull) {
            $appUser = $this->createAppUser($appUser);
            $socialProfile = new SocialProfile();
            $socialProfile
                ->setSocialType(1)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setRequestedAt($date)
                ->setError(false)
                ->setAppUser($appUser)
                ->setSocialId($facebookUser->getId())
                ->setSocialToken($accessToken);
            $appUser->addSocialProfile($socialProfile);
        }else{
            $hasFacebookProfile = false;
            /** @var SocialProfile $socialProfile */
            foreach($appUser->getSocialProfiles() as $socialProfile) {
                if($socialProfile->getSocialType() == 1) {
                    $socialProfile
                        ->setUpdatedAt(new \DateTime())
                        ->setError(false)
                        ->setSocialId($facebookUser->getId())
                        ->setSocialToken($accessToken);
                    $hasFacebookProfile = true;
                    break;
                }
            }
            if(!$hasFacebookProfile) {
                $socialProfile = new SocialProfile();
                $socialProfile
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setSocialType(1)
                    ->setRequestedAt($date)
                    ->setError(false)
                    ->setAppUser($appUser)
                    ->setSocialId($facebookUser->getId())
                    ->setSocialToken($accessToken);
                $appUser->addSocialProfile($socialProfile);
            }
        }
        $this->saveAppUser($appUser);

        return ['status' => true, 'password' => $password, 'username' => $appUser->getUsername()];
    }

    /**
     * @param string $accessToken
     *
     * @return array
     */
    public function instagramLogin($accessToken)
    {
        $result = $this->getInstagramAccess($accessToken);
        if(!$result['status']) {
            return $result;
        }

        $response = $result['response'];
        $instagramUser = $response->data;
        $appUser = $this->findOneBy(['instagramId'=> $instagramUser->id]);
        $isNull = false;
        if(!$appUser) {
            $isNull = true;
            $appUser = new AppUser();
            $appUser->setUsername($instagramUser->id);
            $appUser->setInstagramId($instagramUser->id);
            $appUser->setRoles(['ROLE_APP']);
            $appUser->setEmail($instagramUser->id.'@instagram.com');
        }
        $appUser->setName($instagramUser->full_name);
        $password = md5($accessToken);
        $appUser->setPlainPassword($password);
        $date = new \DateTime();
        $date->modify("-5 days");

        if($isNull) {
            $appUser = $this->createAppUser($appUser);
            $socialProfile = new SocialProfile();
            $socialProfile
                ->setSocialType(3)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setRequestedAt($date)
                ->setError(false)
                ->setAppUser($appUser)
                ->setSocialId($instagramUser->id)
                ->setSocialToken($accessToken);
            $appUser->addSocialProfile($socialProfile);
        }else{
            $hasInstagramProfile = false;
            /** @var SocialProfile $socialProfile */
            foreach($appUser->getSocialProfiles() as $socialProfile) {
                if($socialProfile->getSocialType() == 3) {
                    $socialProfile
                        ->setUpdatedAt(new \DateTime())
                        ->setError(false)
                        ->setSocialId($instagramUser->id)
                        ->setSocialToken($accessToken);
                    $hasInstagramProfile = true;
                    break;
                }
            }
            if(!$hasInstagramProfile) {
                $socialProfile = new SocialProfile();
                $socialProfile
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setSocialType(3)
                    ->setRequestedAt($date)
                    ->setError(false)
                    ->setAppUser($appUser)
                    ->setSocialId($instagramUser->id)
                    ->setSocialToken($accessToken);
                $appUser->addSocialProfile($socialProfile);
            }
        }
        $this->saveAppUser($appUser);
        return ['status' => true, 'password' => $password, 'username' => $appUser->getUsername()];
    }

    public function getFacebookAccess($accessToken)
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
        return ['status' => true, 'response' => $response];
    }

    public function getInstagramAccess($accessToken)
    {
        /** @var Instagram $manager */
        $manager = $this->instagramManager;
        $manager->setAccessToken($accessToken);
        try {
            $response = $manager->getUser();
            if(!isset($response->meta->code) || $response->meta->code != 200) {
                return ['status' => false, 'message' => 'Can not access instagram'];
            }
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
        return ['status' => true, 'response' => $response];
    }

    /**
     * @param string $accessToken
     * @param string $accessTokenSecret
     *
     * @return array
     */
    public function twitterLogin($accessToken, $accessTokenSecret)
    {
        $result = $this->getTwitterAccess($accessToken, $accessTokenSecret);
        if(!$result['status']) {
            return $result;
        }

        $twitter = $result['response'];
        $appUser = $this->findOneBy(['twitterId'=> $twitter->id]);
        $isNull = false;
        if(!$appUser) {
            $isNull = true;
            $appUser = new AppUser();
            $appUser->setTwitterId($twitter->id);
            $appUser->setUsername($twitter->id);
            $appUser->setRoles(['ROLE_APP']);
            $appUser->setEmail($twitter->id.'@twitter.com');
        }
        $appUser->setName($twitter->name);
        $password = md5(sprintf("%s_%s",$accessToken,$accessTokenSecret));
        $appUser->setPlainPassword($password);
        $date = new \DateTime();
        $date->modify("-5 days");

        if($isNull) {
            $appUser = $this->createAppUser($appUser);
            $socialProfile = new SocialProfile();
            $socialProfile
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setSocialType(2)
                ->setAppUser($appUser)
                ->setError(false)
                ->setRequestedAt($date)
                ->setSocialId($twitter->id)
                ->setSocialToken($accessToken)
                ->setSocialSecret($accessTokenSecret);
            $appUser->addSocialProfile($socialProfile);

        }else{
            $hasTwitterProfile = false;
            /** @var SocialProfile $socialProfile */
            foreach($appUser->getSocialProfiles() as $socialProfile) {
                if($socialProfile->getSocialType() == 2) {
                    $socialProfile
                        ->setUpdatedAt(new \DateTime())
                        ->setError(false)
                        ->setSocialId($twitter->id)
                        ->setSocialToken($accessToken)
                        ->setSocialSecret($accessTokenSecret);
                    $hasTwitterProfile = true;
                    break;
                }
            }
            if(!$hasTwitterProfile) {
                $socialProfile = new SocialProfile();
                $socialProfile
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setSocialType(2)
                    ->setAppUser($appUser)
                    ->setRequestedAt($date)
                    ->setError(false)
                    ->setSocialId($twitter->id)
                    ->setSocialToken($accessToken)
                    ->setSocialSecret($accessTokenSecret);
                $appUser->addSocialProfile($socialProfile);
            }
        }
        $this->saveAppUser($appUser);

        return ['status' => true, 'password' => $password, 'username' => $appUser->getUsername()];
    }

    public function getTwitterAccess($accessToken, $accessTokenSecret)
    {
        /** @var TwitterOAuth $manager */
        $manager = $this->twitterManager;
        $manager->setOauthToken($accessToken, $accessTokenSecret);
        $response = $manager->get("account/verify_credentials");
        if(isset($response->errors) && count($response->errors) > 0) {
            return ['status' => false, 'message' => $response->errors[0]->message];
        }
        return ['status' => true, 'response' => $response];
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
     * List App User From Admin
     * @param array $params
     *
     * @return array
     */
    public function getAppUserManagerFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['username','email','company','address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        }
        $roleQuery = new Query\BoolQuery();
        $roleQuery
            ->addShould(new Query\Match('roles', 'ROLE_CLIENT'))
            ->addShould(new Query\Match('roles', 'ROLE_ADMIN'));

        $boolQuery->addMust($roleQuery);
        $query->setQuery($boolQuery);

        $pagination = $this->appUserFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List App User From Client
     * @param array $params
     *
     * @return array
     */
    public function getAppUserManagerFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['username','email','company','address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        }
        $boolQuery
            ->addMust(new Query\Match('roles', 'ROLE_CLIENT'))
            ->addMust(new Query\Term(['store.id' => ['value' => $user->getStore()->getId()]]));
        $query->setQuery($boolQuery);

        $pagination = $this->appUserFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List Customer From Admin
     * @param array $params
     *
     * @return array
     */
    public function getCustomerFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['username','email','company','address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        }
        $roleQuery = new Query\BoolQuery();
        $roleQuery
            ->addShould(new Query\Match('roles', 'ROLE_APP'));

        $boolQuery->addMust($roleQuery);
        $query->setQuery($boolQuery);

        $pagination = $this->appUserFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List Customer From Client
     * @param array $params
     *
     * @return array
     */
    public function getCustomerFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['username','email','company','address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        }
        $boolQuery
            ->addMust(new Query\Match('roles', 'ROLE_APP'))
            ->addMust(new Query\Term(['store.id' => ['value' => $user->getStore()->getId()]]));
        $query->setQuery($boolQuery);

        $pagination = $this->appUserFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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

    /**
     * @param TransformedFinder $appUserFinder
     * @return AppUserManager
     */
    public function setAppUserFinder($appUserFinder)
    {
        $this->appUserFinder = $appUserFinder;
        return $this;
    }

    /**
     * @param Instagram $instagramManager
     * @return AppUserManager
     */
    public function setInstagramManager(Instagram $instagramManager)
    {
        $this->instagramManager = $instagramManager;
        return $this;
    }

    /**
     * @return TransformedFinder
     */
    public function getAppUserFinder()
    {
        return $this->appUserFinder;
    }

    /**
     * @param Producer $emailProducer
     * @return AppUserManager
     */
    public function setEmailProducer($emailProducer)
    {
        $this->emailProducer = $emailProducer;

        return $this;
    }
}