<?php

namespace CoreBundle\Queue\Consumer;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\Post;
use CoreBundle\Entity\UseList;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\PostManager;
use CoreBundle\Manager\UseListManager;
use Doctrine\DBAL\DBALException;
use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class SyncPostConsumer implements ConsumerInterface
{

    /**
     * @var Logger
    */
    private $logger;

    /**
     * @var PostManager
     */
    private $postManager;

    /**
     * @var CouponManager
    */
    private $couponManager;

    /**
     * @var UseListManager
    */
    private $useListManager;

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        /** @var Post $post */
        $post = unserialize($msg->getBody());
        try {
            $this->process($post);
            $this->logger->info(sprintf("Finished Sync Post Job Successfully %s", $post->getId()));
        }catch (DBALException $e) {
            $this->logger->info(sprintf("Retry Connection Database %s",$post->getId()));
            $this->process($post);
        } catch (\Exception $e) {
            $this->logger->error($e->getTraceAsString());
            $this->logger->error(sprintf("Sync Post Job Was Failed With ID: %s", $post->getId()));
            $this->logger->error($e->getMessage());
        }

        die();
    }

    public function process(Post $post)
    {
        $this->postManager->clear();
        $this->useListManager->clear();
        $id = $post->getId();
        /** @var Post $post */
        if(!$post = $this->postManager->findOneById($id)) {
            throw new \Exception(sprintf("Could not found Post With ID: %s", $id));
        }

        $hashTags = $post->getHashTags(); //$this->convertHashTagsToArray($post->getHashTags());
        $result = $this->findCouponByHashTag($hashTags);
        $coupons = $result['data'];
        foreach($coupons as $item) {
            /** @var Coupon $coupon */
            $coupon = $item;
            if($this->hasCouponExitsInUser($post->getAppUser(), $coupon)) {
               continue;
            }

            $useList = $this->createUseList($post, $coupon);
            $this->useListManager->refresh($useList);
        }

        $this->updatePostStatus($post);

    }

    public function updatePostStatus(Post $post)
    {
        $post->setStatus(1);
        $post = $this->postManager->savePost($post);
        $this->postManager->refresh($post);
        return $post;
    }

    public function createUseList(Post $post, Coupon $coupon)
    {
        $useList = new UseList();
        $useList
            ->setAppUser($post->getAppUser())
            ->setPost($post)
            ->setStatus(0)
            ->setExpiredTime($coupon->getExpiredTime())
            ->setCoupon($coupon);
        return $this->useListManager->createUseList($useList);
    }

    public function hasCouponExitsInUser(AppUser $user, Coupon $coupon)
    {
        $useList = $this->useListManager->findUseCoupon($user, $coupon);
        return $useList ? true: false;
    }

    public function findCouponByHashTag($hashTags)
    {
        return $this->couponManager->findCouponByHashTag($hashTags);
    }

    public function convertHashTagsToArray($hashTags)
    {
        $hashTags = str_replace('#','', $hashTags);
        return explode(',', $hashTags);
    }

    /**
     * @param Logger $logger
     * @return ScrappingConsumer
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param PostManager $postManager
     * @return SyncPostConsumer
     */
    public function setPostManager($postManager)
    {
        $this->postManager = $postManager;

        return $this;
    }

    /**
     * @param CouponManager $couponManager
     * @return SyncPostConsumer
     */
    public function setCouponManager($couponManager)
    {
        $this->couponManager = $couponManager;

        return $this;
    }

    /**
     * @param UseListManager $useListManager
     * @return SyncPostConsumer
     */
    public function setUseListManager($useListManager)
    {
        $this->useListManager = $useListManager;

        return $this;
    }
}