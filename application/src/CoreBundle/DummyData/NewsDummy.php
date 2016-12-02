<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\News;
use CoreBundle\Entity\Photo;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\NewsPhoto;
use CoreBundle\Manager\NewsCategoryManager;
use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\NewsPhotoManager;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class NewsDummy extends BaseDummy implements IDummy
{
    /** @var NewsCategoryManager $newscategoryManager */
    private $newsCategoryManager;

    /** @var StoreManager $storeManager */
    private $storeManager;

    /** @var PhotoManager $photoManager */
    private $photoManager;

    /** @var NewsPhotoManager $newsPhotoManager */
    private $newsPhotoManager;

    /***
     * @var string $avatarDirPath
     */
    protected $avatarDirPath;

    /***
     * @var string $imageDirPath
     */
    protected $imageDirPath;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $news = new News();
        $name = $faker->name;
        $description = $faker->paragraph(3);
        $introduction = $faker->paragraph(3);
        $categoryId = $faker->numberBetween(1, 5);
        $newsCategory = $this->newsCategoryManager->findOneById($categoryId);
        $storeId = $faker->numberBetween(1, 10);
        $store = $this->storeManager->findOneById($storeId);
        $file = new Filesystem();
        if(!$file->exists($this->avatarDirPath)) {
            $file->mkdir($this->avatarDirPath);
        }

        if(!$file->exists($this->imageDirPath)) {
            $file->mkdir($this->imageDirPath);
        }

        $news
            ->setTitle($name)
            ->setImageUrl($this->manager->getImage($this->avatarDirPath))
            ->setDescription($description)
            ->setIntroduction($introduction)
            ->setNewsCategory($newsCategory)
            ->setStore($store);

        /** @var News $news*/
        $news = $this->manager->createNews($news);

        $output->writeln("");
        $progress = new ProgressBar($output, 2);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        $output->writeln("Begin Creating Photo...");
        for($i=0; $i< 2; $i++) {
            $photo = new Photo();
            $photo
                ->setImageUrl($this->manager->getImage($this->imageDirPath))
                ->setPhotoId($this->photoManager->createID('PH'));
            $photo = $this->photoManager->createPhoto($photo);
            $newsPhoto = new NewsPhoto();
            $newsPhoto
                ->setPhoto($photo)
                ->setNews($news);

            $newsPhoto = $this->newsPhotoManager->save($newsPhoto, false);

            //NEED TO REVIEW AGAIN
           // $news->addNewsPhoto($newsPhoto);
            $progress->advance();
        }
        $progress->finish();
        $output->writeln("");
        $output->writeln("Finished Creating Photo...");
        $output->writeln("");

        //NEED TO REVIEW AGAIN
        //$news = $this->manager->saveNews($news);

        return $news;
    }

    /**
     * @param mixed $newsCategoryManager
     * @return NewsDummy
     */
    public function setNewsCategoryManager($newsCategoryManager)
    {
        $this->newsCategoryManager = $newsCategoryManager;
        return $this;
    }

    /**
     * @param mixed $storeManager
     * @return NewsDummy
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }

    /**
     * @param PhotoManager $photoManager
     * @return NewsDummy
     */
    public function setPhotoManager($photoManager)
    {
        $this->photoManager = $photoManager;
        return $this;
    }

    /**
     * @param string $avatarDirPath
     * @return NewsDummy
     */
    public function setAvatarDirPath($avatarDirPath)
    {
        $this->avatarDirPath = $avatarDirPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarDirPath()
    {
        return $this->avatarDirPath;
    }

    /**
     * @param string $imageDirPath
     * @return NewsDummy
     */
    public function setImageDirPath($imageDirPath)
    {
        $this->imageDirPath = $imageDirPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageDirPath()
    {
        return $this->imageDirPath;
    }

    /**
     * @param NewsPhotoManager $newsPhotoManager
     * @return NewsDummy
     */
    public function setNewsPhotoManager(NewsPhotoManager $newsPhotoManager): NewsDummy
    {
        $this->newsPhotoManager = $newsPhotoManager;
        return $this;
    }
}