<?php
namespace CoreBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
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
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string $class
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->repository    = $objectManager->getRepository($class);
        $metadata            = $objectManager->getClassMetadata($class);
        $this->class         = $metadata->getName();
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

    /**
     * @param array $criteria
     * @return array
     */
    protected function find(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }



    /**
     * @param $entity
     * @param bool $andFlush
     * @return EntityInterface
     */
    protected function save($entity, $andFlush = true)
    {
        $this->objectManager->persist($entity);
        if (true === $andFlush) {
            $this->objectManager->flush();
        }
        return $entity;
    }
}