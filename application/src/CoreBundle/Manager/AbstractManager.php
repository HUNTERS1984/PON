<?php
namespace CoreBundle\Manager;


use CoreBundle\Utils\StringGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Elastica\QueryBuilder\DSL\Query;
use Faker\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Tests\Fixtures\EntityInterface;

abstract class AbstractManager
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|\Doctrine\ORM\EntityManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $avatarDir;

    /**
     * @var string
     */
    protected $imageDir;

    /**
     * @var string
     */
    private $sampleImagePath;

    /**
     * @param string $sampleImagePath
     * @return $this
     */
    public function setSampleImagePath($sampleImagePath)
    {
        $this->sampleImagePath = $sampleImagePath;

        return $this;
    }

    /**
     * @param string $destinationPath
     *
     * @return string
     */
    public function getImage($destinationPath)
    {
        $file = new Filesystem();
        if (!$file->exists($destinationPath)) {
            $file->mkdir($destinationPath);
        }

        $faker = Factory::create('ja_JP');
        $files = glob($this->sampleImagePath."/*.jpg");
        $fileSystem = new Filesystem();
        $originFile = $faker->randomElement($files);
        $baseName = $faker->md5.".jpg";
        $fileSystem->copy($originFile, sprintf("%s/%s", $destinationPath, $baseName));

        return $baseName;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string $class
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository($class);
        $metadata = $objectManager->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @param int|string $id
     * @return EntityInterface
     */
    public function findOneById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function uploadAvatar(UploadedFile $fileUpload, $id)
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->avatarDir)) {
            $fileSystem->mkdir($this->avatarDir);
        }
        $newFile = sprintf("%s.%s", $id, $fileUpload->guessExtension());
        $fileUpload->move(
            $this->avatarDir,
            $newFile
        );

        return $newFile;
    }

    public function uploadImage(UploadedFile $fileUpload, $id)
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->imageDir)) {
            $fileSystem->mkdir($this->imageDir);
        }
        $newFile = sprintf("%s.%s", $id, $fileUpload->guessExtension());
        $fileUpload->move(
            $this->imageDir,
            $newFile
        );

        return $newFile;
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function find(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @param $entity
     *
     * @return boolean
     */
    public function delete($entity, $andFlush = true)
    {
        $this->objectManager->remove($entity);
        if (true === $andFlush) {
            $this->objectManager->flush();
        }

        return true;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * @param $entity
     * @param bool $andFlush
     * @return EntityInterface
     */
    public function save($entity, $andFlush = true)
    {
        $this->objectManager->persist($entity);
        if (true === $andFlush) {
            $this->objectManager->flush();
        }

        return $entity;
    }

    /**
     * @param $entity
     * @return EntityInterface
     */
    public function refresh($entity)
    {
        $this->objectManager->refresh($entity);
    }

    /**
     * @return EntityInterface
     */
    public function clear()
    {
        $this->objectManager->clear();
    }

    /**
     * Get Query
     * @param array $criticals
     * @param array $orderBys
     * @param int $limit
     * @param int $offset
     *
     * @return Query
     *
    */
    public function getQuery($criticals = [], $orderBys = [], $limit = 10, $offset = 0)
    {
        /** @var QueryBuilder $qb*/
        $qb =  $this->repository
            ->createQueryBuilder('p');

        $index = 0;
        foreach($criticals as $key => $critical) {
            if(in_array($critical['type'], ['is', 'is not'])) {
                $qb->andWhere("p.$key ".$critical['type']." ".$critical['value']);
            }else{
                $qb->andWhere("p.$key ".$critical['type']." ?$index")
                    ->setParameter($index, $critical['value']);
                $index++;
            }
        }


        foreach($orderBys as $key => $orderBy) {
            $qb->addOrderBy("p.$key", $orderBy);
        }

        // Create our query
        return $qb->getQuery();
    }

    public function createID($prefix = 'ID')
    {
        return $prefix . StringGenerator::uniqueGenerate(10, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }

    /**
     * @param string $avatarDir
     * @return $this
     */
    public function setAvatarDir($avatarDir)
    {
        $this->avatarDir = $avatarDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarDir()
    {
        return $this->avatarDir;
    }

    /**
     * @param string $imageDir
     * @return $this
     */
    public function setImageDir($imageDir)
    {
        $this->imageDir = $imageDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageDir()
    {
        return $this->imageDir;
    }

    /**
     * @return ObjectManager|\Doctrine\ORM\EntityManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}