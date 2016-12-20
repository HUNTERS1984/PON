<?php

namespace CoreBundle\Queue\Consumer;


use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Photo;
use CoreBundle\Entity\Post;
use CoreBundle\Entity\PostPhoto;
use CoreBundle\Entity\SocialProfile;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\PostManager;
use CoreBundle\Manager\PostPhotoManager;
use CoreBundle\Manager\SocialProfileManager;
use CoreBundle\SNS\Client;
use CoreBundle\SNS\Exception\AuthenticationException;
use CoreBundle\SNS\Type\BasePostType;
use Doctrine\DBAL\DBALException;
use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScrappingConsumer implements ConsumerInterface
{
    /**
     * @var Client
    */
    private $snsClient;

    /**
     * @var Logger
    */
    private $logger;

    /**
     * @var AppUserManager $appUserManager
    */
    private $appUserManager;

    /**
     * @var PostManager
     */
    private $postManager;

    /**
     * @var PostPhotoManager
     */
    private $postPhotoManager;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * @var SocialProfileManager
    */
    private $socialProfileManager;

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        /** @var SocialProfile $socialProfile */
        $socialProfile = unserialize($msg->getBody());
        try {
            $this->process($socialProfile);
            $this->logger->info(sprintf("Finished Scrapping Job Successfully %s", $socialProfile->getId()));
        }catch (DBALException $e) {
            $this->logger->info(sprintf("Retry Connection Database %s",$socialProfile->getId()));
            $this->socialProfileManager->getObjectManager()->getConnection()->connect();
            $this->process($socialProfile);
        } catch (\Exception $e) {
            $this->logger->error($e->getTraceAsString());
            $this->logger->error(sprintf("Scrapping Job Was Failed %s", $socialProfile->getId()));
            $this->logger->error($e->getMessage());
        }
    }

    public function process(SocialProfile $socialProfile)
    {
        $this->socialProfileManager->clear();
        /** @var SocialProfile $socialProfile */
        $socialProfile = $this->socialProfileManager->findOneById($socialProfile->getId());
        if(!$socialProfile) {
            throw new NotFoundHttpException(sprintf("Can not find SocialProfile with ID: %s", $socialProfile->getId()));
        }
        $posts = $this->getPosts($socialProfile);
        $this->logger->info(sprintf("Got %s Posts", count($posts)));
        $this->updateRequestedAt($socialProfile);
        foreach($posts as $post) {
            $this->createPost($post, $socialProfile->getAppUser(), $socialProfile->getSocialType());
        }
    }

    public function createPost(BasePostType $snsPost, AppUser $appUser, $type)
    {
        /** @var Post $post */
        $this->postManager->clear();
        if(!$post = $this->postManager->findOneBy(['snsId'=> $snsPost->getId()])) {
            $post = new Post();
            $this->appUserManager->clear();
            $appUser = $this->appUserManager->findOneById($appUser->getId());
            $post
                ->setStatus(0)
                ->setAppUser($appUser)
                ->setSnsId($snsPost->getId())
                ->setType($type)
                ->setUrl($snsPost->getUrl())
                ->setCreatedTime($snsPost->getCreatedAt())
                ->setHashTags($this->convertHashTagsToString($snsPost->getHashTags()))
                ->setMessage($snsPost->getMessage());
            $post = $this->postManager->createPost($post);
        }else{
            foreach($post->getPostPhotos() as $key => $postPhoto) {
                $postPhoto->setPhoto(null);
                $postPhoto->setPost(null);
                $post->getPostPhotos()->remove($key);
                $this->postPhotoManager->delete($postPhoto);
            }
            $post
                ->setSnsId($snsPost->getId())
                ->setUrl($snsPost->getUrl())
                ->setCreatedTime($snsPost->getCreatedAt())
                ->setHashTags($this->convertHashTagsToString($snsPost->getHashTags()))
                ->setMessage($snsPost->getMessage());
            $post = $this->postManager->savePost($post);
        }

        foreach ($snsPost->getImages() as $image) {
            $postPhoto = new PostPhoto();
            $photo = new Photo();
            $photo
                ->setPhotoId($this->postPhotoManager->createID('PP'))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setImageUrl($image);

            $postPhoto
                ->setPhoto($photo)
                ->setPost($post);
            $this->postPhotoManager->save($postPhoto);
        }
        $this->postManager->refresh($post);
    }

    public function convertHashTagsToString(array $hashTags)
    {
        return implode(
            ',',
            array_map(function($val) {
                return sprintf("#%s",strtolower($val));
            } , $hashTags)
        );
    }

    public function getPosts(SocialProfile $socialProfile)
    {
        $from = clone $socialProfile->getRequestedAt();
        $to = new \DateTime();
        $from->setTime(0,0,0);
        $to->setTime(23,59,59);
        $posts = [];
        $this->logger->info(sprintf("Social Type %s - Request Parameters: %s",$socialProfile->getSocialType(),print_r([$from, $to], true)));
        try{
            $posts = $this->snsClient
                ->setType($socialProfile->getSocialType())
                ->setAccessToken($socialProfile->getSocialToken())
                ->setTokenSecret($socialProfile->getSocialSecret())
                ->listPost($from, $to);
        }catch(AuthenticationException $e) {
            $socialProfile->setError(true);
            $this->saveSocialProfile($socialProfile);
            throw $e;
        }

        return $posts;
    }

    public function saveSocialProfile(SocialProfile $socialProfile)
    {
        $socialProfile = $this->socialProfileManager->saveSocialProfile($socialProfile);
        $this->socialProfileManager->refresh($socialProfile);

        return $socialProfile;
    }

    public function updateRequestedAt(SocialProfile $socialProfile)
    {
        $socialProfile->setRequestedAt(new \DateTime());
        return $this->saveSocialProfile($socialProfile);

    }

    /**
     * @param Client $snsClient
     * @return ScrappingConsumer
     */
    public function setSnsClient($snsClient)
    {
        $this->snsClient = $snsClient;
        return $this;
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
     * @param AppUserManager $appUserManager
     * @return ScrappingConsumer
     */
    public function setAppUserManager($appUserManager)
    {
        $this->appUserManager = $appUserManager;
        return $this;
    }

    /**
     * @param SocialProfileManager $socialProfileManager
     * @return ScrappingConsumer
     */
    public function setSocialProfileManager($socialProfileManager)
    {
        $this->socialProfileManager = $socialProfileManager;
        return $this;
    }

    /**
     * @return SocialProfileManager
     */
    public function getSocialProfileManager()
    {
        return $this->socialProfileManager;
    }

    /**
     * @param PostManager $postManager
     * @return ScrappingConsumer
     */
    public function setPostManager(PostManager $postManager)
    {
        $this->postManager = $postManager;
        return $this;
    }

    /**
     * @return PostManager
     */
    public function getPostManager()
    {
        return $this->postManager;
    }

    /**
     * @param PostPhotoManager $postPhotoManager
     * @return ScrappingConsumer
     */
    public function setPostPhotoManager(PostPhotoManager $postPhotoManager)
    {
        $this->postPhotoManager = $postPhotoManager;
        return $this;
    }

    /**
     * @return PostPhotoManager
     */
    public function getPostPhotoManager()
    {
        return $this->postPhotoManager;
    }

    /**
     * @param PhotoManager $photoManager
     * @return ScrappingConsumer
     */
    public function setPhotoManager($photoManager)
    {
        $this->photoManager = $photoManager;
        return $this;
    }

    /**
     * @return PhotoManager
     */
    public function getPhotoManager()
    {
        return $this->photoManager;
    }
}