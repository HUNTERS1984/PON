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
        $mod = new Category();
        $name = $faker->name;

        $file = new Filesystem();
        if(!$file->exists($this->avatarDirPath)) {
            $file->mkdir($this->avatarDirPath);
        }


        $mod
            ->setCreatedAt(new \DateTime())
            ->setName($name)
            ->setIconUrl($this->manager->getImage($this->avatarDirPath))
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($mod);
        return $mod;
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