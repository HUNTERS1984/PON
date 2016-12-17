<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\SocialProfile;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Type;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class SocialProfileManager extends AbstractManager
{
    /**
     * @var TransformedFinder $socialProfileFinder
     */
    protected $socialProfileFinder;

    /**
     * @var Type
    */
    protected $socialProfileType;

    /**
     * @param SocialProfile $socialProfile
     *
     * @return SocialProfile
     */
    public function saveSocialProfile(SocialProfile $socialProfile)
    {
        $socialProfile->setUpdatedAt(new \DateTime());
        return $this->save($socialProfile);
    }

    /**
     * @param SocialProfile $socialProfile
     *
     * @return SocialProfile
     */
    public function createSocialProfile(SocialProfile $socialProfile)
    {
        $date = new \DateTime();
        $date->modify('-1 day');
        $socialProfile
            ->setRequestedAt($date)
            ->setCreatedAt(new \DateTime());
        return $this->saveSocialProfile($socialProfile);
    }

    /**
     * @param SocialProfile $socialProfile
     *
     * @return boolean
     */
    public function deleteSocialProfile(SocialProfile $socialProfile)
    {
        $socialProfile
            ->setDeletedAt(new \DateTime());
        return $this->saveSocialProfile($socialProfile);
    }

    /**
     * @param TransformedFinder $socialProfileFinder
     * @return SocialProfileManager
     */
    public function setSocialProfileFinder(TransformedFinder $socialProfileFinder)
    {
        $this->socialProfileFinder = $socialProfileFinder;
        return $this;
    }

    /**
     * get store
     *
     * @param AppUser $user
     * @param int $socialType
     * @return null | SocialProfile
     */
    public function getSocialProfile(AppUser $user, $socialType)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new Query\BoolQuery();
        $boolQuery
            ->addMust(new Query\Term(['appUser.id' => ['value' => $user->getId()]]))
            ->addMust(new Query\Term(['socialType' => ['value' => $socialType]]));

        $query->setQuery($boolQuery);
        $result = $this->socialProfileFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    public function getSocialProfileOfUsers()
    {
        $query = new Query();
        $query
            ->setPostFilter(new Missing('appUser.deletedAt'));
        $mainQuery = new Query\BoolQuery();
        $date = new \DateTime();
        $date->modify("-1 day");
        $mainQuery->addMust(new Query\Range('requestedAt',['lte' => $date->format(\DateTime::ISO8601)]));
        $query->setQuery($mainQuery);

        $pagination = $this->socialProfileFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 200);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return ['data' => $results, 'total' => $total];
    }

    /**
     * get coupon
     *
     * @param $id
     * @return null | SocialProfile
     */
    public function getSocialProfileById($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->socialProfileFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param Type $socialProfileType
     * @return SocialProfileManager
     */
    public function setSocialProfileType($socialProfileType)
    {
        $this->socialProfileType = $socialProfileType;

        return $this;
    }

    /**
     * @return Type
     */
    public function getSocialProfileType()
    {
        return $this->socialProfileType;
    }
}