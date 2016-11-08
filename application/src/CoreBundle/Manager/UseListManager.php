<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class UseListManager extends AbstractManager
{
    /**
     * @var TransformedFinder $couponFinder
     */
    protected $useListFinder;

    /**
     * @param UseList $useList
     *
     * @return UseList
     */
    public function createNews(UseList $useList)
    {
        $useList->setCreatedAt(new \DateTime());
        $this->saveUseList($useList);
    }

    /**
     * @param UseList $useList
     *
     * @return UseList
     */
    public function saveUseList(UseList $useList)
    {
        $useList->setUpdatedAt(new \DateTime());
        return $this->save($useList);
    }


    /**
     * get QR Code
     *
     * @param AppUser $user
     * @param Coupon $coupon
     * @return string|null
     */
    public function getCode(AppUser $user = null, Coupon $coupon)
    {
        if (!$user) {
            return null;
        }
        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);
        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $statusQuery = new Term(['status' => 1]);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($userQuery)
            ->addMust($statusQuery);
        $result = $this->useListFinder->find($query);

        return !empty($result) ? $result[0]->getCode() : null;
    }


    /**
     * Accept Coupon
     * @param UseList $useList
     *
     * @return array
     */
    public function acceptCoupon(UseList $useList)
    {
        $useList->setStatus(3);
        return $this->saveUseList($useList);
    }

    /**
     * Decline Coupon
     * @param UseList $useList
     *
     * @return array
     */
    public function declineCoupon(UseList $useList)
    {
        $useList->setStatus(4);
        return $this->saveUseList($useList);
    }

    /**
     * @param TransformedFinder $useListFinder
     * @return UseListManager
     */
    public function setUseListFinder($useListFinder)
    {
        $this->useListFinder = $useListFinder;
        return $this;
    }

}