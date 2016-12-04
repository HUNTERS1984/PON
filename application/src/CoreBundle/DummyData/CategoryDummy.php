<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Category;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CategoryDummy extends BaseDummy implements IDummy
{
    /***
     * @var string $avatarDirPath
     */
    protected $avatarDirPath;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $category = new Category();
        $name = $faker->name;

        $file = new Filesystem();
        if(!$file->exists($this->avatarDirPath)) {
            $file->mkdir($this->avatarDirPath);
        }
        
        $category
            ->setCreatedAt(new \DateTime())
            ->setName($name)
            ->setIconUrl($this->manager->getImage($this->avatarDirPath));
        $this->manager->createCategory($category);
        return $category;
    }

    /**
     * @param string $avatarDirPath
     * @return CategoryDummy
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
}