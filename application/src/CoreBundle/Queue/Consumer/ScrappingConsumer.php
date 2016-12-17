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
use CoreBundle\SNS\Type\BasePostType;
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
            $socialProfile = $this->socialProfileManager->getSocialProfileById($socialProfile->getId());
            if(!$socialProfile) {
                throw new NotFoundHttpException(sprintf("Can not find SocialProfile with ID: %s", $socialProfile->getId()));
            }
            $posts = $this->getPosts($socialProfile);
            foreach($posts as $post) {
                $this->createPost($post, $socialProfile->getAppUser());
            }
            $this->logger->info(sprintf("Finished Scrapping Job Successfully %s", $socialProfile->getId()));
        } catch (\Exception $e) {
            $this->logger->error($e->getTraceAsString());
            $this->logger->error(sprintf("Scrapping Job Was Failed %s", $socialProfile->getId()));
            $this->logger->error($e->getMessage());
        }
    }

    public function createPost(BasePostType $snsPost, AppUser $appUser)
    {
        /** @var Post $post */
        if(!$post = $this->postManager->findOneBy(['snsId'=> $snsPost->getId()])) {
            $post = new Post();
            $post
                ->setStatus(0)
                ->setAppUser($appUser)
                ->setSnsId($snsPost->getId())
                ->setUrl($snsPost->getUrl())
                ->setCreatedTime($snsPost->getCreatedAt())
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

    public function getPosts(SocialProfile $socialProfile)
    {
        $from = clone $socialProfile->getRequestedAt();
        $to = new \DateTime();
        $from->setTime(0,0,0);
        $to->setTime(23,59,59);
        $posts = $this->snsClient
            ->setType($socialProfile->getSocialType())
            ->setAccessToken($socialProfile->getSocialToken())
            ->setTokenSecret($socialProfile->getSocialSecret())
            ->listPost($from, $to);
        $this->updateRequestedAt($socialProfile);

        return $posts;
    }

    public function updateRequestedAt(SocialProfile $socialProfile)
    {
        $socialProfile->setRequestedAt(new \DateTime());
        $socialProfile = $this->socialProfileManager->saveSocialProfile($socialProfile);
        $this->socialProfileManager->refresh($socialProfile);

        return $socialProfile;

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