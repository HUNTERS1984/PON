<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;

class UseListManager extends AbstractManager
{
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

}