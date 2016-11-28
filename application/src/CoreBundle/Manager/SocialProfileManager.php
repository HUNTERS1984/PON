<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\SocialProfile;
use Elastica\Filter\Missing;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class SocialProfileManager extends AbstractManager
{
    /**
     * @var TransformedFinder $socialProfileFinder
     */
    protected $socialProfileFinder;

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
        $socialProfile->setCreatedAt(new \DateTime());
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
}